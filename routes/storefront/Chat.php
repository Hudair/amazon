<?php

use App\Http\Controllers\Storefront\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware('ajax')->name('chat.')->group(function () {
  Route::get('chat/{shop}', [
    ChatController::class, 'conversation'
  ])->name('conversation');

  Route::post('chat', [
    ChatController::class, 'save'
  ])->name('start');
});
