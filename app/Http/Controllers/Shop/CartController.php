<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $key => $details) {
            $total += $details['price'] * $details['quantity'];
        }
        
        $shipping = $total > 0 ? (float)\App\Models\Setting::get('shipping_fee', '10.00') : 0;
        $grandTotal = $total + $shipping;

        return view('shop.cart', compact('cart', 'total', 'shipping', 'grandTotal'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $quantity = (int) $request->input('quantity', 1);
        $selectedSize  = $request->input('size', '');
        $selectedColor = $request->input('color', '');

        // Validate that size/color are required when the product has them
        if (!empty($product->sizes) && empty($selectedSize)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please select a size.']);
            }
            return redirect()->back()->with('error', 'Please select a size.');
        }
        if (!empty($product->colors) && empty($selectedColor)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please select a color.']);
            }
            return redirect()->back()->with('error', 'Please select a color.');
        }

        // Quantity must always be at least 1
        if ($quantity < 1) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please enter a valid quantity (minimum 1).']);
            }
            return redirect()->back()->with('error', 'Please enter a valid quantity (minimum 1).');
        }

        // Unique cart key: combine product ID + size + color so same product in different
        // variants becomes a separate cart line item
        $cartKey = $id . '_' . $selectedSize . '_' . $selectedColor;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id'     => $id,
                'name'           => $product->name,
                'quantity'       => $quantity,
                'price'          => (float) $product->price,
                'compare_price'  => $product->compare_price ?? null,
                'image'          => $product->image_url,
                'slug'           => $product->slug,
                'selected_size'  => $selectedSize,
                'selected_color' => $selectedColor,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Product added to cart!',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $cartKey  = $request->input('cart_key', $request->id);
        $quantity = (int) $request->input('quantity');

        if ($cartKey && $quantity) {
            $cart    = session()->get('cart', []);
            $details = $cart[$cartKey] ?? null;

            if ($details) {
                $product = Product::find($details['product_id'] ?? $cartKey);

                $availableStock = $product ? $product->getVariantStock($details['selected_size'] ?? null, $details['selected_color'] ?? null) : 0;

                if ($product && $quantity <= $availableStock) {
                    $cart[$cartKey]['quantity'] = $quantity;
                    session()->put('cart', $cart);
                    return response()->json(['success' => true]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $availableStock . ' items available in stock.',
                    ]);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found.']);
    }

    public function remove(Request $request)
    {
        $cartKey = $request->input('cart_key', $request->id);

        if ($cartKey) {
            $cart = session()->get('cart', []);
            if (isset($cart[$cartKey])) {
                unset($cart[$cartKey]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully!');
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
