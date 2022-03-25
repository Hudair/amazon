<?php

use App\Http\Controllers\Admin\SupplierController;
use Illuminate\Support\Facades\Route;

// Suppliers
Route::post('supplier/massTrash', [SupplierController::class, 'massTrash'])->name('supplier.massTrash')->middleware('demoCheck');
Route::post('supplier/massDestroy', [SupplierController::class, 'massDestroy'])->name('supplier.massDestroy')->middleware('demoCheck');
Route::delete('supplier/emptyTrash', [SupplierController::class, 'emptyTrash'])->name('supplier.emptyTrash');
Route::delete('supplier/{supplier}/trash', [SupplierController::class, 'trash'])->name('supplier.trash'); // supplier move to trash
Route::get('supplier/{supplier}/restore', [SupplierController::class, 'restore'])->name('supplier.restore');
Route::resource('supplier', SupplierController::class);
