<?php

use App\Http\Controllers\Admin\CustomCssController;
use Illuminate\Support\Facades\Route;

Route::get('custom_css', [
  CustomCssController::class, 'edit'
])->name('custom_css');

Route::post('custom_css/update', [
  CustomCssController::class, 'update'
])->name('custom_css.update');
