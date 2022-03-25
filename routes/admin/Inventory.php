<?php

use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InventoryUploadController;
use Illuminate\Support\Facades\Route;

// Bulk upload routes
Route::get('inventory/upload/downloadTemplate', [InventoryUploadController::class, 'downloadTemplate'])
  ->name('inventory.downloadTemplate');

Route::get('inventory/upload', [InventoryUploadController::class, 'showForm'])->name('inventory.bulk');

Route::post('inventory/upload', [InventoryUploadController::class, 'upload'])->name('inventory.upload');

Route::post('inventory/import', [InventoryUploadController::class, 'import'])->name('inventory.import');

Route::post('inventory/downloadFailedRows', [InventoryUploadController::class, 'downloadFailedRows'])
  ->name('inventory.downloadFailedRows');

// Mass Actions
Route::post('inventory/massTrash', [InventoryController::class, 'massTrash'])
  ->name('inventory.massTrash')->middleware('demoCheck');

Route::post('inventory/massDestroy', [InventoryController::class, 'massDestroy'])
  ->name('inventory.massDestroy')->middleware('demoCheck');

Route::delete('inventory/emptyTrash', [InventoryController::class, 'emptyTrash'])->name('inventory.emptyTrash');

// inventories
Route::delete('inventory/{inventory}/trash', [InventoryController::class, 'trash'])->name('inventory.trash'); // inventory move to trash

Route::get('inventory/{inventory}/restore', [InventoryController::class, 'restore'])->name('inventory.restore');

Route::get('inventory/setVariant/{product}', [InventoryController::class, 'setVariant'])
  ->name('inventory.setVariant');

Route::get('inventory/add/{product}', [InventoryController::class, 'add'])->name('inventory.add');

Route::get('inventory/addWithVariant/{product}', [InventoryController::class, 'addWithVariant'])
  ->name('inventory.addWithVariant');

Route::post('inventory/storeWithVariant', [InventoryController::class, 'storeWithVariant'])
  ->name('inventory.storeWithVariant');

Route::post('inventory/store', [InventoryController::class, 'store'])->name('inventory.store')->middleware('ajax');

Route::post('inventory/{inventory}/update', [InventoryController::class, 'update'])
  ->name('inventory.update')->middleware('ajax');

Route::get('inventory/{inventory}/editQtt', [InventoryController::class, 'editQtt'])
  ->name('inventory.editQtt');

Route::put('inventory/{inventory}/updateQtt', [InventoryController::class, 'updateQtt'])
  ->name('inventory.updateQtt');

Route::get('inventory/{status}/getInventory', [InventoryController::class, 'getInventory'])
  ->name('inventory.getMore')->middleware('ajax');

Route::resource('inventory', InventoryController::class)->except('create', 'store', 'update');
