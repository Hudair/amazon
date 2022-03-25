<?php

use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\CommentsController;
use Illuminate\Support\Facades\Route;

Route::delete('ticket/{ticket}/archive', [TicketController::class, 'archive'])->name('ticket.archive'); // ticket move to trash

Route::get('ticket/{ticket}/reply', [TicketController::class, 'reply'])->name('ticket.reply');

Route::post('ticket/{ticket}/storeReply', [TicketController::class, 'storeReply'])->name('ticket.storeReply');

Route::post('ticket/{ticket}/reopen', [TicketController::class, 'reopen'])->name('ticket.reopen');

Route::get('ticket/{ticket}/showAssignForm', [TicketController::class, 'showAssignForm'])->name('ticket.showAssignForm');

Route::post('ticket/{ticket}/assign', [TicketController::class, 'assign'])->name('ticket.assign');

// Route::get('ticket/search/{text}', [TicketController::class, 'search'])->name('ticket.search');

// Route::post('ticket/{ticket}/comments', [CommentsController::class, 'store'])->name('comments.store');

Route::resource('ticket', TicketController::class)->only('index', 'show', 'edit', 'update');
