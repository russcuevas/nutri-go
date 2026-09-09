<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderTracking;
use App\Models\User;

class OrderTrackingController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['store', 'rider.user', 'items.product', 'review'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.orders.index', compact('orders'));
    }

    public function track(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['store', 'rider.user', 'items.product', 'trackings', 'review'])
            ->firstOrFail();

        if (Auth::check() && Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $riderLat = $order->rider?->current_latitude ?? (($order->store->latitude + $order->delivery_latitude) / 2);
        $riderLng = $order->rider?->current_longitude ?? (($order->store->longitude + $order->delivery_longitude) / 2);

        $initialTrackings = $order->trackings->map(function($t) {
            return [
                'status' => $t->status,
                'title' => $t->title,
                'description' => $t->description,
                'time' => $t->created_at->format('h:i A'),
            ];
        })->values()->toArray();

        $initialRider = $order->rider ? [
            'name' => $order->rider->user->name,
            'phone' => $order->rider->phone,
            'vehicle' => $order->rider->vehicle_type,
            'plate' => $order->rider->plate_number,
            'rating' => $order->rider->rating,
            'lat' => $order->rider->current_latitude ?? $order->store->latitude,
            'lng' => $order->rider->current_longitude ?? $order->store->longitude,
        ] : null;

        return view('users.orders.track', compact('order', 'riderLat', 'riderLng', 'initialTrackings', 'initialRider'));
    }

    public function statusPoll(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['rider.user', 'trackings'])
            ->firstOrFail();

        $isRiderActive = $order->rider_id && in_array($order->status, ['rider_assigned', 'rider_picked_up', 'on_the_way']);
        $isRiderDelivering = $order->rider_id && in_array($order->status, ['rider_picked_up', 'on_the_way']);
        
        $riderData = null;
        if ($isRiderActive && $order->rider) {
            $riderLat = $order->rider->current_latitude ?? $order->store->latitude;
            $riderLng = $order->rider->current_longitude ?? $order->store->longitude;
            $riderData = [
                'name' => $order->rider->user->name,
                'phone' => $order->rider->phone,
                'vehicle' => $order->rider->vehicle_type,
                'plate' => $order->rider->plate_number,
                'rating' => $order->rider->rating,
                'lat' => $riderLat,
                'lng' => $riderLng,
            ];
        }

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'status_badge_class' => $order->status_badge_class,
            'payment_status' => $order->payment_status,
            'store_decline_reason' => $order->store_decline_reason,
            'is_rider_active' => $isRiderActive,
            'is_rider_delivering' => $isRiderDelivering,
            'rider' => $riderData,
            'trackings' => $order->trackings->map(function($t) {
                return [
                    'status' => $t->status,
                    'title' => $t->title,
                    'description' => $t->description,
                    'time' => $t->created_at->format('h:i A'),
                ];
            }),
        ]);
    }

    public function submitReview(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->firstOrFail();

        $validated = $request->validate([
            'store_rating' => 'required|integer|min:1|max:5',
            'store_comment' => 'nullable|string|max:1000',
            'rider_rating' => 'required|integer|min:1|max:5',
            'rider_comment' => 'nullable|string|max:1000',
            'health_satisfaction' => 'nullable|string',
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => Auth::id(),
                'store_id' => $order->store_id,
                'rider_id' => $order->rider_id,
                'store_rating' => $validated['store_rating'],
                'store_comment' => $validated['store_comment'] ?? null,
                'rider_rating' => $validated['rider_rating'],
                'rider_comment' => $validated['rider_comment'] ?? null,
                'health_satisfaction' => $validated['health_satisfaction'] ?? null,
            ]
        );

        $avgStoreRating = Review::where('store_id', $order->store_id)->avg('store_rating');
        $storeCount = Review::where('store_id', $order->store_id)->count();
        $order->store->update([
            'rating' => round($avgStoreRating, 2),
            'total_reviews' => $storeCount,
        ]);

        if ($order->rider_id) {
            $avgRiderRating = Review::where('rider_id', $order->rider_id)->avg('rider_rating');
            $order->rider->update([
                'rating' => round($avgRiderRating, 2),
            ]);
        }

        return back()->with('success', 'Thank you for your rating! Your health review has been submitted.');
    }

    public function cancel(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        // Check permission (User must own the order or be superadmin)
        if (Auth::check() && Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action on this order.');
        }

        // Only allow cancellation if the store has not yet accepted the order
        if ($order->status !== 'pending_store') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hindi na maaaring i-cancel ang order dahil na-accept o hinahanda na ito ng restaurant.',
                ], 422);
            }
            return back()->with('error', 'Hindi na maaaring i-cancel ang order dahil na-accept o hinahanda na ito ng restaurant.');
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($order) {
            // Delete order items
            $order->items()->delete();
            // Delete trackings
            $order->trackings()->delete();
            // Delete review if any
            $order->review()?->delete();
            // Delete the order itself
            $order->delete();
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$orderNumber} has been successfully cancelled and deleted.",
                'redirect' => route('users.orders.index'),
            ]);
        }

        return redirect()->route('users.orders.index')->with('success', "Order #{$orderNumber} ay matagumpay na na-cancel at na-delete.");
    }
}
