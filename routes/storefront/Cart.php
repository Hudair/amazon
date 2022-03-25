<?php

use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('addToCart/{slug}', [
  CartController::class, 'addToCart'
])->name('cart.addItem')->middleware(['ajax']);

Route::post('coupon/validate', [
  CartController::class, 'validateCoupon'
])->name('validate.coupon')->middleware(['auth:customer', 'ajax']);

Route::post('cart/removeItem', [
  CartController::class, 'remove'
])->name('cart.removeItem')->middleware(['ajax']);

Route::get('cart/{expressId?}', [
  CartController::class, 'index'
])->name('cart.index');

Route::put('cart/{cart}', [
  CartController::class, 'update'
])->name('cart.update')->middleware(['ajax']);

Route::any('cart/{cart}/checkout', [
  CheckoutController::class, 'checkout'
])->name('cart.checkout')->middleware('checkout');

Route::get('checkout/{slug}', [
  CheckoutController::class, 'directCheckout'
])->name('direct.checkout')->middleware('checkout');
