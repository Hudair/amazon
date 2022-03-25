<?php

use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('profile', [AccountController::class, 'profile'])->name('profile');

Route::get('billing', [AccountController::class, 'profile'])->name('billing');

Route::get('ticket', [AccountController::class, 'profile'])->name('ticket');

Route::put('update', [AccountController::class, 'update'])->name('update');

Route::get('changePasswordForm', [AccountController::class, 'ShowChangePasswordForm'])
  ->name('showChangePasswordForm');

Route::post('updatePassword', [AccountController::class, 'updatePassword'])->name('updatePassword');

Route::post('updatePhoto', [AccountController::class, 'updatePhoto'])->name('updatePhoto');

Route::get('deletePhoto', [AccountController::class, 'deletePhoto'])->name('deletePhoto');

Route::get('ticket/create', [AccountController::class, 'createTicket'])->name('ticket.create');

Route::post('ticket', [AccountController::class, 'storeTicket'])->name('ticket.store');

Route::get('ticket/{ticket}', [AccountController::class, 'showTicket'])->name('ticket.show');

Route::delete('ticket/{ticket}/archive', [AccountController::class, 'archiveTicket'])->name('ticket.archive'); // ticket move to trash

Route::get('ticket/{ticket}/reply', [AccountController::class, 'replyTicket'])->name('ticket.reply');

Route::post('ticket/{ticket}/storeReply', [AccountController::class, 'storeTicketReply'])->name('ticket.storeReply');
