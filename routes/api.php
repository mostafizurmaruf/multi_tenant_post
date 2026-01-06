<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ProductController,
    ReportController,
    OrderController,
    TokenController
};

Route::get('token', [TokenController::class, 'token']);

Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
});


Route::middleware([
    'auth:sanctum',
    'tenant',
    'throttle:api'
])->group(function () {

    Route::get('/reports/daily', [ReportController::class, 'daily']);
    Route::get('/reports/top-products', [ReportController::class, 'topProducts']);
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock']);
});
