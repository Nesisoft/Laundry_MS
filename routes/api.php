<?php

use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    require base_path('routes/api/auth.php');
});

Route::prefix('user')->group(function () {
    require base_path('routes/api/user.php');
});
