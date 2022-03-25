<?php

use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;

// Route::delete('banner/{banner}/trash', [BannerController::class, 'trash'])->name('banner.trash');
// Route::get('banner/{banner}/restore', [BannerController::class, 'restore'])->name('banner.restore');
// Route::post('banner/massTrash', [BannerController::class, 'massTrash'])->name('banner.massTrash')->middleware('demoCheck');

Route::post('banner/massDestroy', [BannerController::class, 'massDestroy'])->name('banner.massDestroy')->middleware('demoCheck');

// Route::delete('banner/emptyTrash', [BannerController::class, 'emptyTrash'])->name('banner.emptyTrash');

Route::resource('banner', BannerController::class)->except('show');
