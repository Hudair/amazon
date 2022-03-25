<?php

use App\Http\Controllers\Admin\AnnouncementController;
use Illuminate\Support\Facades\Route;

// Announcement
Route::get('announcement.read', [AnnouncementController::class, 'read'])
    ->name('announcement.read')->middleware('ajax');

Route::resource('announcement', AnnouncementController::class)->except('show');
