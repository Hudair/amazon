<?php

use App\Http\Controllers\Admin\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::post('currency/massDestroy', [CurrencyController::class, 'massDestroy'])->name('currency.massDestroy')->middleware('demoCheck');

Route::resource('currency', CurrencyController::class)->except('show');
