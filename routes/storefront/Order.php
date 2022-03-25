<?php

use App\Http\Controllers\Storefront\ConversationController;
use App\Http\Controllers\Storefront\DisputeController;
use App\Http\Controllers\Storefront\OrderCancelController;
use App\Http\Controllers\Storefront\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('order/{cart}', [
    OrderController::class, 'create'
])->name('order.create');

Route::get('paymentFailed/{order}', [
    OrderController::class, 'paymentFailed'
])->name('payment.failed');

Route::get('paymentSuccess/{gateway}/{order}', [
    OrderController::class, 'paymentGatewaySuccessResponse'
])->name('payment.success');

Route::middleware(['auth:customer'])->group(function () {
    Route::get('order/{order}', [
        OrderController::class, 'detail'
    ])->name('order.detail');

    Route::get('order/invoice/{order}', [
        OrderController::class, 'invoice'
    ])->name('order.invoice');

    Route::get('order/track/{order}', [
        OrderController::class, 'track'
    ])->name('order.track');

    Route::put('order/goodsReceived/{order}', [
        OrderController::class, 'goods_received'
    ])->name('goods.received');

    Route::get('order/again/{order}', [
        OrderController::class, 'again'
    ])->name('order.again');

    // Order cancel
    Route::get('order/cancel/{order}/{action?}', [
        OrderCancelController::class, 'showForm'
    ])->name('cancellation.form');

    Route::put('order/cancel/{order}', [
        OrderCancelController::class, 'cancel'
    ])->name('order.cancel');

    Route::post('order/cancel/{order}', [
        OrderCancelController::class, 'saveCancelRequest'
    ])->name('order.submitCancelRequest');

    // Conversations
    Route::post('order/conversation/{order}', [
        ConversationController::class, 'order_conversation'
    ])->name('order.conversation');

    // Disputes
    Route::get('order/dispute/{order}', [
        DisputeController::class, 'show_dispute_form'
    ])->name('dispute.open');

    Route::post('order/dispute/{order}', [
        DisputeController::class, 'open_dispute'
    ])->name('dispute.save');

    Route::post('dispute/{dispute}', [
        DisputeController::class, 'response'
    ])->name('dispute.response');

    Route::post('dispute/{dispute}/appeal', [
        DisputeController::class, 'appeal'
    ])->name('dispute.appeal');

    Route::post('dispute/{dispute}/markAsSolved', [
        DisputeController::class, 'markAsSolved'
    ])->name('dispute.markAsSolved');

    // Refunds
    // Route::post('order/refund/{order}', [DisputeController::class, 'refund_request'])->name('refund.request');
});
