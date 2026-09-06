<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Rider;
use App\Models\Order;
use App\Models\RiderPayoutRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalGrossSales = Order::where('status', 'delivered')->sum('total_amount');
        $totalPlatformFees = Order::where('status', 'delivered')->sum('platform_fee');

        $pendingStoresCount = Store::where('status', 'pending')->count();
        $approvedStoresCount = Store::where('status', 'approved')->count();

        $pendingRidersCount = Rider::where('status', 'pending')->count();
        $activeRidersCount = Rider::where('status', 'approved')->where('is_online', true)->count();

        $pendingPayoutsCount = RiderPayoutRequest::where('status', 'pending')->count();
        $pendingPayoutsAmount = RiderPayoutRequest::where('status', 'pending')->sum('amount');

        $recentOrders = Order::with(['store', 'rider.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $pendingStores = Store::where('status', 'pending')->with('user')->take(5)->get();
        $pendingRiders = Rider::where('status', 'pending')->with('user')->take(5)->get();

        return view('superadmin.dashboard.index', compact(
            'totalOrders',
            'deliveredOrders',
            'totalGrossSales',
            'totalPlatformFees',
            'pendingStoresCount',
            'approvedStoresCount',
            'pendingRidersCount',
            'activeRidersCount',
            'pendingPayoutsCount',
            'pendingPayoutsAmount',
            'recentOrders',
            'pendingStores',
            'pendingRiders'
        ));
    }
}
