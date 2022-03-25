<?php

use App\Http\Controllers\Installer;
use Illuminate\Support\Facades\Route;

Route::middleware('install')->namespace('Installer')->name('Installer.')->prefix('install')->group(function () {
    Route::get('/', [Installer\WelcomeController::class, 'welcome'])->name('welcome');

    Route::get('environment', [Installer\EnvironmentController::class, 'environmentMenu'])->name('environment');

    Route::get('environment/classic', [Installer\EnvironmentController::class, 'environmentClassic'])->name('environmentClassic');

    Route::post('environment/saveClassic', [Installer\EnvironmentController::class, 'saveClassic'])->name('environmentSaveClassic');

    Route::get('requirements', [Installer\RequirementsController::class, 'requirements'])->name('requirements');

    Route::get('permissions', [Installer\PermissionsController::class, 'permissions'])->name('permissions');

    Route::get('database', [Installer\DatabaseController::class, 'database'])->name('database');

    Route::get('activate', [Installer\ActivateController::class, 'activate'])->name('activate');

    Route::post('verify', [Installer\ActivateController::class, 'verify'])->name('verify');

    Route::get('final', [Installer\FinalController::class, 'final'])->name('final');

    Route::get('finish', [Installer\FinalController::class, 'finish'])->name('finish');

    Route::get('demo', [Installer\FinalController::class, 'seedDemo'])->name('seedDemo');
});
