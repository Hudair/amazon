<?php

use App\Http\Controllers\Admin\TaxController;
use Illuminate\Support\Facades\Route;

// taxes
Route::post('tax/massTrash', [TaxController::class, 'massTrash'])->name('tax.massTrash')->middleware('demoCheck');

Route::post('tax/massDestroy', [TaxController::class, 'massDestroy'])->name('tax.massDestroy')->middleware('demoCheck');

Route::delete('tax/emptyTrash', [TaxController::class, 'emptyTrash'])->name('tax.emptyTrash');

Route::delete('tax/{tax}/trash', [TaxController::class, 'trash'])->name('tax.trash'); // tax move to trash

Route::get('tax/{tax}/restore', [TaxController::class, 'restore'])->name('tax.restore');

Route::resource('tax', TaxController::class)->except('show');
