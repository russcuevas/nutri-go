<?php

namespace App\Http\Controllers\Riders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\RiderWallet;
use App\Models\RiderWalletTransaction;

class OrderDeliveryController extends Controller
{
    public function accept(int $orderId)
    {
        $rider = Auth::user()->rider;

        if (!$rider->isApproved()) {
            return back()->with('error', 'Your rider account is not approved yet.');
        }

        // Enforce maximum 3 active orders rule
        $activeOrdersCount = Order::where('rider_id', $rider->id)
            ->whereIn('status', ['rider_assigned', 'rider_picked_up', 'on_the_way'])
            ->count();

        if ($activeOrdersCount >= 3) {
            return back()->with('error', 'Maximum 3 active orders reached. Please complete current deliveries first to ensure food freshness.');
        }

        $order = Order::where('id', $orderId)
            ->where('status', 'ready_for_pickup')
            ->whereNull('rider_id')
            ->firstOrFail();

        $order->update([
            'rider_id' => $rider->id,
            'status' => 'rider_assigned',
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'rider_assigned',
            'title' => 'Rider Assigned for Delivery',
            'description' => "Rider {$rider->user->name} ({$rider->vehicle_type} - {$rider->plate_number}) accepted your delivery and is heading to the store.",
            'latitude' => $rider->current_latitude ?? $order->store->latitude,
            'longitude' => $rider->current_longitude ?? $order->store->longitude,
        ]);

        return redirect()->route('riders.orders.active', $order->id)->with('success', "Order #{$order->order_number} assigned to you! Proceed to {$order->store->store_name}.");
    }

    public function showActive(int $id)
    {
        $rider = Auth::user()->rider;
        $order = Order::where('rider_id', $rider->id)
            ->where('id', $id)
            ->with(['store', 'user', 'items.product', 'trackings'])
            ->firstOrFail();

        return view('riders.orders.active', compact('rider', 'order'));
    }

    public function markPickedUp(int $id)
    {
        $rider = Auth::user()->rider;
        $order = Order::where('rider_id', $rider->id)->where('id', $id)->firstOrFail();

        $order->update([
            'status' => 'rider_picked_up',
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'rider_picked_up',
            'title' => 'Order Collected from Store',
            'description' => "Rider verified meal package and collected it from {$order->store->store_name}.",
            'latitude' => $order->store->latitude,
            'longitude' => $order->store->longitude,
        ]);

        return back()->with('success', 'Order marked as Picked Up! You may now head to customer address in Lipa.');
    }

    public function markOnTheWay(int $id)
    {
        $rider = Auth::user()->rider;
        $order = Order::where('rider_id', $rider->id)->where('id', $id)->firstOrFail();

        $order->update([
            'status' => 'on_the_way',
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'on_the_way',
            'title' => 'Rider On The Way to Your Address',
            'description' => "Rider is en route to Brgy. {$order->delivery_barangay}. ETA: ~{$order->estimated_delivery_time_mins} mins.",
            'latitude' => $rider->current_latitude ?? $order->store->latitude,
            'longitude' => $rider->current_longitude ?? $order->store->longitude,
        ]);

        return back()->with('success', 'Order marked On The Way! Live map updated for customer.');
    }

    public function markDelivered(Request $request, int $id)
    {
        $rider = Auth::user()->rider;
        $order = Order::where('rider_id', $rider->id)->where('id', $id)->firstOrFail();

        $request->validate([
            'rider_proof_image' => 'nullable|image|max:4096',
        ]);

        $proofPath = null;
        if ($request->hasFile('rider_proof_image')) {
            $file = $request->file('rider_proof_image');
            $filename = 'proof_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/riders/delivery_proofs');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $proofPath = 'images/riders/delivery_proofs/' . $filename;
        }

        $order->update([
            'status' => 'delivered',
            'rider_proof_image' => $proofPath,
            'rider_delivered_at' => now(),
        ]);

        // Increment rider stats
        $rider->increment('total_deliveries');

        // Automatically Credit Delivery Fee to Rider's Digital Wallet
        $wallet = RiderWallet::firstOrCreate(['rider_id' => $rider->id]);
        $deliveryFeeEarned = (float) $order->delivery_fee;

        $wallet->increment('balance', $deliveryFeeEarned);
        $wallet->increment('total_earnings', $deliveryFeeEarned);

        RiderWalletTransaction::create([
            'rider_id' => $rider->id,
            'order_id' => $order->id,
            'type' => 'delivery_fee',
            'amount' => $deliveryFeeEarned,
            'description' => "Delivery Fee for Order #{$order->order_number} (Brgy. {$order->delivery_barangay}, {$order->distance_km} km)",
        ]);

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'delivered',
            'title' => 'Order Successfully Delivered',
            'description' => "Package received at {$order->delivery_address}. Enjoy your fresh, healthy meal!",
            'latitude' => $order->delivery_latitude,
            'longitude' => $order->delivery_longitude,
        ]);

        return redirect()->route('riders.dashboard')->with('success', "Order #{$order->order_number} marked Delivered! ₱{$deliveryFeeEarned} has been credited to your NutriGo Wallet.");
    }
}
