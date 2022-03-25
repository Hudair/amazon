<?php

use App\Http\Controllers\Admin\OrderCancellationController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('order/{order}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
// Route::post('order/massTrash', [OrderController::class, 'massTrash'])->name('order.massTrash')->middleware('demoCheck');

// Route::post('order/massDestroy', [OrderController::class, 'massDestroy'])->name('order.massDestroy')->middleware('demoCheck');

Route::delete('order/emptyTrash', [OrderController::class, 'emptyTrash'])->name('order.emptyTrash');

Route::get('order/{order}/adminNote', [OrderController::class, 'adminNote'])->name('order.adminNote');

Route::put('order/{order}/adminNote', [OrderController::class, 'saveAdminNote'])->name('order.saveAdminNote');

Route::delete('order/{order}/archive', [OrderController::class, 'archive'])->name('order.archive'); // order move to trash

Route::get('/{order}/details', [OrderController::class, 'show'])->name('details'); // order Details

//pickup orders
Route::get('pickup', [OrderController::class, 'index'])->name('pickup'); // pickup orders

// Cancellation routes
Route::get('order/{order}/cancel', [OrderCancellationController::class, 'create'])->name('cancellation.create');

Route::put('order/{order}/cancel', [OrderCancellationController::class, 'cancel'])->name('order.cancel');

Route::get('cancellation', [OrderCancellationController::class, 'index'])->name('order.cancellation');

Route::put('cancellation/{order}/{action}', [OrderCancellationController::class, 'handleCancellationRequest'])->name('cancellation.handle');

Route::get('order/{order}/restore', [OrderController::class, 'restore'])->name('order.restore');

Route::get('order/searchCutomer', [OrderController::class, 'searchCutomer'])->name('order.searchCutomer');

Route::get('order/{order}/fulfill', [OrderController::class, 'fulfillment'])->name('order.fulfillment');

Route::put('order/{order}/fulfill', [OrderController::class, 'fulfill'])->name('order.fulfill');

Route::put('order/{order}/updateOrderStatus', [OrderController::class, 'updateOrderStatus'])->name('order.updateOrderStatus');

Route::put('order/{order}/togglePaymentStatus', [OrderController::class, 'togglePaymentStatus'])->name('order.togglePaymentStatus');

Route::get('{order}/deliveryboys', [
  OrderController::class, 'deliveryBoys'
])->name('deliveryboys');

Route::post('{order}/deliveryboy/assign', [
  OrderController::class, 'assignDeliveryBoy'
])->name('deliveryboy.assign');

Route::resource('order', OrderController::class)->except('update');
