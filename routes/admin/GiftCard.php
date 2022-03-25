<?php

use App\Http\Controllers\Admin\GiftCardController;
use Illuminate\Support\Facades\Route;

// giftCards
Route::post('giftCard/massTrash', [GiftCardController::class, 'massTrash'])->name('giftCard.massTrash')->middleware('demoCheck');

Route::post('giftCard/massDestroy', [GiftCardController::class, 'massDestroy'])->name('giftCard.massDestroy')->middleware('demoCheck');

Route::delete('giftCard/emptyTrash', [GiftCardController::class, 'emptyTrash'])->name('giftCard.emptyTrash');

Route::delete('giftCard/{giftCard}/trash', [GiftCardController::class, 'trash'])->name('giftCard.trash'); // giftCard move to trash

Route::get('giftCard/{giftCard}/restore', [GiftCardController::class, 'restore'])->name('giftCard.restore');

Route::resource('giftCard', GiftCardController::class);
