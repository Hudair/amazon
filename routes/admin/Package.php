<?php

use App\Http\Controllers\Admin\PackagesController;
use Illuminate\Support\Facades\Route;

// Package
Route::get('packages', [PackagesController::class, 'index'])->name('packages');

Route::get('package/upload', [PackagesController::class, 'upload'])->name('package.upload');

Route::post('package/upload', [PackagesController::class, 'save'])->name('package.save');

Route::get('package/{package}/initiate', [PackagesController::class, 'initiate'])->name('package.initiate');

Route::post('package/{package}/install', [PackagesController::class, 'install'])->name('package.install');

Route::post('package/{package}/uninstall', [PackagesController::class, 'uninstall'])->name('package.uninstall');

Route::post('package/{package}/update', [PackagesController::class, 'updateConfig'])->name('package.config.update');

Route::put('package/{package}/switch', [PackagesController::class, 'activation'])->name('package.switch')->middleware('ajax');

Route::put('package/toggle/{option}', [PackagesController::class, 'toggleConfig'])->name('package.config.toggle')->middleware('ajax');
