<?php

use Illuminate\Support\Facades\Route;
use Incevio\Package\Inspector\Http\Controllers\InspectorController;
use Incevio\Package\Inspector\Http\Controllers\SettingsController;

// Web Routes
Route::group([
	'middleware' => ['web','auth','admin'],
], function(){

    Route::get('admin/setting/inspector', [SettingsController::class, 'index'])->name(config('inspector.routes.settings'));

    Route::get('admin/inspector/inspectables', [InspectorController::class, 'inspectables'])->name('admin.inspector.inspectables');

    Route::post('admin/inspector/{id}/approve', [InspectorController::class, 'approveItem'])->name('admin.inspector.approve');

    Route::post('admin/inspector/{id}/deny', [InspectorController::class, 'blockItem'])->name('admin.inspector.deny');

});