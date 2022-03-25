<?php

use App\Http\Controllers\Admin\ChatController;
use Illuminate\Support\Facades\Route;

// Support chats
Route::get('chat', [ChatController::class, 'index'])->name('chat_conversation.index');

Route::get('chat/{chat}', [ChatController::class, 'show'])->name('chat_conversation.show');

Route::post('chat/{chat}/reply', [ChatController::class, 'reply'])->name('chat_conversation.reply');

// Route::delete('chat/{chat}/trash', [ChatController::class, 'trash'])->name('chat_conversation.trash'); // Chat move to trash

// Route::post('chat/{chat}/restore', [ChatController::class, 'restore'])->name('chat_conversation.restore');

// Route::delete('chat/{chat}/destroy', [ChatController::class, 'destroy'])->name('chat_conversation.destroy');
