<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('changePasswordForm/{user}', [UserController::class, 'ShowChangePasswordForm'])->name('user.changePassword');

Route::put('updatePassword/{user}', [UserController::class, 'updatePassword'])->name('user.updatePassword');

Route::post('user/massTrash', [UserController::class, 'massTrash'])->name('user.massTrash')->middleware('demoCheck');

Route::post('user/massDestroy', [UserController::class, 'massDestroy'])->name('user.massDestroy')->middleware('demoCheck');

Route::delete('user/emptyTrash', [UserController::class, 'emptyTrash'])->name('user.emptyTrash');

Route::delete('user/{user}/trash', [UserController::class, 'trash'])->name('user.trash');

Route::get('user/{user}/restore', [UserController::class, 'restore'])->name('user.restore');

Route::resource('user', UserController::class);
