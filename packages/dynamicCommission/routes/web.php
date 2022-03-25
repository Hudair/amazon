<?php

use Illuminate\Support\Facades\Route;
use Incevio\Package\DynamicCommission\Http\Controllers\DynamicCommissionController;

// Web Routes
Route::group([
	'middleware' => ['web','auth','admin'],
], function() {
    Route::get('admin/setting/dynamicCommission', [DynamicCommissionController::class, 'index'])->name(config('dynamicCommission.routes.settings'));
    Route::post('admin/setting/dynamicCommission/update', [DynamicCommissionController::class, 'updateCommission'])->name(config('dynamicCommission.routes.update'));
});