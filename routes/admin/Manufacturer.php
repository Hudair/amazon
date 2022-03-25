<?php

use App\Http\Controllers\Admin\ManufacturerController;
use Illuminate\Support\Facades\Route;

Route::delete('manufacturer/{manufacturer}/trash', [ManufacturerController::class, 'trash'])->name('manufacturer.trash');

Route::post('manufacturer/massTrash', [ManufacturerController::class, 'massTrash'])->name('manufacturer.massTrash')->middleware('demoCheck');

Route::post('manufacturer/massDestroy', [ManufacturerController::class, 'massDestroy'])->name('manufacturer.massDestroy')->middleware('demoCheck');

Route::delete('manufacturer/emptyTrash', [ManufacturerController::class, 'emptyTrash'])->name('manufacturer.emptyTrash');

Route::get('manufacturer/{manufacturer}/restore', [ManufacturerController::class, 'restore'])->name('manufacturer.restore');

Route::resource('manufacturer', ManufacturerController::class);
