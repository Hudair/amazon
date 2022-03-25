<?php

use App\Http\Controllers\Admin\ShippingRateController;
use Illuminate\Support\Facades\Route;

Route::get('shippingRate/{shippingZone}/create/{basedOn}', [ShippingRateController::class, 'create'])->name('shippingRate.create');

Route::resource('shippingRate', ShippingRateController::class)->except('index', 'create', 'show');
