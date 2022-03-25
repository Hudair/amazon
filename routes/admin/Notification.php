<?php

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');

Route::get('notifications.markAsRead', [NotificationController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllAsRead')->middleware('ajax');

Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.delete');

Route::delete('notifications', [NotificationController::class, 'destroyAll'])->name('notifications.deleteAll');
