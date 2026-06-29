<?php

use Illuminate\Support\Facades\Route;

// Shop / Storefront Controllers
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products Catalog
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/variant-stock', [ProductController::class, 'variantStock'])->name('products.variant-stock');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout (available to both guests and logged-in users)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/success/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');

// Order Tracking (for guests and logged-in users)
Route::get('/track-order', [ShopOrderController::class, 'track'])->name('orders.track');
Route::post('/track-order', [ShopOrderController::class, 'findOrder'])->name('orders.find');

// Logged-in Customer Orders
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [ShopOrderController::class, 'index'])->name('orders.index');
});
// View order detail (accessible by guests if verified in session, or logged in owner)
Route::get('/orders/{order_number}', [ShopOrderController::class, 'show'])->name('orders.show');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Protected by Auth and custom Admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Categories CRUD
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Sizes CRUD
    Route::resource('sizes', \App\Http\Controllers\Admin\SizeController::class)->except(['show']);

    // Colors CRUD
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class)->except(['show']);

    // Sliders CRUD
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class)->except(['show']);

    // Site Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/site-name', [\App\Http\Controllers\Admin\SettingController::class, 'updateSiteName'])->name('settings.update-name');
    Route::post('settings/favicon', [\App\Http\Controllers\Admin\SettingController::class, 'updateFavicon'])->name('settings.update-favicon');
    Route::post('settings/favicon/remove', [\App\Http\Controllers\Admin\SettingController::class, 'removeFavicon'])->name('settings.remove-favicon');

    // Admin Profile
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Users List
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');

    // Maintenance Mode
    Route::post('maintenance/down', [DashboardController::class, 'maintenanceDown'])->name('maintenance.down');
    Route::post('maintenance/up', [DashboardController::class, 'maintenanceUp'])->name('maintenance.up');
});
