<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Default shipping for Inside Dhaka
        $shippingInsideDhaka  = (float)Setting::get('shipping_inside_dhaka', '60.00');
        $shippingOutsideDhaka = (float)Setting::get('shipping_outside_dhaka', '120.00');
        $shipping = $shippingInsideDhaka; // default
        $grandTotal = $total + $shipping;

        return view('shop.checkout', compact('cart', 'total', 'shipping', 'grandTotal', 'shippingInsideDhaka', 'shippingOutsideDhaka'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'area' => 'required|in:inside_dhaka,outside_dhaka',
            'shipping_address' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $shippingCost = $request->area === 'inside_dhaka'
            ? (float)Setting::get('shipping_inside_dhaka', '60.00')
            : (float)Setting::get('shipping_outside_dhaka', '120.00');
        $total = $subtotal + $shippingCost;

        // Build full shipping address with area
        $areaLabel = $request->area === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka';
        $fullAddress = "[{$areaLabel}] {$request->shipping_address}";

        // Perform stock check and creation within a transaction
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(), // null if guest
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email ?? '',
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $fullAddress,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cart as $cartKey => $details) {
                $productId = $details['product_id'] ?? $cartKey;
                $product = Product::findOrFail($productId);
                
                // Stock validation - check variant stock if applicable
                $availableStock = $product->getVariantStock($details['selected_size'] ?? null, $details['selected_color'] ?? null);
                if ($availableStock < $details['quantity']) {
                    throw new \Exception("Sorry, '{$product->name}' is out of stock or doesn't have enough inventory.");
                }

                // Deduct stock - update variant_stock or base stock
                $selectedSize = $details['selected_size'] ?? null;
                $selectedColor = $details['selected_color'] ?? null;
                if (!empty($product->variant_stock)) {
                    $variantStock = $product->variant_stock;
                    if ($selectedColor && $selectedSize && isset($variantStock[$selectedColor][$selectedSize])) {
                        $variantStock[$selectedColor][$selectedSize] = max(0, $variantStock[$selectedColor][$selectedSize] - $details['quantity']);
                    } elseif ($selectedColor && isset($variantStock[$selectedColor]) && !is_array($variantStock[$selectedColor])) {
                        $variantStock[$selectedColor] = max(0, $variantStock[$selectedColor] - $details['quantity']);
                    } elseif ($selectedSize && isset($variantStock[$selectedSize]) && !is_array($variantStock[$selectedSize])) {
                        $variantStock[$selectedSize] = max(0, $variantStock[$selectedSize] - $details['quantity']);
                    }
                    $product->update(['variant_stock' => $variantStock]);
                } else {
                    $product->decrement('stock', $details['quantity']);
                }

                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $productId,
                    'product_name'   => $details['name'],
                    'selected_size'  => $details['selected_size'] ?? null,
                    'selected_color' => $details['selected_color'] ?? null,
                    'price'          => $details['price'],
                    'quantity'       => $details['quantity'],
                    'total'          => $details['price'] * $details['quantity'],
                ]);
            }

            DB::commit();

            // Clear Cart
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->order_number)
                             ->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('shop.order-success', compact('order'));
    }
}
