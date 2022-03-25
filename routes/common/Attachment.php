<?php

use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;

Route::name('attachment.')->group(function () {
  Route::get('attachment/{attachment}/download', [
    AttachmentController::class, 'download'
  ])->name('download');

  Route::delete('attachment/{attachment}', [
    AttachmentController::class, 'destroy'
  ])->name('delete');
});
