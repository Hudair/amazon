<?php

use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('subscription/invoice/{invoiceId}', [SubscriptionController::class, 'invoice'])->name('subscription.invoice');

Route::put('card/update', [SubscriptionController::class, 'updateCardinfo'])->name('card.update');

Route::get('features/{subscriptionPlan}', [SubscriptionController::class, 'features'])->name('subscription.features');

Route::get('subscribe/{plan}/{merchant?}', [SubscriptionController::class, 'subscribe'])->name('subscribe');

Route::get('subscription/resume', [SubscriptionController::class, 'resumeSubscription'])->name('subscription.resume');

Route::delete('subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');
