<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalConfigController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('config')->group(function () {
        Route::get('/', [LocalConfigController::class, 'fetchAll']); // Fetch all config values
        Route::get('/{key}', [LocalConfigController::class, 'fetchOne']); // Fetch a single config by key
        Route::post('/', [LocalConfigController::class, 'add']); // Add a new config entry
        Route::put('/reset', [LocalConfigController::class, 'resetDefaultConfigs']);
        Route::put('/logo', [LocalConfigController::class, 'setLogo']);
        Route::put('/update', [LocalConfigController::class, 'update']);
        Route::put('/', [LocalConfigController::class, 'setAllConfigValues']); // Keep this last
        Route::delete('/delete/{key}', [LocalConfigController::class, 'delete']);
        Route::get('/value/{key}', [LocalConfigController::class, 'getValue']); // Get the value of a specific config key
    });
});
