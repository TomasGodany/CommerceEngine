<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\PriceListItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('warehouses', WarehouseController::class)->except(['show']);
        Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);

        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status', [OrderStatusController::class, 'update'])->name('orders.status.update');

        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/addresses', [CustomerAddressController::class, 'store'])->name('customers.addresses.store');
        Route::delete('customer-addresses/{customerAddress}', [CustomerAddressController::class, 'destroy'])->name('customer-addresses.destroy');

        Route::resource('price-lists', PriceListController::class);
        Route::post('price-lists/{priceList}/items', [PriceListItemController::class, 'store'])->name('price-lists.items.store');
        Route::delete('price-list-items/{priceListItem}', [PriceListItemController::class, 'destroy'])->name('price-list-items.destroy');

        Route::resource('discounts', DiscountController::class)->except(['show']);
        Route::resource('coupons', CouponController::class)->except(['show']);
    });
});
