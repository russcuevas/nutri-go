<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('home')->with('error', 'Store profile not found.');
        }

        $pendingOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending_store')
            ->with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrders = Order::where('store_id', $store->id)
            ->whereIn('status', ['store_accepted_preparing', 'ready_for_pickup', 'rider_assigned', 'rider_picked_up', 'on_the_way'])
            ->with(['items.product', 'rider.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $completedOrdersCount = Order::where('store_id', $store->id)
            ->where('status', 'delivered')
            ->count();

        $totalRevenue = Order::where('store_id', $store->id)
            ->where('status', 'delivered')
            ->sum('subtotal');

        $productsCount = Product::where('store_id', $store->id)->count();
        $reviews = Review::where('store_id', $store->id)->with('user')->latest()->take(5)->get();

        return view('store.dashboard.index', compact(
            'store',
            'pendingOrders',
            'activeOrders',
            'completedOrdersCount',
            'totalRevenue',
            'productsCount',
            'reviews'
        ));
    }

    public function toggleStatus()
    {
        $store = Auth::user()->store;
        $store->update(['is_open' => !$store->is_open]);
        $status = $store->is_open ? 'Open for orders' : 'Closed';
        return back()->with('success', "Store is now {$status}.");
    }
}
