<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderTracking;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        $statusFilter = $request->get('status');

        $ordersQuery = Order::where('store_id', $store->id)
            ->with(['items.product', 'user', 'rider.user'])
            ->orderBy('created_at', 'desc');

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        return view('store.orders.index', compact('store', 'orders', 'statusFilter'));
    }

    public function show(int $id)
    {
        $store = Auth::user()->store;
        $order = Order::where('store_id', $store->id)
            ->where('id', $id)
            ->with(['items.product', 'user', 'rider.user', 'trackings'])
            ->firstOrFail();

        return view('store.orders.show', compact('store', 'order'));
    }

    public function accept(Request $request, int $id)
    {
        $store = Auth::user()->store;
        $order = Order::where('store_id', $store->id)->where('id', $id)->firstOrFail();

        if ($order->status !== 'pending_store') {
            return back()->with('error', 'Order cannot be accepted in its current state.');
        }

        $prepTime = (int) $request->input('estimated_prep_time_mins', 20);

        $order->update([
            'status' => 'store_accepted_preparing',
            'payment_status' => 'verified',
            'estimated_prep_time_mins' => $prepTime,
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'store_accepted_preparing',
            'title' => 'Payment Verified & Preparing Food',
            'description' => "Store accepted your order. Estimated prep time: {$prepTime} minutes.",
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
        ]);

        return back()->with('success', "Order #{$order->order_number} accepted and marked as Preparing!");
    }

    public function decline(Request $request, int $id)
    {
        $store = Auth::user()->store;
        $order = Order::where('store_id', $store->id)->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'store_decline_reason' => 'required|string|max:500',
        ]);

        $order->update([
            'status' => 'declined_by_store',
            'payment_status' => 'failed',
            'store_decline_reason' => $validated['store_decline_reason'],
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'declined_by_store',
            'title' => 'Order Declined by Store',
            'description' => "Reason: " . $validated['store_decline_reason'],
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
        ]);

        return back()->with('info', "Order #{$order->order_number} has been declined.");
    }

    public function markReady(int $id)
    {
        $store = Auth::user()->store;
        $order = Order::where('store_id', $store->id)->where('id', $id)->firstOrFail();

        if ($order->status !== 'store_accepted_preparing') {
            return back()->with('error', 'Order must be in preparing status first.');
        }

        $order->update([
            'status' => 'ready_for_pickup',
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'ready_for_pickup',
            'title' => 'Ready for Rider Pickup',
            'description' => "Food is freshly packed and waiting for delivery rider collection in Lipa.",
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
        ]);

        return back()->with('success', "Order #{$order->order_number} is now marked Ready for Pickup! It is dispatched to nearby Lipa riders.");
    }
}
