<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

// Route::get('address/ajax/getCountryStates', [AddressController::class, 'ajaxCountryStates'])->name('ajax.getCountryStates')->middleware('ajax');

Route::get('address/addresses/{addressable_type}/{addressable_id}', [
  AddressController::class, 'addresses'
])->name('address.addresses');

Route::get('address/create/{addressable_type}/{addressable_id}', [
  AddressController::class, 'create'
])->name('address.create');

Route::resource('address', AddressController::class)
  ->except('index', 'create', 'show');
