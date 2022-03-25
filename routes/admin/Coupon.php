<?php

use App\Http\Controllers\Admin\CouponController;
use Illuminate\Support\Facades\Route;

// coupons
Route::post('coupon/massTrash', [CouponController::class, 'massTrash'])->name('coupon.massTrash')->middleware('demoCheck');

Route::post('coupon/massDestroy', [CouponController::class, 'massDestroy'])->name('coupon.massDestroy')->middleware('demoCheck');

Route::delete('coupon/emptyTrash', [CouponController::class, 'emptyTrash'])->name('coupon.emptyTrash');

Route::delete('coupon/{coupon}/trash', [CouponController::class, 'trash'])->name('coupon.trash'); // coupon move to trash

Route::get('coupon/{coupon}/restore', [CouponController::class, 'restore'])->name('coupon.restore');

Route::resource('coupon', CouponController::class);
