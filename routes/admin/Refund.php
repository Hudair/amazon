<?php

use App\Http\Controllers\Admin\RefundController;
use Illuminate\Support\Facades\Route;

// refunds
Route::get('refund/{refund}/response', [RefundController::class, 'response'])->name('refund.response');

Route::get('refund/{refund}/approve', [RefundController::class, 'approve'])->name('refund.approve');

Route::get('refund/{refund}/decline', [RefundController::class, 'decline'])->name('refund.decline');

Route::get('refund/initiate/{order?}', [RefundController::class, 'showRefundForm'])->name('refund.form');

Route::post('refund/initiate', [RefundController::class, 'initiate'])->name('refund.initiate');

Route::get('refund', [RefundController::class, 'index'])->name('refund.index');
