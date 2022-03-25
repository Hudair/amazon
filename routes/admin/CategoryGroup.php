<?php

use App\Http\Controllers\Admin\CategoryGroupController;
use Illuminate\Support\Facades\Route;

Route::delete('categoryGroup/{categoryGrp}/trash', [CategoryGroupController::class, 'trash'])
  ->name('categoryGroup.trash');

Route::get('categoryGroup/{categoryGrp}/restore', [CategoryGroupController::class, 'restore'])
  ->name('categoryGroup.restore');

Route::post('categoryGroup/massTrash', [CategoryGroupController::class, 'massTrash'])
  ->name('categoryGroup.massTrash');

Route::post('categoryGroup/massDestroy', [CategoryGroupController::class, 'massDestroy'])
  ->name('categoryGroup.massDestroy');

Route::delete('categoryGroup/emptyTrash', [CategoryGroupController::class, 'emptyTrash'])
  ->name('categoryGroup.emptyTrash');

Route::resource('categoryGroup', CategoryGroupController::class)->except('show');
