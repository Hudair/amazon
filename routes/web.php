<?php

use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

// Common
include 'Common.php';

// Front End routes
include 'Frontend.php';

// Backoffice routes
include 'Backoffice.php';

// Webhooks
// Route::post('webhook/stripe', [WebhookController::class, 'handleStripeCallback']); 		// Stripe
Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook']);
// AJAX routes for get images
// Route::get('order/ajax/taxrate', [OrderController::class, 'ajaxTaxRate'])->name('ajax.taxrate');
