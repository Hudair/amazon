<?php

use App\Http\Controllers\Admin\MessageController;
use Illuminate\Support\Facades\Route;

// Support messages
Route::get('message/labelOf/{label}', [MessageController::class, 'labelOf'])->name('message.labelOf');

Route::post('message/draftSend/{draft}', [MessageController::class, 'draftSend'])->name('message.draftSend');

Route::get('message/{message}/update/{statusOrLabel}/{type?}', [MessageController::class, 'update'])->name('message.update');

Route::post('message/massUpdate/{statusOrLabel}/{type?}', [MessageController::class, 'massUpdate'])->name('message.massUpdate');

Route::post('message/massDestroy', [MessageController::class, 'massDestroy'])->name('message.massDestroy');

Route::get('message/destroy/{message}', [MessageController::class, 'destroy'])->name('message.destroy');

Route::get('message/{message}/reply/{template?}', [MessageController::class, 'reply'])->name('message.reply');

Route::post('message/{message}/storeReply', [MessageController::class, 'storeReply'])->name('message.storeReply');

Route::get('message/create/{template?}', [MessageController::class, 'create'])->name('message.create');

Route::get('message/{order}/conversation', [MessageController::class, 'orderConversation'])->name('orderConversation.create');

Route::resource('message', MessageController::class)->except('index', 'create', 'update', 'destroy');
