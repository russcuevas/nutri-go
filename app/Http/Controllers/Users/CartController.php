<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Services\LipaLocationService;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $storeId = session()->get('cart_store_id');
        $store = $storeId ? Store::find($storeId) : null;
        $barangays = LipaLocationService::getBarangayList();

        $subtotal = 0;
        $totalCalories = 0;
        $totalProtein = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalCalories += ($item['calories'] ?? 0) * $item['quantity'];
            $totalProtein += ($item['protein_g'] ?? 0) * $item['quantity'];
        }

        return view('users.cart.index', compact('cart', 'store', 'subtotal', 'totalCalories', 'totalProtein', 'barangays'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = max(1, (int) $request->input('quantity', 1));

        $product = Product::with('store')->findOrFail($productId);
        $cart = session()->get('cart', []);
        $currentStoreId = session()->get('cart_store_id');

        if (!empty($cart) && $currentStoreId && $currentStoreId != $product->store_id) {
            if ($request->boolean('replace_cart')) {
                $cart = [];
                session()->put('cart_store_id', $product->store_id);
            } else {
                return response()->json([
                    'success' => false,
                    'conflict' => true,
                    'message' => "Your cart contains items from another store. Clear cart and add from {$product->store->store_name}?",
                ], 409);
            }
        }

        session()->put('cart_store_id', $product->store_id);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->image_url,
                'calories' => (int) $product->calories,
                'protein_g' => (float) $product->protein_g,
                'carbs_g' => (float) $product->carbs_g,
                'fat_g' => (float) $product->fat_g,
                'store_id' => $product->store_id,
                'store_name' => $product->store->store_name,
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'message' => "{$product->name} added to cart!",
            ]);
        }

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        if (empty($cart)) {
            session()->forget('cart_store_id');
        }

        session()->put('cart', $cart);

        return redirect()->route('users.cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        if (empty($cart)) {
            session()->forget('cart_store_id');
        }

        session()->put('cart', $cart);

        return redirect()->route('users.cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget(['cart', 'cart_store_id']);
        return redirect()->route('users.cart.index')->with('success', 'Cart cleared.');
    }
}
