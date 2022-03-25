<?php

use App\Http\Controllers\Admin\SystemConfigController;
use Illuminate\Support\Facades\Route;

// system Configs
Route::put('system/config/{node}/toggle', [SystemConfigController::class, 'toggleConfig'])
  ->name('system.config.toggle')->middleware('ajax');

Route::put('system/paymentMethod/{id}/toggle', [SystemConfigController::class, 'togglePaymentMethod'])
  ->name('system.paymentMethod.toggle')->middleware('ajax');

Route::put('system/updateConfig', [SystemConfigController::class, 'update'])
  ->name('system.update')->middleware('ajax');

Route::get('system/config', [SystemConfigController::class, 'view'])->name('system.config');
