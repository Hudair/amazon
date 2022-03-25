<?php

use App\Http\Controllers\Admin\Report\VisitorController;
use Illuminate\Support\Facades\Route;

// Visitors
Route::delete('visitor/{visitor}/ban', [VisitorController::class, 'ban'])->name('visitor.ban');

Route::get('visitor/{visitor}/unban', [VisitorController::class, 'unban'])->name('visitor.unban');

Route::get('report/getVisitors', [VisitorController::class, 'getVisitors'])->name('report.visitors.getMore')->middleware('ajax');

Route::get('report/visitors', [VisitorController::class, 'index'])->name('report.visitors');
