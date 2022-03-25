<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Refund;
use App\Models\Shop;
use App\Contracts\PaymentServiceContract;
use App\Observers\OrderObserver;
use App\Observers\RefundObserver;
use App\Observers\ShopObserver;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
// use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (
            isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        ) {
            URL::forceScheme('https');
        }

        Blade::withoutDoubleEncoding();
        Paginator::useBootstrapThree();

        Shop::observe(ShopObserver::class);
        Order::observe(OrderObserver::class);
        Refund::observe(RefundObserver::class);

        // Add Google recaptcha validation rule
        Validator::extend('recaptcha', 'App\\Helpers\\ReCaptcha@validate');

        // Disable encryption for gdpr cookie
        $this->app->resolving(EncryptCookies::class, function (EncryptCookies $encryptCookies) {
            $encryptCookies->disableFor(config('gdpr.cookie.name'));
        });

        // Add pagination on collections
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
                $q = url()->full();
                // Remove unwanted page parameter from the url if exist
                if (Request::has('page')) {
                    $q = remove_url_parameter($q, 'page');
                }

                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

                $paginator = new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, $options);

                return $paginator->withPath($q);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Need for cashier
        Cashier::ignoreMigrations();
        Cashier::useCustomerModel('App\\Models\\Shop');

        //Payment method binding for wallet deposit
        if (Request::has('payment_method')) {
            $className = $this->resolvePaymentDependency(Request::get('payment_method'));
            $this->app->bind(PaymentServiceContract::class, $className);
        }

        // Ondemand Img manupulation
        $this->app->singleton(
            \League\Glide\Server::class,
            function ($app) {
                $filesystem = $app->make(Filesystem::class);

                return \League\Glide\ServerFactory::create([
                    'response' => new \League\Glide\Responses\LaravelResponseFactory(app('request')),
                    'driver' => config('image.driver'),
                    'presets' => config('image.sizes'),
                    'source' => $filesystem->getDriver(),
                    'cache' => $filesystem->getDriver(),
                    'cache_path_prefix' => config('image.cache_dir'),
                    'base_url' => 'image', //Don't change this value
                ]);
            }
        );
    }

    private function resolvePaymentDependency($class_name)
    {
        switch ($class_name) {
            case 'stripe':
            case 'saved_card':
                return \App\Services\Payments\StripePaymentService::class;

            case 'instamojo':
                return \App\Services\Payments\InstamojoPaymentService::class;

            case 'authorize-net':
                return \App\Services\Payments\AuthorizeNetPaymentService::class;

            case 'cybersource':
                return \App\Services\Payments\CybersourcePaymentService::class;

            case 'paystack':
                return \App\Services\Payments\PaystackPaymentService::class;

            case 'paypal-express':
                return \App\Services\Payments\PaypalExpressPaymentService::class;

            case 'paypal-marketplace':
                return \Incevio\Package\PaypalMarketplace\Services\PaypalMarketplacePaymentService::class;

            case 'wire':
                return \App\Services\Payments\WirePaymentService::class;

            case 'cod':
                return \App\Services\Payments\CodPaymentService::class;

            case 'pip':
                return \App\Services\Payments\PipPaymentService::class;

            case 'zcart-wallet':
                return \Incevio\Package\Wallet\Services\WalletPaymentService::class;

            case 'razorpay':
                return \Incevio\Package\Razorpay\Services\RazorpayPaymentService::class;

            case 'jrfpay':
                return \Incevio\Package\Jrfpay\Services\JrfpayPaymentService::class;

            case 'mpesa':
                return \Incevio\Package\MPesa\Services\MPesaPaymentService::class;
        }

        throw new \ErrorException("Error: Payment Method {$class_name} Not Found.");
    }
}
