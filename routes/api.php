<?php

use Illuminate\Support\Facades\Route;


Route::prefix('local')->group(function () {
    Route::prefix('user')->group(function () {
        require base_path('routes/api/local/user.php');
    });
});

Route::prefix('live')->group(function () {
    Route::prefix('user')->group(function () {
        require base_path('routes/api/live/user.php');
    });
});
