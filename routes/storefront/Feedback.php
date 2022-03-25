<?php

use App\Http\Controllers\Storefront\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('order/feedback/{order}', [
  FeedbackController::class, 'feedback_form'
])->name('order.feedback');

Route::post('order/feedback/{order}', [
  FeedbackController::class, 'save_product_feedbacks'
])->name('save.feedback');

Route::post('shop/feedback/{order}', [
  FeedbackController::class, 'save_shop_feedbacks'
])->name('shop.feedback');
