<?php

use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('shop/massTrash', [ShopController::class, 'massTrash'])->name('shop.massTrash')->middleware('demoCheck');

Route::post('shop/massDestroy', [ShopController::class, 'massDestroy'])->name('shop.massDestroy')->middleware('demoCheck');

Route::delete('shop/emptyTrash', [ShopController::class, 'emptyTrash'])->name('shop.emptyTrash');

Route::get('shop/verifications', [ShopController::class, 'verifications'])->name('shop.verifications');

Route::get('shop/{shop}/verify', [ShopController::class, 'showVerificationForm'])->name('shop.verify');

Route::get('subscription/{shop}/editTrial', [SubscriptionController::class, 'editTrial'])->name('subscription.editTrial');

Route::put('subscription/{shop}/updateTrial', [SubscriptionController::class, 'updateTrial'])->name('subscription.updateTrial');

Route::put('shop/{shop}/toggle', [ShopController::class, 'toggleStatus'])->name('shop.toggle')->middleware('ajax');

Route::get('shop/{shop}/staffs', [ShopController::class, 'staffs'])->name('shop.staffs');

Route::delete('shop/{shop}/trash', [ShopController::class, 'trash'])->name('shop.trash'); // shop move to trash

Route::get('shop/{shop}/restore', [ShopController::class, 'restore'])->name('shop.restore');

Route::resource('shop', ShopController::class)->except('create', 'store');
