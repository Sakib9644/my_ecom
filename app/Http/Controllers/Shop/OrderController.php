<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your orders.');
        }

        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('shop.orders-index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();

        // Security check: Only allow the placing user or admins to view directly.
        // Guests can track using track page.
        if (Auth::check()) {
            if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            // If they are a guest but trying to load details, they must go through the track form.
            // We can check if a guest session tracker exists or redirect to track page.
            if (session('tracked_order') !== $order->order_number) {
                return redirect()->route('orders.track')->with('error', 'Please enter order details to track.');
            }
        }

        return view('shop.order-show', compact('order'));
    }

    public function track()
    {
        return view('shop.track-order');
    }

    public function findOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_number', $request->order_number)
                      ->where('customer_email', $request->email)
                      ->first();

        if (!$order) {
            return back()->with('error', 'No order found with those details. Please check and try again.')->withInput();
        }

        // Store tracked order in session to bypass direct view checks
        session()->put('tracked_order', $order->order_number);

        return redirect()->route('orders.show', $order->order_number);
    }
}
