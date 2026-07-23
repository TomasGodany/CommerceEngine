<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the administration dashboard.
     */
    public function index(): View
    {
        return view('dashboard.index', [
            'productsCount' => Product::count(),
            'categoriesCount' => Category::count(),
            'brandsCount' => Brand::count(),
            'usersCount' => User::count(),
            'stockItemsCount' => StockItem::count(),
            'ordersCount' => Order::count(),
            'customersCount' => Customer::count(),
            'couponsCount' => Coupon::count(),
        ]);
    }
}
