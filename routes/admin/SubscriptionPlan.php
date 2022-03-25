<?php

use App\Http\Controllers\Admin\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;

// SubscriptionPlans
Route::middleware(['subscriptionEnabled'])->group(function () {
    Route::delete('subscriptionPlan/{subscriptionPlan}/trash', [SubscriptionPlanController::class, 'trash'])->name('subscriptionPlan.trash');

    Route::get('subscriptionPlan/{subscriptionPlan}/restore', [SubscriptionPlanController::class, 'restore'])->name('subscriptionPlan.restore');

    Route::post('subscriptionPlan/massTrash', [SubscriptionPlanController::class, 'massTrash'])->name('subscriptionPlan.massTrash')->middleware('demoCheck');

    Route::post('subscriptionPlan/massDestroy', [SubscriptionPlanController::class, 'massDestroy'])->name('subscriptionPlan.massDestroy')->middleware('demoCheck');

    Route::delete('subscriptionPlan/emptyTrash', [SubscriptionPlanController::class, 'emptyTrash'])->name('subscriptionPlan.emptyTrash');

    Route::post('subscriptionPlan/reorder', [SubscriptionPlanController::class, 'reorder'])->name('subscriptionPlan.reorder');

    Route::resource('subscriptionPlan', SubscriptionPlanController::class);
});
