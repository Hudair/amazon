<?php

use App\Http\Controllers\Admin\ConfigController;
use Illuminate\Support\Facades\Route;

// Verification
Route::get('verify', [ConfigController::class, 'verify'])->name('verify');

Route::post('verify', [ConfigController::class, 'saveVerificationData'])
  ->name('verify.submit');
