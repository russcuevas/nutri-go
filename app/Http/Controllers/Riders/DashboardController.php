<?php

namespace App\Http\Controllers\Riders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RiderWallet;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rider = $user->rider;

        if (!$rider) {
            return redirect()->route('home')->with('error', 'Rider profile not found.');
        }

        // Active orders for this rider
        $myActiveOrders = Order::where('rider_id', $rider->id)
            ->whereIn('status', ['rider_assigned', 'rider_picked_up', 'on_the_way'])
            ->with(['store', 'user', 'items'])
            ->orderBy('created_at', 'asc')
            ->get();

        $activeOrdersCount = $myActiveOrders->count();
        $canAcceptMore = $rider->isApproved() && $rider->is_online && ($activeOrdersCount < 3);

        // Available orders ready for pickup in Lipa City (not yet assigned to any rider)
        $availableOrders = Order::where('status', 'ready_for_pickup')
            ->whereNull('rider_id')
            ->with(['store', 'user', 'items'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Completed deliveries today
        $todayCompletedCount = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereDate('rider_delivered_at', today())
            ->count();

        $wallet = $rider->wallet ?? RiderWallet::firstOrCreate(['rider_id' => $rider->id]);

        return view('riders.dashboard.index', compact(
            'rider',
            'myActiveOrders',
            'activeOrdersCount',
            'canAcceptMore',
            'availableOrders',
            'todayCompletedCount',
            'wallet'
        ));
    }

    public function toggleDuty()
    {
        $rider = Auth::user()->rider;
        $rider->update(['is_online' => !$rider->is_online]);
        $status = $rider->is_online ? 'Online (Ready to deliver)' : 'Offline';
        return back()->with('success', "Status set to {$status}.");
    }

    public function updateLocation(Request $request)
    {
        $rider = Auth::user()->rider;
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $rider->update([
            'current_latitude' => $validated['lat'],
            'current_longitude' => $validated['lng'],
        ]);

        return response()->json(['success' => true]);
    }
}
