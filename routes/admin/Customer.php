<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerUploadController;
use Illuminate\Support\Facades\Route;

// Bulk upload routes
Route::get('customer/upload/downloadTemplate', [
  CustomerUploadController::class, 'downloadTemplate'
])->name('customer.downloadTemplate');

Route::get('customer/upload', [
  CustomerUploadController::class, 'showForm'
])->name('customer.bulk');

Route::post('customer/upload', [
  CustomerUploadController::class, 'upload'
])->name('customer.upload');

Route::post('customer/import', [
  CustomerUploadController::class, 'import'
])->name('customer.import');

Route::post('customer/downloadFailedRows', [
  CustomerUploadController::class, 'downloadFailedRows'
])->name('customer.downloadFailedRows');

// Customer Routes
Route::get('customer/{customer}/changePasswordForm', [
  CustomerController::class, 'ShowChangePasswordForm'
])->name('customer.changePassword');

Route::put('customer/{customer}/updatePassword', [
  CustomerController::class, 'updatePassword'
])->name('customer.updatePassword');

Route::post('customer/massTrash', [
  CustomerController::class, 'massTrash'
])->name('customer.massTrash');

Route::post('customer/massDestroy', [
  CustomerController::class, 'massDestroy'
])->name('customer.massDestroy');

Route::delete('customer/emptyTrash', [
  CustomerController::class, 'emptyTrash'
])->name('customer.emptyTrash');

Route::get('customer/{customer}/profile', [
  CustomerController::class, 'profile'
])->name('customer.profile');

Route::get('customer/{customer}/addresses', [
  CustomerController::class, 'addresses'
])->name('customer.addresses');

Route::delete('customer/{customer}/trash', [
  CustomerController::class, 'trash'
])->name('customer.trash');

Route::get('customer/{customer}/restore', [
  CustomerController::class, 'restore'
])->name('customer.restore');

Route::get('customer/getCustomers', [
  CustomerController::class, 'getCustomers'
])->name('customer.getMore')->middleware('ajax');

Route::resource('customer', CustomerController::class);
