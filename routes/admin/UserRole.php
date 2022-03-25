<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('role/massTrash', [RoleController::class, 'massTrash'])->name('role.massTrash')->middleware('demoCheck');

Route::post('role/massDestroy', [RoleController::class, 'massDestroy'])->name('role.massDestroy')->middleware('demoCheck');

Route::delete('role/emptyTrash', [RoleController::class, 'emptyTrash'])->name('role.emptyTrash');

Route::delete('role/{role}/trash', [RoleController::class, 'trash'])->name('role.trash'); // role move to trash

Route::get('role/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');

Route::resource('role', RoleController::class);
