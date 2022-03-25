<?php

use App\Http\Controllers\Admin\ShippingZoneController;
use Illuminate\Support\Facades\Route;

Route::delete('shippingZone/{shippingZone}/removeCountry/{country}', [ShippingZoneController::class, 'removeCountry'])->name('shippingZone.removeCountry');

Route::get('shippingZone/{shippingZone}/editStates/{country}', [ShippingZoneController::class, 'editStates'])->name('shippingZone.editStates');

Route::put('shippingZone/{shippingZone}/updateStates/{country}', [ShippingZoneController::class, 'updateStates'])->name('shippingZone.updateStates');

Route::resource('shippingZone', ShippingZoneController::class)->except('show');
