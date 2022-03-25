<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DeliveryBoyController;

Route::name('deliveryboy.')->group(function () {
    Route::post('deliveryboy/massTrash', [
        DeliveryBoyController::class, 'massTrash'
    ])->name('massTrash')->middleware('demoCheck');

    Route::post('deliveryboy/massDestroy', [
        DeliveryBoyController::class, 'massDestroy'
    ])->name('massDestroy')->middleware('demoCheck');

    Route::delete('deliveryboy/emptyTrash', [
        DeliveryBoyController::class, 'emptyTrash'
    ])->name('emptyTrash');

    Route::delete('deliveryboy/{deliveryboy}/trash', [
        DeliveryBoyController::class, 'trash'
    ])->name('trash');

    Route::get('deliveryboy/{deliveryboy}/restore', [
        DeliveryBoyController::class, 'restore'
    ])->name('restore');
});

Route::resource('deliveryboy', DeliveryBoyController::class);
