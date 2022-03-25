<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loading all helper files/classes
        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }

        // if (!$this->app->runningInConsole()) {
        //     if (is_incevio_package_loaded('zipcode')) {
        //         $zipCode = session('zipcode') ?? get_from_option_table('zipcode_default');
        //         Session::put('zipcode', $zipCode);
        //     }
        // }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
