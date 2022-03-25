<?php

namespace App\Common;

use Illuminate\Support\Facades\Artisan;
use Request;

trait PackageConfig
{
    /**
     * Register package seeds.
     *
     * @return void
     */
    // protected function registerSeedsFrom($path)
    // {
    //     foreach (glob($path."/*.php") as $filename)
    //     {
    //         include $filename;
    //         $classes = get_declared_classes();
    //         $class = end($classes);
    //         $command = Request::server('argv', null);

    //         if (is_array($command)) {
    //             $command = implode(' ', $command);
    //             if ($command == "artisan db:seed") {
    //                 Artisan::call('db:seed', ['--class' => $class, '--force' => true]);
    //             }
    //         }
    //     }
    // }

    /**
     * Configure Wallet to not register its migrations.
     *
     * @return static
     */
    // public function dependencyLoaded(): bool
    // {
    //     foreach ($this->dependentModules as $module) {
    //         if (! is_incevio_package_loaded($module)) {
    //             return false;
    //         }
    //     }

    //     return true;
    // }
}
