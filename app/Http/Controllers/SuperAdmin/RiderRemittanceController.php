<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Rider;
use App\Models\Order;
use App\Models\RiderRemittance;
use App\Http\Controllers\Riders\WalletPayoutController;

class RiderRemittanceController extends Controller
{
    public function index(Request $request)
    {
        $availableCycles = WalletPayoutController::getAvailableCycles(6);
        $currentCycleKey = $availableCycles[0]['key'];
        $selectedCycleKey = $request->get('cycle', $currentCycleKey);

        $selectedCycle = WalletPayoutController::parseCycleDates($selectedCycleKey);

        // Fetch all approved riders
        $riders = Rider::with(['user', 'wallet'])
            ->where('status', 'approved')
            ->orderBy('id', 'asc')
            ->get();

        // For each rider, aggregate COD delivered orders in this 15-day period
        $riderSummaries = $riders->map(function($rider) use ($selectedCycle) {
            $codOrders = Order::where('rider_id', $rider->id)
                ->where('status', 'delivered')
                ->where('payment_method', 'cash_on_delivery')
                ->where(function($q) use ($selectedCycle) {
                    $q->whereBetween('rider_delivered_at', [$selectedCycle['start'], $selectedCycle['end']])
                      ->orWhere(function($sub) use ($selectedCycle) {
                          $sub->whereNull('rider_delivered_at')
                              ->whereBetween('created_at', [$selectedCycle['start'], $selectedCycle['end']]);
                      });
                })
                ->with('store')
                ->get();

            $totalCash = (float) $codOrders->sum('total_amount');
            $deliveryFees = (float) $codOrders->sum('delivery_fee');
            $storeSalesToRemit = (float) $codOrders->sum('subtotal');
            if ($storeSalesToRemit <= 0 && $totalCash > 0) {
                $storeSalesToRemit = max(0, $totalCash - $deliveryFees);
            }

            // Group by store
            $storeBreakdown = $codOrders->groupBy('store_id')->map(function($orders, $storeId) {
                $store = $orders->first()->store;
                $sales = (float) $orders->sum('subtotal');
                $fees = (float) $orders->sum('delivery_fee');
                $cash = (float) $orders->sum('total_amount');
                if ($sales <= 0 && $cash > 0) {
                    $sales = max(0, $cash - $fees);
                }

                return [
                    'store_id' => $storeId,
                    'store_name' => $store ? $store->store_name : "Store #{$storeId}",
                    'store_barangay' => $store ? $store->barangay : 'Lipa City',
                    'orders_count' => $orders->count(),
                    'total_sales' => $sales,
                    'delivery_fees' => $fees,
                    'total_cash_collected' => $cash,
                ];
            })->values();

            // Fetch remittance record if table exists
            $remittance = null;
            try {
                if (Schema::hasTable('rider_remittances')) {
                    $remittance = RiderRemittance::where('rider_id', $rider->id)
                        ->where('cycle_key', $selectedCycle['key'])
                        ->first();
                }
            } catch (\Throwable $e) {
                $remittance = null;
            }

            return [
                'rider' => $rider,
                'orders_count' => $codOrders->count(),
                'total_cash_collected' => $totalCash,
                'rider_delivery_fees' => $deliveryFees,
                'total_money_to_remit' => $storeSalesToRemit,
                'store_breakdown' => $storeBreakdown,
                'remittance' => $remittance,
            ];
        });

        // Filter out riders with 0 activity if requested, or keep all
        $showAll = $request->boolean('all_riders', false);
        $activeSummaries = $showAll 
            ? $riderSummaries 
            : $riderSummaries->filter(fn($s) => $s['orders_count'] > 0 || $s['remittance'] !== null);

        // Overall stats for this cycle
        $cycleTotalToRemit = $riderSummaries->sum('total_money_to_remit');
        $cycleTotalCollected = $riderSummaries->sum('total_cash_collected');
        $cycleTotalFees = $riderSummaries->sum('rider_delivery_fees');
        $cycleDeliveredOrders = $riderSummaries->sum('orders_count');

        // Submitted remittance counts
        $pendingCount = 0;
        $verifiedCount = 0;
        try {
            if (Schema::hasTable('rider_remittances')) {
                $pendingCount = RiderRemittance::where('cycle_key', $selectedCycle['key'])->where('status', 'pending')->count();
                $verifiedCount = RiderRemittance::where('cycle_key', $selectedCycle['key'])->where('status', 'verified')->count();
            }
        } catch (\Throwable $e) {}

        return view('superadmin.remittances.index', compact(
            'availableCycles',
            'selectedCycle',
            'activeSummaries',
            'riderSummaries',
            'cycleTotalToRemit',
            'cycleTotalCollected',
            'cycleTotalFees',
            'cycleDeliveredOrders',
            'pendingCount',
            'verifiedCount',
            'showAll'
        ));
    }

    public function verify(Request $request, int $id)
    {
        $remittance = RiderRemittance::with('rider.user')->findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $remittance->update([
            'status' => 'verified',
            'admin_notes' => $validated['admin_notes'] ?? 'Remittance verified and reconciled by Super Admin.',
            'verified_at' => now(),
        ]);

        return back()->with('success', "Remittance of ₱" . number_format($remittance->total_remit_amount, 2) . " from {$remittance->rider->user->name} has been VERIFIED and marked as Settled!");
    }

    public function reject(Request $request, int $id)
    {
        $remittance = RiderRemittance::with('rider.user')->findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $remittance->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'verified_at' => now(),
        ]);

        return back()->with('warning', "Remittance from {$remittance->rider->user->name} marked as REJECTED. (Reason: {$validated['admin_notes']})");
    }
}

