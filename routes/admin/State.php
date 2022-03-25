<?php

use App\Http\Controllers\Admin\StateController;
use Illuminate\Support\Facades\Route;

Route::get('state/create/{country}', [StateController::class, 'create'])->name('state.create');

Route::post('state/massDestroy', [StateController::class, 'massDestroy'])->name('state.massDestroy')->middleware('demoCheck');

Route::resource('state', StateController::class)->except('index', 'create');
