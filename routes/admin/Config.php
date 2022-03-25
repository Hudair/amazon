<?php

use App\Http\Controllers\Admin\ConfigController;
use Illuminate\Support\Facades\Route;

// config
Route::put('config/maintenanceMode/{shop}/toggle', [ConfigController::class, 'toggleMaintenanceMode'])
    ->name('config.maintenanceMode.toggle')->middleware('ajax');

Route::put('config/notification/{node}/toggle', [ConfigController::class, 'toggleNotification'])
    ->name('config.notification.toggle')->middleware('ajax');

Route::put('config/updateBasicConfig/{shop}', [ConfigController::class, 'updateBasicConfig'])
    ->name('basic.config.update');

Route::put('config/updateConfig/{config}', [ConfigController::class, 'updateConfig'])
    ->name('config.update')->middleware('ajax');

Route::get('general', [ConfigController::class, 'viewGeneralSetting'])
    ->name('config.general');

Route::get('config', [ConfigController::class, 'view'])
    ->name('config.view');
