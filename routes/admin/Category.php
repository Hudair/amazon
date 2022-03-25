<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::delete('category/{category}/trash', [CategoryController::class, 'trash'])->name('category.trash'); // category post move to trash

Route::post('category/massTrash', [CategoryController::class, 'massTrash'])->name('category.massTrash');

Route::post('category/massDestroy', [CategoryController::class, 'massDestroy'])->name('category.massDestroy');

Route::delete('category/emptyTrash', [CategoryController::class, 'emptyTrash'])->name('category.emptyTrash');

Route::get('category/{category}/restore', [CategoryController::class, 'restore'])->name('category.restore');

Route::get('category/getMoreCategories', [CategoryController::class, 'getCategories'])
  ->name('category.getMore')->middleware('ajax');

Route::resource('category', CategoryController::class)->except('show');
