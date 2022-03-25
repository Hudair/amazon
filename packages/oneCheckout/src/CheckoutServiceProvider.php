<?php

namespace Incevio\Package\Checkout;

use function config;
use function dirname;
use function function_exists;
use App\Common\PackageConfig;
use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * This module depenets on other modules
     *
     * @var array
     */
    public $dependentModules = [
        'OneCheckout',
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
        $this->loadRoutesFrom(__DIR__ . '/../routes/all.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'checkout');

        // Return if trying to access directly
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Commands
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //     ]);
        // }

        // Config
        if (function_exists('config_path')) {
            $this->publishes([
                dirname(__DIR__) . '/config/config.php' => config_path('checkout.php'),
            ], 'checkout');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'checkout');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'checkout');

        // Autoload helpers
        // foreach(glob(__DIR__.'/Helpers/*.php') as $filename){
        //     require_once($filename);
        // }
    }
}
