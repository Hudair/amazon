<?php

use App\Http\Controllers\Admin\Incevio;
use Illuminate\Support\Facades\Route;

Route::prefix('incevio')->group(function () {
    // Check different type system information
    Route::get('check/{option?}', [Incevio::class, 'check'])->name('incevio.check');

    // New version upgrade
    Route::get('upgrade/{option?}', [Incevio::class, 'upgrade'])->name('incevio.upgrade');

    // Run Artisan command
    Route::get('command/{option?}', [Incevio::class, 'command'])->name('incevio.command');

    // Clear config. cache etc
    Route::get('clear/{all?}', [Incevio::class, 'clear'])->name('incevio.clear');

    // Clear scout. cache etc
    // Route::get('scout/{all?}', [Incevio::class, 'scout'])->name('incevio.scout');
});
