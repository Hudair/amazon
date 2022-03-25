<?php

namespace Incevio\Package\Flashdeal;

use App\Common\PackageConfig;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FlashdealServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'flashdeal');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'flashdeal');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'flashdeal');

        // Autoload helpers
        foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
            require_once($filename);
        }

        // Register the main class to use with the facade
        $this->app->singleton('flashdeal', function () {
            return new Flashdeal;
        });
    }
}
