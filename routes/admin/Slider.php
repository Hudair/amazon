<?php

use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Route;

// Route::delete('slider/{slider}/trash', [SliderController::class, 'trash'])->name('slider.trash'); // slider post move to trash
// Route::get('slider/{slider}/restore', [SliderController::class, 'restore'])->name('slider.restore');
// Route::post('slider/massTrash', [SliderController::class, 'massTrash'])->name('slider.massTrash')->middleware('demoCheck');
Route::post('slider/massDestroy', [SliderController::class, 'massDestroy'])->name('slider.massDestroy')->middleware('demoCheck');
// Route::delete('slider/emptyTrash', [SliderController::class, 'emptyTrash'])->name('slider.emptyTrash');
Route::resource('slider', SliderController::class)->except('show');
