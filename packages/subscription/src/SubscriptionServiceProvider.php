<?php

namespace Incevio\Package\Subscription;

// use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Rateable;
// use Incevio\Package\Wallet\Interfaces\Storable;
// use Incevio\Package\Wallet\Models\Transaction;
// use Incevio\Package\Wallet\Models\Transfer;
// use Incevio\Package\Wallet\Models\Wallet;
// use Incevio\Package\Wallet\Objects\Bring;
// use Incevio\Package\Wallet\Objects\Cart;
// use Incevio\Package\Wallet\Objects\EmptyLock;
// use Incevio\Package\Wallet\Objects\Operation;
// use Incevio\Package\Wallet\Services\CommonService;
// use App\Services\DbService;
// use Incevio\Package\Wallet\Services\ExchangeService;
// use Incevio\Package\Wallet\Services\LockService;
// use Incevio\Package\Wallet\Services\WalletService;
// use Incevio\Package\Wallet\Simple\Math;
// use Incevio\Package\Wallet\Simple\Rate;
// use Incevio\Package\Wallet\Simple\Store;
use function config;
use function dirname;
use function function_exists;
use App\Common\PackageConfig;
use Illuminate\Support\ServiceProvider;
use Incevio\Package\Subscription\Commands\ChargeSubscriptionFee;

class SubscriptionServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * This module depenets on other modules
     *
     * @var array
     */
    public $dependentModules = [
        'wallet',
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot(): void
    {
        // Routes
        $this->loadTranslationsFrom(
            dirname(__DIR__).'/resources/lang',
            'subscription'
        );

        // Return if trying to access directly
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChargeSubscriptionFee::class,
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/config.php',
            'subscription'
        );

        // Autoload helpers
        foreach(glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/all.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'subscription');
    }
}
