<?php

use App\Http\Controllers\Admin\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('country/{country}/state', [CountryController::class, 'states'])->name('country.states');

// Route::post('country/massDestroy', [CountryController::class, 'massDestroy'])->name('country.massDestroy')->middleware('demoCheck');

Route::resource('country', CountryController::class, ['destroy']);
