<?php

namespace Incevio\Package\Inspector;

use App\Common\PackageConfig;
use Illuminate\Support\ServiceProvider;
// use Incevio\Package\Inspector\Helper\Helper;

class InspectorServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'inspector');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'inspector');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'inspector');

        // Autoload helpers
        foreach(glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once($filename);
        }

        ##Share inspector Status For Whole Application View:
        // Register the main class to use with the facade
        $this->app->singleton('inspector', function () {
            return new Inspector;
        });
    }
}
