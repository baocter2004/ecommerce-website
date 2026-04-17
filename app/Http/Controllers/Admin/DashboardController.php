<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return route('admin.dashboard');
    }

    public function dashboard()
    {
        $categories = Category::withCount('products')->get();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('categories', 'totalOrders', 'totalUsers', 'totalProducts', 'recentOrders'));
    }
}
