<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)->take(6)->get();
        $categories = Category::withCount('products')->get();
        $sliders = Slider::where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();

        // Stats
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = Category::count();

        return view('shop.home', compact(
            'featuredProducts', 'categories', 'sliders',
            'totalProducts', 'totalOrders', 'totalCategories'
        ));
    }
}
