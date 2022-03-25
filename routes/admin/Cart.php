<?php

use App\Http\Controllers\Admin\CartController;
use Illuminate\Support\Facades\Route;

Route::post('cart/massTrash', [CartController::class, 'massTrash'])->name('cart.massTrash')->middleware('demoCheck');

Route::post('cart/massDestroy', [CartController::class, 'massDestroy'])->name('cart.massDestroy')->middleware('demoCheck');

Route::delete('cart/emptyTrash', [CartController::class, 'emptyTrash'])->name('cart.emptyTrash');

Route::delete('cart/{cart}/trash', [CartController::class, 'trash'])->name('cart.trash'); // cart move to trash

Route::get('cart/{cart}/restore', [CartController::class, 'restore'])->name('cart.restore');

Route::resource('cart', CartController::class)->except('create', 'edit');
