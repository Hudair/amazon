<?php

use Illuminate\Support\Facades\Route;
use Incevio\Package\Flashdeal\Http\Controllers\FlashdealController;

// Web Routes
Route::group([
	'middleware' => ['web','auth','admin'],
], function(){

    Route::get('admin/flashdeal', [FlashdealController::class, 'index'])->name('admin.flashdeal');
    Route::post('admin/flashdeal/create', [FlashdealController::class, 'create'])->name('admin.flashdeal.create');

});