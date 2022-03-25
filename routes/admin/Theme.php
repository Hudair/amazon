<?php

use App\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

// Theme
Route::get('/theme', [ThemeController::class, 'all'])->name('theme.index');

Route::put('/theme/activate/{theme}/{type?}', [ThemeController::class, 'activate'])
  ->name('theme.activate');
