<?php

use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\CustomerDiscountApiController;
use App\Http\Controllers\Api\DeliveryRequestApiController;
use App\Http\Controllers\Api\DeliveryRequestPaymentController;
use App\Http\Controllers\Api\DiscountApiController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\InvoicePaymentApiController;
use App\Http\Controllers\Api\ItemApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocalConfigApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\OrderItemApiController;
use App\Http\Controllers\Api\PickupRequestApiController;
use App\Http\Controllers\Api\PickupRequestPaymentController;
use App\Http\Controllers\Api\UserApiController;

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardApiController::class, 'getStats']);
    });

    Route::prefix('config')->group(function () {
        Route::get('/', [LocalConfigApiController::class, 'fetchAll']); // Fetch all config values
        Route::post('/', [LocalConfigApiController::class, 'add']); // Add a new config entry
        Route::put('/', [LocalConfigApiController::class, 'setAllConfigValues']); // Keep this last
        Route::put('/reset', [LocalConfigApiController::class, 'resetDefaultConfigs']);
        Route::put('/logo', [LocalConfigApiController::class, 'setLogo']);
        Route::put('/update', [LocalConfigApiController::class, 'update']);
        Route::get('/{key}', [LocalConfigApiController::class, 'fetchOne']); // Fetch a single config by key
        Route::delete('/{key}', [LocalConfigApiController::class, 'delete']);
        Route::get('/value/{key}', [LocalConfigApiController::class, 'getValue']); // Get the value of a specific config key
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);               // Get list of users (with filters)
        Route::post('/', [UserApiController::class, 'store']);              // Create new user
        Route::get('/{id}', [UserApiController::class, 'show']);            // Get a single user by ID
        Route::put('/{id}', [UserApiController::class, 'update']);          // Update user by ID
        Route::delete('/{id}', [UserApiController::class, 'destroy']);      // Permanently delete a user
        Route::put('/archive/{id}', [UserApiController::class, 'archive']); // Archive a user
        Route::put('/restore/{id}', [UserApiController::class, 'restore']); // Restore a user
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeApiController::class, 'fetchAll']); // Fetch all employee values
        Route::post('/', [EmployeeApiController::class, 'add']); // Add a new employee entry
        Route::put('/', [EmployeeApiController::class, 'update']);
        Route::get('/archived', [EmployeeApiController::class, 'fetchArchived']);
        Route::get('/{employee_id}', [EmployeeApiController::class, 'fetchOne']); // Fetch a single employee by key
        Route::delete('/{employee_id}', [EmployeeApiController::class, 'delete']);
        Route::post('/address/{employee_id}', [EmployeeApiController::class, 'addAddress']);
        Route::put('/archive/{employee_id}', [EmployeeApiController::class, 'archive']);
        Route::put('/address/{employee_id}', [EmployeeApiController::class, 'updateAddress']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerApiController::class, 'index']); // Fetch all employee values
        Route::post('/', [CustomerApiController::class, 'store']); // Add a new employee entry
        Route::put('/', [CustomerApiController::class, 'update']);
        Route::get('/{employee_id}', [CustomerApiController::class, 'show']); // Fetch a single employee by key
        Route::delete('/{employee_id}', [CustomerApiController::class, 'destroy']); // Fetch a single employee by key
        Route::patch('/{employee_id}', [CustomerApiController::class, 'restore']);
        Route::post('/address/{employee_id}', [CustomerApiController::class, 'addAddress']);
        Route::patch('/archive/{employee_id}', [CustomerApiController::class, 'archive']);
        Route::patch('/address/{employee_id}', [CustomerApiController::class, 'updateAddress']);
    });

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemApiController::class, 'index']);         // Get all items
        Route::post('/', [ItemApiController::class, 'store']);         // Create
        Route::delete('/{id}', [ItemApiController::class, 'destroy']); // Delete
        Route::get('/{id}', [ItemApiController::class, 'show']);       // Get one
        Route::put('/{id}', [ItemApiController::class, 'update']);     // Update
        Route::get('/archived', [ItemApiController::class, 'fetchArchived']); // Archived items
        Route::put('/archive/{id}', [ItemApiController::class, 'archive']); // Archive
        Route::put('/restore/{id}', [ItemApiController::class, 'restore']); // Restore archived
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountApiController::class, 'index']);
        Route::post('/', [DiscountApiController::class, 'store']);
        Route::get('/{id}', [DiscountApiController::class, 'show']);
        Route::put('/{id}', [DiscountApiController::class, 'update']);
        Route::delete('/{id}', [DiscountApiController::class, 'destroy']);
        Route::put('/archive/{id}', [DiscountApiController::class, 'archive']);
        Route::put('/restore/{id}', [DiscountApiController::class, 'restore']);

        Route::get('/customer', [CustomerDiscountApiController::class, 'index']);
        Route::post('/customer', [CustomerDiscountApiController::class, 'store']);
        Route::get('/customer/{id}', [CustomerDiscountApiController::class, 'show']);
        Route::put('/customer/{id}', [CustomerDiscountApiController::class, 'update']);
        Route::delete('/customer/{id}', [CustomerDiscountApiController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderApiController::class, 'index']);         // List all
        Route::post('/', [OrderApiController::class, 'store']);        // Create
        Route::get('/{id}', [OrderApiController::class, 'show']);      // Show one
        Route::put('/{id}', [OrderApiController::class, 'update']);    // Update
        Route::put('/{id}/archive', [OrderApiController::class, 'archive']); // Archive
        Route::put('/{id}/restore', [OrderApiController::class, 'restore']); // Restore
        Route::delete('/{id}', [OrderApiController::class, 'destroy']); // Delete

        Route::get('/items', [OrderItemApiController::class, 'index']);
        Route::post('/items', [OrderItemApiController::class, 'store']);
        Route::delete('/items/{id}', [OrderItemApiController::class, 'destroy']);

        Route::get('/recent', [OrderApiController::class, 'getRecentOrders']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceApiController::class, 'index']);
        Route::post('/', [InvoiceApiController::class, 'store']);
        Route::get('/{id}', [InvoiceApiController::class, 'show']);
        Route::post('/{invoice}/send-sms', [InvoiceApiController::class, 'sendInvoiceSMS']);

        Route::get('/payments', [InvoicePaymentApiController::class, 'index']);
        Route::post('/payments', [InvoicePaymentApiController::class, 'store']);
    });

    // Pickup Requests
    Route::prefix('pickup-requests')->group(function () {
        Route::controller(PickupRequestApiController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/payments', 'getAllPayments');

            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

            Route::patch('/{id}/status', 'updateStatus');
            Route::patch('/{id}/archive', 'archive');
            Route::patch('/{id}/restore', 'restore');
            Route::post('/{id}/drivers', 'assignDriver');
            Route::patch('/{id}/drivers/{assignmentId}', 'updateDriverAssignmentStatus');
            Route::post('/{id}/payments', 'recordPayment');
            Route::get('/{id}/payments', 'getPayments');
        });
    });

    // Delivery Requests
    Route::prefix('delivery-requests')->group(function () {
        Route::controller(DeliveryRequestApiController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/payments', 'getAllPayments');

            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

            Route::patch('/{id}/status', 'updateStatus');
            Route::patch('/{id}/archive', 'archive');
            Route::patch('/{id}/restore', 'restore');
            Route::post('/{id}/drivers', 'assignDriver');
            Route::patch('/{id}/drivers/{assignmentId}', 'updateDriverAssignmentStatus');
            Route::post('/{id}/payments', 'recordPayment');
            Route::get('/{id}/payments', 'getPayments');
        });
    });
});
