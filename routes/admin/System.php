<?php

use App\Http\Controllers\Admin\ApplicationKeyController;
use App\Http\Controllers\Admin\SystemController;
use Illuminate\Support\Facades\Route;

// system
Route::put('system/maintenanceMode/toggle', [
  SystemController::class, 'toggleMaintenanceMode'
])->name('system.maintenanceMode.toggle')->middleware('ajax');

Route::get('system/general', [
  SystemController::class, 'view'
])->name('system.general');

Route::put('system/updateBasicSystem', [
  SystemController::class, 'update'
])->name('basic.system.update');

Route::get('system/modifyEnvironment', [
  SystemController::class, 'modifyEnvFile'
])->name('system.modifyEnvFile')->middleware('ajax');

Route::post('system/modifyEnvironment', [
  SystemController::class, 'saveEnvFile'
])->name('system.saveEnvFile');

Route::get('system/importDemoContents', [
  SystemController::class, 'importDemoContents'
])->name('system.importDemoContents')->middleware('ajax');

Route::get('system/backup', [
  SystemController::class, 'backup'
])->name('system.backup');

Route::post('system/importDemoContents', [
  SystemController::class, 'resetDatabase'
])->name('system.reset');

Route::get('system/uninstallAppLicense', [
  SystemController::class, 'uninstallAppLicense'
])->name('license.uninstall')->middleware('ajax');

Route::post('system/uninstallAppLicense', [
  SystemController::class, 'uninstallAppLicense'
])->name('license.reset');

Route::get('system/updateAppLicense', [
  SystemController::class, 'updateAppLicense'
])->name('license.update');

Route::get('generate-key', [
  ApplicationKeyController::class, 'confirm'
])->name('key.confirm');

Route::post('generate-key', [
  ApplicationKeyController::class, 'generate'
])->name('key.generate');
