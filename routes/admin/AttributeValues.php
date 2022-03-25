<?php

use App\Http\Controllers\Admin\AttributeValueController;
use Illuminate\Support\Facades\Route;

Route::get('attributeValue/create/{attribute?}', [AttributeValueController::class, 'create'])
  ->name('attributeValue.create');

Route::delete('attributeValue/{attributeValue}/trash', [AttributeValueController::class, 'trash'])
  ->name('attributeValue.trash');

Route::get('attributeValue/{attributeValue}/restore', [AttributeValueController::class, 'restore'])
  ->name('attributeValue.restore');

Route::post('attributeValue/massTrash', [AttributeValueController::class, 'massTrash'])
  ->name('attributeValue.massTrash')->middleware('demoCheck');

Route::post('attributeValue/massDestroy', [AttributeValueController::class, 'massDestroy'])
  ->name('attributeValue.massDestroy')->middleware('demoCheck');

Route::delete('attributeValue/emptyTrash', [AttributeValueController::class, 'emptyTrash'])
  ->name('attributeValue.emptyTrash');

Route::post('attributeValue/reorder', [AttributeValueController::class, 'reorder'])
  ->name('attributeValue.reorder');

Route::resource('attributeValue', AttributeValueController::class)->except('index', 'create');
