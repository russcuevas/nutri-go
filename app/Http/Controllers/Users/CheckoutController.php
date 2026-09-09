<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Store;
use App\Models\User;
use App\Services\LipaLocationService;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('users.explore')->with('info', 'Your cart is empty. Please add items to proceed.');
        }

        $storeId = session()->get('cart_store_id');
        $store = Store::where('id', $storeId)->where('status', 'approved')->firstOrFail();

        if (!$store->is_open) {
            return redirect()->route('users.cart.index')->with('error', "The store ({$store->store_name}) is currently closed and not accepting orders.");
        }

        $subtotal = 0;
        $totalCalories = 0;
        $totalProtein = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalCalories += ($item['calories'] ?? 0) * $item['quantity'];
            $totalProtein += ($item['protein_g'] ?? 0) * $item['quantity'];
        }

        $barangays = LipaLocationService::getBarangayList();
        $barangayCoords = LipaLocationService::$barangays;
        $defaultBarangay = 'Marauoy';
        $defaultCoords = LipaLocationService::getCoordinates($defaultBarangay);

        $distanceKm = LipaLocationService::calculateDistance(
            $store->latitude,
            $store->longitude,
            $defaultCoords['lat'],
            $defaultCoords['lng']
        );

        $feeData = LipaLocationService::calculateDeliveryFee($distanceKm);
        /** @var User|null $user */
        $user = Auth::user();

        $isVip = $user && $user->isVipSubscriber();
        $discount = $isVip ? min(50.00, $feeData['delivery_fee']) : 0.00;

        return view('users.checkout.index', compact(
            'cart',
            'store',
            'subtotal',
            'totalCalories',
            'totalProtein',
            'barangays',
            'barangayCoords',
            'defaultBarangay',
            'defaultCoords',
            'distanceKm',
            'feeData',
            'user',
            'isVip',
            'discount'
        ));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('users.explore')->with('error', 'Cart is empty.');
        }

        $storeId = session()->get('cart_store_id');
        $store = Store::where('id', $storeId)->where('status', 'approved')->firstOrFail();

        if (!$store->is_open) {
            return redirect()->route('users.cart.index')->with('error', "The store ({$store->store_name}) is currently closed and not accepting orders.");
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'delivery_barangay' => 'nullable|string',
            'delivery_address' => 'required|string',
            'delivery_landmark' => 'nullable|string|max:255',
            'delivery_notes' => 'nullable|string',
            'delivery_latitude' => 'required|numeric',
            'delivery_longitude' => 'required|numeric',
            'delivery_type' => 'required|in:immediate,scheduled',
            'scheduled_at' => 'nullable|required_if:delivery_type,scheduled|date|after:now',
            'payment_method' => 'required|in:gcash,cod',
            'payment_reference_no' => 'nullable|required_if:payment_method,gcash|string|max:50',
            'payment_proof_image' => 'nullable|required_if:payment_method,gcash|image|max:4096',
        ]);

        $deliveryBarangay = !empty($validated['delivery_barangay'])
            ? $validated['delivery_barangay']
            : LipaLocationService::findNearestBarangay((float) $validated['delivery_latitude'], (float) $validated['delivery_longitude']);

        $distanceKm = LipaLocationService::calculateDistance(
            $store->latitude,
            $store->longitude,
            (float) $validated['delivery_latitude'],
            (float) $validated['delivery_longitude']
        );

        $feeData = LipaLocationService::calculateDeliveryFee($distanceKm);
        $deliveryFee = $feeData['delivery_fee'];
        $platformFee = 10.00;

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        /** @var User|null $user */
        $user = Auth::user();
        $discountAmount = 0.00;
        if ($user && $user->isVipSubscriber()) {
            $discountAmount = min(50.00, $deliveryFee);
        }

        $totalAmount = $subtotal + $deliveryFee + $platformFee - $discountAmount;

        $proofPath = null;
        if ($request->hasFile('payment_proof_image')) {
            $proofPath = $request->file('payment_proof_image')->store('orders/payment_proofs', 'public');
        }

        $orderNumber = 'NTR-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'store_id' => $store->id,
            'status' => 'pending_store',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'platform_fee' => $platformFee,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending_verification',
            'payment_proof_image' => $proofPath,
            'payment_reference_no' => $validated['payment_reference_no'] ?? null,
            'recipient_name' => $validated['recipient_name'],
            'recipient_phone' => $validated['recipient_phone'],
            'delivery_barangay' => $deliveryBarangay,
            'delivery_address' => $validated['delivery_address'],
            'delivery_landmark' => $validated['delivery_landmark'] ?? null,
            'delivery_notes' => $validated['delivery_notes'] ?? null,
            'delivery_latitude' => $validated['delivery_latitude'],
            'delivery_longitude' => $validated['delivery_longitude'],
            'distance_km' => $distanceKm,
            'delivery_type' => $validated['delivery_type'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'estimated_prep_time_mins' => 20,
            'estimated_delivery_time_mins' => $feeData['estimated_time_mins'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'calories' => $item['calories'] ?? 0,
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'pending_store',
            'title' => 'Order Placed & Payment Sent',
            'description' => $order->payment_method === 'gcash'
                ? "GCash payment proof uploaded with Ref #{$order->payment_reference_no}. Awaiting store verification."
                : "Order placed via Cash on Delivery. Awaiting store confirmation.",
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
        ]);

        session()->forget(['cart', 'cart_store_id']);

        return redirect()->route('users.orders.track', $order->order_number)->with('success', 'Order placed successfully! You can track its live status below.');
    }
}
