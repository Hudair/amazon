<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::name('image.')->group(function () {
  Route::get('download/{image}', [
    ImageController::class, 'download'
  ])->name('download');

  Route::post('delete/{image}', [
    ImageController::class, 'delete'
  ])->name('delete');

  Route::post('upload', [
    ImageController::class, 'upload'
  ])->name('upload');

  Route::post('image/sort', [
    ImageController::class, 'sort'
  ])->name('sort');
});
