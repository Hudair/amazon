<?php

use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Route;

Route::post('blog/massTrash', [BlogController::class, 'massTrash'])
  ->name('blog.massTrash')->middleware('demoCheck');

Route::post('blog/massDestroy', [BlogController::class, 'massDestroy'])
  ->name('blog.massDestroy')->middleware('demoCheck');

Route::delete('blog/emptyTrash', [BlogController::class, 'emptyTrash'])
  ->name('blog.emptyTrash');

Route::delete('blog/{blog}/trash', [BlogController::class, 'trash'])
  ->name('blog.trash'); // Blog post move to trash

Route::get('blog/{blog}/restore', [BlogController::class, 'restore'])
  ->name('blog.restore');

Route::resource('blog', BlogController::class)->except('show');
