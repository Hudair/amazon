<?php

use App\Http\Controllers\Admin\CarrierController;
use Illuminate\Support\Facades\Route;

// carriers
Route::post('carrier/massTrash', [CarrierController::class, 'massTrash'])
  ->name('carrier.massTrash')->middleware('demoCheck');

Route::post('carrier/massDestroy', [CarrierController::class, 'massDestroy'])
  ->name('carrier.massDestroy')->middleware('demoCheck');

Route::delete('carrier/emptyTrash', [CarrierController::class, 'emptyTrash'])
  ->name('carrier.emptyTrash');

Route::delete('carrier/{carrier}/trash', [CarrierController::class, 'trash'])
  ->name('carrier.trash'); // carrier move to trash

Route::get('carrier/{carrier}/restore', [CarrierController::class, 'restore'])
  ->name('carrier.restore');

Route::resource('carrier', CarrierController::class);
