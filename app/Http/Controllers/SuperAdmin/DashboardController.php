<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        $totalGrossSales = (float) Order::where('status', 'delivered')->sum('total_amount');
        $totalPlatformFees = (float) Order::where('status', 'delivered')->sum('platform_fee');

        $pendingStoresCount = Store::where('status', 'pending')->count();
        $approvedStoresCount = Store::where('status', 'approved')->count();

        $pendingRidersCount = Rider::where('status', 'pending')->count();
        $activeRidersCount = Rider::where('status', 'approved')->where('is_online', true)->count();

        $pendingPayoutsCount = RiderPayoutRequest::where('status', 'pending')->count();
        $pendingPayoutsAmount = (float) RiderPayoutRequest::where('status', 'pending')->sum('amount');

        $recentOrders = Order::with(['store', 'rider.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $pendingStores = Store::where('status', 'pending')->with('user')->take(5)->get();
        $pendingRiders = Rider::where('status', 'pending')->with('user')->take(5)->get();

        // 1. Revenue & GMV Trend (Last 7 Days)
        $revenueDates = [];
        $dailySales = [];
        $dailyFees = [];
        $dailyOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->format('M d');

            $revenueDates[] = $label;

            $sales = (float) Order::whereDate('created_at', $dateStr)
                ->where('status', 'delivered')
                ->sum('total_amount');

            $fees = (float) Order::whereDate('created_at', $dateStr)
                ->where('status', 'delivered')
                ->sum('platform_fee');

            $count = Order::whereDate('created_at', $dateStr)->count();

            $dailySales[] = round($sales, 2);
            $dailyFees[] = round($fees, 2);
            $dailyOrders[] = $count;
        }

        // 2. Orders by Actual Delivery Address (Top 6)
        $addressStats = Order::selectRaw('delivery_address, count(*) as order_count, sum(total_amount) as total_spent')
            ->whereNotNull('delivery_address')
            ->where('delivery_address', '!=', '')
            ->groupBy('delivery_address')
            ->orderByDesc('order_count')
            ->take(6)
            ->get();

        $addressLabels = $addressStats->pluck('delivery_address')->toArray();
        $addressCounts = $addressStats->pluck('order_count')->toArray();

        // 3. Store Health Categories Distribution
        $healthCatStats = Store::selectRaw('health_category, count(*) as total')
            ->whereNotNull('health_category')
            ->groupBy('health_category')
            ->orderByDesc('total')
            ->get();

        $healthCatLabels = $healthCatStats->pluck('health_category')->toArray();
        $healthCatCounts = $healthCatStats->pluck('total')->toArray();

        // 4. Order Status Distribution (Delivered, In Progress, Pending, Declined/Cancelled)
        $statusCounts = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $deliveredCount = $statusCounts['delivered'] ?? 0;
        $inProgressCount = ($statusCounts['store_accepted_preparing'] ?? 0)
            + ($statusCounts['ready_for_pickup'] ?? 0)
            + ($statusCounts['rider_assigned'] ?? 0)
            + ($statusCounts['rider_picked_up'] ?? 0)
            + ($statusCounts['on_the_way'] ?? 0);
        $pendingApprovalCount = $statusCounts['pending_store'] ?? 0;
        $cancelledDeclinedCount = ($statusCounts['declined_by_store'] ?? 0) + ($statusCounts['cancelled'] ?? 0);

        $orderStatusData = [
            'labels' => ['Delivered', 'In Kitchen / Transit', 'Pending Approval', 'Cancelled / Declined'],
            'counts' => [$deliveredCount, $inProgressCount, $pendingApprovalCount, $cancelledDeclinedCount]
        ];

        // Average Order Value (AOV)
        $avgOrderValue = $deliveredOrders > 0 ? ($totalGrossSales / $deliveredOrders) : 0;

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
            'pendingRiders',
            'revenueDates',
            'dailySales',
            'dailyFees',
            'dailyOrders',
            'addressLabels',
            'addressCounts',
            'healthCatLabels',
            'healthCatCounts',
            'orderStatusData',
            'avgOrderValue'
        ));
    }
}
