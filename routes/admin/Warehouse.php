<?php

use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;

// warehouses
Route::post('warehouse/massTrash', [WarehouseController::class, 'massTrash'])
  ->name('warehouse.massTrash')->middleware('demoCheck');

Route::post('warehouse/massDestroy', [WarehouseController::class, 'massDestroy'])
  ->name('warehouse.massDestroy')->middleware('demoCheck');

Route::delete('warehouse/emptyTrash', [WarehouseController::class, 'emptyTrash'])
  ->name('warehouse.emptyTrash');

Route::delete('warehouse/{warehouse}/trash', [WarehouseController::class, 'trash'])
  ->name('warehouse.trash'); // warehouse move to trash

Route::get('warehouse/{warehouse}/restore', [WarehouseController::class, 'restore'])
  ->name('warehouse.restore');

Route::resource('warehouse', WarehouseController::class);
