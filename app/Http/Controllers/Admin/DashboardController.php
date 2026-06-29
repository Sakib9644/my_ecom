<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')
            ->orWhere('status', 'delivered')
            ->sum('total');

        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('is_admin', false)->count();

        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        // Calculate sales per category for insights
        $categories = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'categories'
        ));
    }
}
