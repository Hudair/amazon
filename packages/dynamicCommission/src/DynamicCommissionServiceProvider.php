<?php

namespace Incevio\Package\DynamicCommission;

use App\Common\PackageConfig;
use Illuminate\Support\ServiceProvider;
// use Incevio\Package\Inspector\Helper\Helper;

class DynamicCommissionServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dynamicCommission');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'dynamicCommission');
        //$this->loadMigrationsFrom(__DIR__.'../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dynamicCommission');

        // Autoload helpers
        foreach(glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once($filename);
        }

        ##Share inspector Status For Whole Application View:
        // Register the main class to use with the facade
        $this->app->singleton('dynamicCommission', function () {
            return new DynamicCommission;
        });
    }
}
