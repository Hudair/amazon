<?php

use App\Http\Controllers\Admin\DisputeController;
use Illuminate\Support\Facades\Route;

Route::get('dispute/{dispute}/response', [DisputeController::class, 'response'])->name('dispute.response');

Route::post('dispute/{dispute}/response', [DisputeController::class, 'storeResponse'])->name('dispute.storeResponse');

Route::resource('dispute', DisputeController::class)->only(['index', 'show']);
