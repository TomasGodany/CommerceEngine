<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandApiController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CustomerOrderController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category:slug}', [CategoryApiController::class, 'show']);
    Route::get('brands', [BrandApiController::class, 'index']);
    Route::get('brands/{brand:slug}', [BrandApiController::class, 'show']);
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product:slug}', [ProductApiController::class, 'show']);

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::get('cart', [CartController::class, 'show']);
    Route::post('cart/items', [CartController::class, 'addItem']);
    Route::patch('cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('cart/items/{cartItem}', [CartController::class, 'removeItem']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('checkout', [CheckoutController::class, 'store']);
        Route::get('orders', [CustomerOrderController::class, 'index']);
        Route::get('orders/{order}', [CustomerOrderController::class, 'show']);
    });
});
