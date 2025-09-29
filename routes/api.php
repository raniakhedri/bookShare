<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketBookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Marketplace API Routes
Route::middleware('auth:sanctum')->group(function () {

    // Market Books Routes
    Route::apiResource('marketbooks', MarketBookController::class);
    Route::get('marketbooks/my/books', [MarketBookController::class, 'myBooks']);
    Route::patch('marketbooks/{marketBook}/toggle-availability', [MarketBookController::class, 'toggleAvailability']);

    // Transactions Routes
    Route::apiResource('transactions', TransactionController::class)->except(['destroy']);
    Route::get('transactions/my/requests', [TransactionController::class, 'myRequests']);
    Route::get('transactions/my/received', [TransactionController::class, 'requestsForMyBooks']);
    Route::patch('transactions/{transaction}/complete', [TransactionController::class, 'markCompleted']);

    // Exchange Requests Routes
    Route::apiResource('exchange-requests', ExchangeRequestController::class)->except(['store']);
    Route::get('exchange-requests/my/offered', [ExchangeRequestController::class, 'myOfferedBooks']);
    Route::get('exchange-requests/my/requests', [ExchangeRequestController::class, 'myExchangeRequests']);

});

// Admin Routes (require admin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('system-health', [AdminController::class, 'systemHealth']);

    // Admin User Management
    Route::apiResource('users', AdminUserController::class);
    Route::get('users/statistics', [AdminUserController::class, 'statistics']);
    Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus']);

});
