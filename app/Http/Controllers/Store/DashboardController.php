<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
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

        $totalRevenue = (float) Order::where('store_id', $store->id)
            ->where('status', 'delivered')
            ->sum('subtotal');

        $totalOrdersCount = Order::where('store_id', $store->id)->count();
        $productsCount = Product::where('store_id', $store->id)->count();
        $reviews = Review::where('store_id', $store->id)->with('user')->latest()->take(5)->get();

        // 1. Store Daily Revenue & Orders Trend (Last 7 Days)
        $revenueDates = [];
        $dailySales = [];
        $dailyOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->format('M d');

            $revenueDates[] = $label;

            $sales = (float) Order::where('store_id', $store->id)
                ->whereDate('created_at', $dateStr)
                ->where('status', 'delivered')
                ->sum('subtotal');

            $count = Order::where('store_id', $store->id)
                ->whereDate('created_at', $dateStr)
                ->count();

            $dailySales[] = round($sales, 2);
            $dailyOrders[] = $count;
        }

        // 2. Top Selling Healthy Dishes (Bar Chart)
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->where('orders.status', 'delivered')
            ->selectRaw('order_items.product_name, sum(order_items.quantity) as total_qty, sum(order_items.subtotal) as total_sales')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topProductLabels = $topProducts->pluck('product_name')->toArray();
        $topProductQuantities = $topProducts->pluck('total_qty')->toArray();
        $topProductSales = $topProducts->pluck('total_sales')->toArray();

        // 3. Order Fulfillment Status Distribution
        $statusCounts = Order::where('store_id', $store->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $deliveredCount = $statusCounts['delivered'] ?? 0;
        $inKitchenTransitCount = ($statusCounts['store_accepted_preparing'] ?? 0)
            + ($statusCounts['ready_for_pickup'] ?? 0)
            + ($statusCounts['rider_assigned'] ?? 0)
            + ($statusCounts['rider_picked_up'] ?? 0)
            + ($statusCounts['on_the_way'] ?? 0);
        $pendingStoreCount = $statusCounts['pending_store'] ?? 0;
        $declinedCancelledCount = ($statusCounts['declined_by_store'] ?? 0) + ($statusCounts['cancelled'] ?? 0);

        $orderStatusData = [
            'labels' => ['Delivered', 'In Kitchen / Transit', 'Pending Verification', 'Declined / Cancelled'],
            'counts' => [$deliveredCount, $inKitchenTransitCount, $pendingStoreCount, $declinedCancelledCount]
        ];

        // 4. Customer Delivery Addresses (Top 5)
        $addressStats = Order::where('store_id', $store->id)
            ->whereNotNull('delivery_address')
            ->where('delivery_address', '!=', '')
            ->selectRaw('delivery_address, count(*) as count')
            ->groupBy('delivery_address')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $addressLabels = $addressStats->pluck('delivery_address')->toArray();
        $addressCounts = $addressStats->pluck('count')->toArray();

        // Average Order Value
        $avgOrderValue = $completedOrdersCount > 0 ? ($totalRevenue / $completedOrdersCount) : 0;
        $totalItemsSold = (int) OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->where('orders.status', 'delivered')
            ->sum('order_items.quantity');

        return view('store.dashboard.index', compact(
            'store',
            'pendingOrders',
            'activeOrders',
            'completedOrdersCount',
            'totalRevenue',
            'totalOrdersCount',
            'productsCount',
            'reviews',
            'revenueDates',
            'dailySales',
            'dailyOrders',
            'topProductLabels',
            'topProductQuantities',
            'topProductSales',
            'orderStatusData',
            'addressLabels',
            'addressCounts',
            'avgOrderValue',
            'totalItemsSold'
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
