<?php

use Illuminate\Support\Facades\Route;
use Incevio\Package\oneCheckout\Http\Controllers\OneCheckoutController;
use Incevio\Package\oneCheckout\Http\Controllers\Api\OneCheckoutController as ApiCheckoutController;

// Web Routes
Route::middleware(['web', 'storefront', 'checkout'])
    ->group(function () {
        Route::get('carts/checkout_all', [
            OneCheckoutController::class, 'index'
        ])->name(config('checkout.routes.checkout'));

        Route::post('carts/checkout_all', [
            OneCheckoutController::class, 'checkout'
        ])->name(config('checkout.routes.place_order'));
    });

// API Routes
Route::namespace('Api')->middleware(['api'])
    ->prefix('api')->group(function () {
        Route::post('cart/checkout_all', [
            ApiCheckoutController::class, 'checkout'
        ]);
    });
