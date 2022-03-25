<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

// Switch between the included languages
Route::get('locale/{locale?}', [
    LocaleController::class, 'change'
])->name('locale.change');

// Contact Us
Route::post('/contact_us', [
    ContactUsController::class, 'send'
])->name('contact_us');

// To view img no need to login
Route::get('image/{path}', [
    ImageController::class, 'show'
])->where('path', '.*')->name('image.show');

Route::middleware('ajax')->group(function () {
    // Use php helper functions from js
    Route::get('helper/getFromPHPHelper', [
        Admin\AjaxController::class, 'ajaxGetFromPHPHelper'
    ])->name('helper.getFromPHPHelper');

    Route::get('cart/ajax/getTaxRate', [
        Admin\ShippingZoneController::class, 'ajaxGetTaxRate'
    ])->name('ajax.getTaxRate');

    Route::get('address/ajax/getCountryStates', [
        AddressController::class, 'ajaxCountryStates'
    ])->name('ajax.getCountryStates');
});

Route::middleware('auth')->group(function () {
    include 'common/Image.php';
    include 'common/Attachment.php';
    include 'common/Address.php';
    include 'common/Search.php';
});
