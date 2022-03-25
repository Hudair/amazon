<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

// pages
Route::post('page/massTrash', [PageController::class, 'massTrash'])->name('page.massTrash')->middleware('demoCheck');

Route::post('page/massDestroy', [PageController::class, 'massDestroy'])->name('page.massDestroy')->middleware('demoCheck');

Route::delete('page/emptyTrash', [PageController::class, 'emptyTrash'])->name('page.emptyTrash');

Route::delete('page/{page}/trash', [PageController::class, 'trash'])->name('page.trash'); // page move to trash

Route::get('page/{page}/restore', [PageController::class, 'restore'])->name('page.restore');

Route::resource('page', PageController::class)->except('show');
