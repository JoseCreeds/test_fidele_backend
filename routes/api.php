<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandleTokenController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrdersController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware(['cors'])->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/handletoken', [HandleTokenController::class, 'handleToken']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products/all', [ProductController::class, 'getProducts']);
});

Route::middleware(['cors', 'auth:sanctum', 'role:vendor'])->group(function () {
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/products', [ProductController::class, 'getByVendorId']);
    Route::get('/product/{id}', [ProductController::class, 'getProductById']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/vendor-orders', [OrdersController::class, 'vendorOrders']);
});

Route::middleware(['cors', 'auth:sanctum', 'role:client'])->group(function () {
    Route::post('/orders', [OrdersController::class, 'addToCart']);
    Route::get('/orders/history', [OrdersController::class, 'orderHistory']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
