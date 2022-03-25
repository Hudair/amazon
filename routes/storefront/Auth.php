<?php

use App\Http\Controllers\Storefront\Auth;
use Illuminate\Support\Facades\Route;

// Auth route for customers
Route::name('customer.')->prefix('customer')->group(function () {
    Route::get('/login', [
        Auth\LoginController::class, 'showLoginForm'
    ])->name('login');

    Route::post('/login', [
        Auth\LoginController::class, 'login'
    ])->name('login.submit');

    Route::get('/logout', [
        Auth\LoginController::class, 'logout'
    ])->name('logout');

    // Register
    Route::get('/register', [
        Auth\RegisterController::class, 'showRegistrationForm'
    ])->name('register');

    Route::post('/register', [
        Auth\RegisterController::class, 'register'
    ])->name('register.submit');

    Route::get('/verify/{token?}', [
        Auth\RegisterController::class, 'verify'
    ])->name('verify');

    // Forgot Password
    Route::get('password/reset', [
        Auth\ForgotPasswordController::class, 'showLinkRequestForm'
    ])->name('password.request');

    Route::Post('password/email', [
        Auth\ForgotPasswordController::class, 'sendResetLinkEmail'
    ])->name('password.email');

    Route::get('password/reset/{token}', [
        Auth\ResetPasswordController::class, 'showResetForm'
    ])->name('password.reset');

    Route::Post('password/reset', [
        Auth\ResetPasswordController::class, 'reset'
    ]);
});

// Social login
Route::get('socialite/customer/{provider}', [
    Auth\LoginController::class, 'redirectToProvider'
])->name('socialite.customer');

Route::any('socialite/customer/{provider}/callback', [
    Auth\LoginController::class, 'handleProviderCallback'
])->name('socialite.customer.callback');
