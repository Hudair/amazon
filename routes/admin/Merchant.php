<?php

use App\Http\Controllers\Admin\MerchantController;
use Illuminate\Support\Facades\Route;

Route::get('changePasswordForm/{merchant}', [MerchantController::class, 'ShowChangePasswordForm'])->name('merchant.changePassword');

Route::put('updatePassword/{merchant}', [MerchantController::class, 'updatePassword'])->name('merchant.updatePassword');

Route::resource('merchant', MerchantController::class)->except('delete');
