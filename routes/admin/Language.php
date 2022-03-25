<?php

use App\Http\Controllers\Admin\LanguageController;
use Illuminate\Support\Facades\Route;

// languages
Route::post('language/massTrash', [LanguageController::class, 'massTrash'])->name('language.massTrash')->middleware('demoCheck');

Route::post('language/massDestroy', [LanguageController::class, 'massDestroy'])->name('language.massDestroy')->middleware('demoCheck');

Route::delete('language/emptyTrash', [LanguageController::class, 'emptyTrash'])->name('language.emptyTrash');

Route::delete('language/{language}/trash', [LanguageController::class, 'trash'])->name('language.trash'); // language move to trash

Route::get('language/{language}/restore', [LanguageController::class, 'restore'])->name('language.restore');

Route::resource('language', LanguageController::class)->except('show');
