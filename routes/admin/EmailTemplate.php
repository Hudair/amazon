<?php

use App\Http\Controllers\Admin\EmailTemplateController;
use Illuminate\Support\Facades\Route;

Route::post('emailTemplate/massTrash', [EmailTemplateController::class, 'massTrash'])->name('emailTemplate.massTrash')->middleware('demoCheck');

Route::post('emailTemplate/massDestroy', [EmailTemplateController::class, 'massDestroy'])->name('emailTemplate.massDestroy')->middleware('demoCheck');

Route::delete('emailTemplate/emptyTrash', [EmailTemplateController::class, 'emptyTrash'])->name('emailTemplate.emptyTrash');

Route::delete('emailTemplate/{emailTemplate}/trash', [EmailTemplateController::class, 'trash'])->name('emailTemplate.trash');

Route::get('emailTemplate/{emailTemplate}/restore', [EmailTemplateController::class, 'restore'])->name('emailTemplate.restore');

Route::resource('emailTemplate', EmailTemplateController::class);
