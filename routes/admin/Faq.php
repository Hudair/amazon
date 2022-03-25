<?php

use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FaqTopicController;
use Illuminate\Support\Facades\Route;

// Faqs
Route::resource('faqTopic', FaqTopicController::class)->except('index', 'show');

Route::resource('faq', FaqController::class);
