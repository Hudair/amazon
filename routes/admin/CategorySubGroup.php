<?php

use App\Http\Controllers\Admin\CategorySubGroupController;
use Illuminate\Support\Facades\Route;

Route::delete('categorySubGroup/{categorySubGroup}/trash', [CategorySubGroupController::class, 'trash'])
  ->name('categorySubGroup.trash');

Route::post('categorySubGroup/massTrash', [CategorySubGroupController::class, 'massTrash'])
  ->name('categorySubGroup.massTrash');

Route::post('categorySubGroup/massDestroy', [CategorySubGroupController::class, 'massDestroy'])
  ->name('categorySubGroup.massDestroy');

Route::delete('categorySubGroup/emptyTrash', [CategorySubGroupController::class, 'emptyTrash'])
  ->name('categorySubGroup.emptyTrash');

Route::get('categorySubGroup/{categorySubGroup}/restore', [CategorySubGroupController::class, 'restore'])
  ->name('categorySubGroup.restore');

Route::resource('categorySubGroup', CategorySubGroupController::class)->except('show');
