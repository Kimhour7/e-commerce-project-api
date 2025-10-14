<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\OrdersController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Group Middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/update', [AuthController::class, 'update']);
    Route::post('/user/delete', [AuthController::class, 'deleteAccount']);
    Route::post('/user/logout', [AuthController::class, 'logout']);

    // Addresses
    Route::post('/addresses', [AddressesController::class, 'store']);
    Route::get('/addresses', [AddressesController::class, 'index']);
    Route::put('/addresses/{addresses}', [AddressesController::class, 'update']);
    Route::delete('/addresses/{addresses}', [AddressesController::class, 'destroy']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/categories/{category_name}', [CategoryController::class, 'getByCategoryName']);

    // Products
    Route::post('/products/{category}', [ProductsController::class, 'store']);
    Route::get('/products', [ProductsController::class, 'index']);
    Route::put('/products/{product}', [ProductsController::class, 'update']);
    Route::delete('/products/{product}', [ProductsController::class, 'destroy']);

    //Carts
    Route::post('/carts', [CartsController::class, 'store']);
    Route::get('/carts', [CartsController::class, 'index']);
    Route::put('/carts/{cart}', [CartsController::class, 'update']);
    Route::delete('/carts/{cart}', [CartsController::class, 'destroy']);

    //CartItems
    Route::put('/cart-items/{cart_item}', [CartItemsController::class, 'update']);
    Route::delete('/cart-items/{cart_item}', [CartItemsController::class, 'destroy']);

    //Orders
    Route::post('/orders', [OrdersController::class, 'checkout']);
    Route::get('/orders', [OrdersController::class, 'index']);
});

Route::get('/products/search', [ProductsController::class, 'searchProduct']);
Route::get('/products/{id}', [ProductsController::class, 'getProductByCategoryId']);