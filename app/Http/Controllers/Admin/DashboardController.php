<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

        $maintenanceMode = file_exists(storage_path('framework/down'));

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'categories',
            'maintenanceMode'
        ));
    }

    /**
     * Put the application into maintenance mode.
     */
    public function maintenanceDown()
    {
        Artisan::call('down', [
            '--secret' => 'techx-' . md5(now()),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Application is now in maintenance mode.');
    }

    /**
     * Take the application out of maintenance mode.
     */
    public function maintenanceUp()
    {
        Artisan::call('up');

        return redirect()->route('admin.dashboard')->with('success', 'Application is back online.');
    }
}
