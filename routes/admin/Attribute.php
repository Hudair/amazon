<?php

use App\Http\Controllers\Admin\AttributeController;
use Illuminate\Support\Facades\Route;

Route::get('attribute/{attribute}/entities', [AttributeController::class, 'entities'])
  ->name('attribute.entities');

Route::delete('attribute/{attribute}/trash', [AttributeController::class, 'trash'])
  ->name('attribute.trash');

Route::get('attribute/{attribute}/restore', [AttributeController::class, 'restore'])
  ->name('attribute.restore');

Route::post('attribute/massTrash', [AttributeController::class, 'massTrash'])
  ->name('attribute.massTrash')->middleware('demoCheck');

Route::post('attribute/massDestroy', [AttributeController::class, 'massDestroy'])
  ->name('attribute.massDestroy')->middleware('demoCheck');

Route::delete('attribute/emptyTrash', [AttributeController::class, 'emptyTrash'])
  ->name('attribute.emptyTrash');

Route::post('attribute/reorder', [AttributeController::class, 'reorder'])
  ->name('attribute.reorder');

Route::resource('attribute', AttributeController::class)->except('show');
