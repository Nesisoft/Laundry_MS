<?php
// routes/web.php

use App\Http\Controllers\ConfigurationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocalConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/verify-pk', [ConfigurationController::class, 'verifyProductKey'])->name('verifyProductKey');
Route::get('/configure-business', [ConfigurationController::class, 'configureBusiness'])->name('configureBusiness');
Route::get('/configure-admin', [ConfigurationController::class, 'configureAdmin'])->name('configureAdmin');
Route::get('/login', [ConfigurationController::class, 'login'])->name('login');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/pos', [DashboardController::class, 'pos'])->name('pos');

// Customers
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
});

// Orders
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
});

// Items
Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

// Items
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

// Items
Route::prefix('employees')->name('employees.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

// Items
Route::prefix('pickups')->name('pickups.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

// Items
Route::prefix('deliveries')->name('deliveries.')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('index');
});

// Items
Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

// Items
Route::prefix('discounts')->name('discounts.')->group(function () {
    Route::get('/', [DiscountController::class, 'index'])->name('index');
});

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [LocalConfigController::class, 'index'])->name('index');
    Route::get('/local-config', [LocalConfigController::class, 'localConfig'])->name('local-config');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Setup wizard routes
// Route::get('/', function () {
//     if (config('app.setup_completed', false)) {
//         return redirect('/login');
//     }
//     return view('config/verify-key');
// });

// // Setup routes
// Route::prefix('setup')->group(function () {
//     Route::get('/verify-key', [LocalConfigController::class, 'showVerifyKey'])->name('setup.verify-key');
//     Route::post('/verify-key', [LocalConfigController::class, 'verifyKey'])->name('setup.verify-key.submit');

//     Route::get('/system-config', [LocalConfigController::class, 'showSystemConfig'])->name('setup.system-config');
//     Route::post('/system-config', [LocalConfigController::class, 'saveSystemConfig'])->name('setup.system-config.submit');

//     Route::get('/add-manager', [LocalConfigController::class, 'showAddManager'])->name('setup.add-manager');
//     Route::post('/add-manager', [LocalConfigController::class, 'saveManager'])->name('setup.add-manager.submit');

//     Route::get('/complete', [LocalConfigController::class, 'complete'])->name('setup.complete');
// });

// After setup is completed
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     // Add more routes for your application here
// });
