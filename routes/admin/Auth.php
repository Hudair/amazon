<?php

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;

// Admin User Auth
Route::auth();

Route::get('/register/{plan?}', [Auth\RegisterController::class, 'showRegistrationForm'])
  ->name('vendor.register');

Route::get('/verify/{token?}', [Auth\RegisterController::class, 'verify'])->name('verify');

Route::get('/logout', [Auth\LoginController::class, 'logout']);
