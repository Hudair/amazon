<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('ajax')->name('search.')->group(function () {
    Route::get('search/customer', [
        SearchController::class, 'findCustomer'
    ])->name('customer');

    Route::get('search/product', [
        SearchController::class, 'findProduct'
    ])->name('product');

    Route::get('message/search', [
        SearchController::class, 'findMessage'
    ])->name('message');

    Route::get('search/merchant', [
        SearchController::class, 'findMerchant'
    ])->name('merchant');

    Route::get('search/findProduct', [
        SearchController::class, 'findProductForSelect'
    ])->name('findProduct');

    Route::get('search/findInventory', [
        SearchController::class, 'findInventoryForSelect'
    ])->name('findInventory');
});
