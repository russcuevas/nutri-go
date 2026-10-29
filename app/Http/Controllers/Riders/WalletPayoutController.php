<?php

namespace App\Http\Controllers\Riders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\RiderWallet;
use App\Models\RiderWalletTransaction;
use App\Models\RiderPayoutRequest;
use App\Models\RiderRemittance;

class WalletPayoutController extends Controller
{
    /**
     * Helper to compute start and end dates for a semi-monthly (15-day) cycle.
     */
    public static function parseCycleDates(string $cycleKey): array
    {
        // Expecting format: YYYY-MM-1 or YYYY-MM-2 (e.g., 2026-10-1 or 2026-10-2)
        $parts = explode('-', $cycleKey);
        if (count($parts) === 3) {
            $year = (int) $parts[0];
            $month = (int) $parts[1];
            $half = (int) $parts[2];
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            $half = $now->day <= 15 ? 1 : 2;
        }

        if ($half === 1) {
            $start = Carbon::create($year, $month, 1, 0, 0, 0);
            $end = Carbon::create($year, $month, 15, 23, 59, 59);
            $label = $start->format('M 01') . ' – ' . $end->format('M 15, Y') . ' (1st Half)';
        } else {
            $start = Carbon::create($year, $month, 16, 0, 0, 0);
            $end = Carbon::create($year, $month, 16)->endOfMonth();
            $label = $start->format('M 16') . ' – ' . $end->format('M d, Y') . ' (2nd Half)';
        }

        return [
            'key' => "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-{$half}",
            'start' => $start,
            'end' => $end,
            'label' => $label,
            'half' => $half,
            'month_name' => $start->format('F Y'),
        ];
    }

    /**
     * Generate available semi-monthly cycles (Current + 5 previous cycles)
     */
    public static function getAvailableCycles(int $count = 6): array
    {
        $cycles = [];
        $cursor = Carbon::now();

        for ($i = 0; $i < $count; $i++) {
            $half = $cursor->day <= 15 ? 1 : 2;
            $cycleKey = $cursor->format('Y-m') . '-' . $half;
            $info = self::parseCycleDates($cycleKey);
            $info['is_current'] = ($i === 0);
            $cycles[] = $info;

            // Step back half a month
            if ($half === 2) {
                $cursor = $cursor->copy()->startOfMonth()->addDays(10); // inside first half
            } else {
                $cursor = $cursor->copy()->subMonth()->endOfMonth(); // inside second half of prev month
            }
        }

        return $cycles;
    }

    public function index(Request $request)
    {
        $rider = Auth::user()->rider;
        $wallet = RiderWallet::firstOrCreate(['rider_id' => $rider->id]);

        // 1. Available 15-day semi-monthly cycles
        $availableCycles = self::getAvailableCycles(6);
        $currentCycleKey = $availableCycles[0]['key'];
        $selectedCycleKey = $request->get('cycle', $currentCycleKey);

        $selectedCycle = self::parseCycleDates($selectedCycleKey);

        // 2. Fetch Delivered COD orders completed by this rider during the 15-day period
        $periodOrders = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->where(function($q) use ($selectedCycle) {
                $q->whereBetween('rider_delivered_at', [$selectedCycle['start'], $selectedCycle['end']])
                  ->orWhere(function($sub) use ($selectedCycle) {
                      $sub->whereNull('rider_delivered_at')
                          ->whereBetween('created_at', [$selectedCycle['start'], $selectedCycle['end']]);
                  });
            })
            ->with(['store', 'items'])
            ->orderBy('rider_delivered_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Separate COD orders (requires cash remittance) vs Non-COD (e.g. GCash paid directly)
        $codOrders = $periodOrders->where('payment_method', 'cash_on_delivery');

        // Total cash collected from customers by rider for COD orders
        $totalCashCollected = (float) $codOrders->sum('total_amount');

        // Rider delivery fee earned (already credited to rider wallet upon delivery)
        $periodDeliveryFees = (float) $codOrders->sum('delivery_fee');

        // Total money rider must remit to Super Admin (sum of store food sales / subtotal)
        $totalMoneyToRemit = (float) $codOrders->sum('subtotal');
        if ($totalMoneyToRemit <= 0 && $totalCashCollected > 0) {
            $totalMoneyToRemit = max(0, $totalCashCollected - $periodDeliveryFees);
        }

        // Each store total sales breakdown
        $eachStoreSales = $codOrders->groupBy('store_id')->map(function($orders, $storeId) {
            $store = $orders->first()->store;
            $storeTotalSales = (float) $orders->sum('subtotal');
            $storeDeliveryFees = (float) $orders->sum('delivery_fee');
            $storeCashCollected = (float) $orders->sum('total_amount');

            if ($storeTotalSales <= 0 && $storeCashCollected > 0) {
                $storeTotalSales = max(0, $storeCashCollected - $storeDeliveryFees);
            }

            return [
                'store_id' => $storeId,
                'store_name' => $store ? $store->store_name : "Store #{$storeId}",
                'store_barangay' => $store ? $store->barangay : 'Lipa City',
                'store_phone' => $store ? $store->phone : null,
                'orders_count' => $orders->count(),
                'total_sales' => $storeTotalSales,
                'delivery_fees' => $storeDeliveryFees,
                'total_cash_collected' => $storeCashCollected,
            ];
        })->values();

        // 3. Remittance Record (if submitted for this cycle)
        $remittanceRecord = null;
        try {
            if (Schema::hasTable('rider_remittances')) {
                $remittanceRecord = RiderRemittance::where('rider_id', $rider->id)
                    ->where('cycle_key', $selectedCycle['key'])
                    ->first();
            }
        } catch (\Throwable $e) {
            // Graceful fallback if migration not yet run
            $remittanceRecord = null;
        }

        // 4. Wallet Transactions Ledger
        $transactions = RiderWalletTransaction::where('rider_id', $rider->id)
            ->with(['order.store', 'order.items.product', 'order.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($tx) {
                return [
                    'id' => $tx->id,
                    'amount' => (float) $tx->amount,
                    'type' => $tx->type,
                    'description' => $tx->description,
                    'created_at_formatted' => $tx->created_at->format('M d, Y • h:i A'),
                    'order' => $tx->order ? [
                        'id' => $tx->order->id,
                        'order_number' => $tx->order->order_number,
                        'recipient_name' => $tx->order->recipient_name,
                        'recipient_phone' => $tx->order->recipient_phone,
                        'delivery_address' => $tx->order->delivery_address,
                        'delivery_landmark' => $tx->order->delivery_landmark,
                        'delivery_notes' => $tx->order->delivery_notes,
                        'distance_km' => $tx->order->distance_km,
                        'total_amount' => (float) $tx->order->total_amount,
                        'delivery_fee' => (float) $tx->order->delivery_fee,
                        'rider_proof_image' => $tx->order->rider_proof_url,
                        'store' => $tx->order->store ? [
                            'store_name' => $tx->order->store->store_name,
                            'address_line' => $tx->order->store->address_line,
                            'barangay' => $tx->order->store->barangay,
                            'phone' => $tx->order->store->phone,
                        ] : null,
                        'items' => $tx->order->items->map(function($item) {
                            return [
                                'id' => $item->id,
                                'product_name' => $item->product_name,
                                'calories' => $item->calories,
                                'quantity' => (int) $item->quantity,
                                'price' => (float) $item->price,
                                'subtotal' => (float) $item->subtotal,
                            ];
                        })->values(),
                    ] : null,
                ];
            });

        // 5. Payout Requests (GCash Cashouts)
        $payoutRequests = RiderPayoutRequest::where('rider_id', $rider->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riders.wallet.index', compact(
            'rider',
            'wallet',
            'transactions',
            'payoutRequests',
            'availableCycles',
            'selectedCycle',
            'codOrders',
            'totalCashCollected',
            'periodDeliveryFees',
            'totalMoneyToRemit',
            'eachStoreSales',
            'remittanceRecord'
        ));
    }

    /**
     * Submit Proof of Remittance to Super Admin for the 15-day period
     */
    public function submitRemittance(Request $request)
    {
        $rider = Auth::user()->rider;

        $validated = $request->validate([
            'cycle_key' => 'required|string',
            'payment_method' => 'required|string|in:gcash,bank_transfer,office_cash',
            'reference_no' => 'required|string|max:100',
            'proof_image' => 'required|image|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $cycleInfo = self::parseCycleDates($validated['cycle_key']);

        // Recalculate amounts to ensure data integrity
        $codOrders = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->where('payment_method', 'cash_on_delivery')
            ->where(function($q) use ($cycleInfo) {
                $q->whereBetween('rider_delivered_at', [$cycleInfo['start'], $cycleInfo['end']])
                  ->orWhere(function($sub) use ($cycleInfo) {
                      $sub->whereNull('rider_delivered_at')
                          ->whereBetween('created_at', [$cycleInfo['start'], $cycleInfo['end']]);
                  });
            })
            ->get();

        $totalCash = (float) $codOrders->sum('total_amount');
        $deliveryFees = (float) $codOrders->sum('delivery_fee');
        $storeSalesToRemit = (float) $codOrders->sum('subtotal');
        if ($storeSalesToRemit <= 0 && $totalCash > 0) {
            $storeSalesToRemit = max(0, $totalCash - $deliveryFees);
        }

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = 'remittance_' . $rider->id . '_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/remittances/proofs');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $proofPath = 'images/remittances/proofs/' . $filename;
        }

        $remittance = RiderRemittance::updateOrCreate(
            [
                'rider_id' => $rider->id,
                'cycle_key' => $validated['cycle_key'],
            ],
            [
                'period_start' => $cycleInfo['start']->toDateString(),
                'period_end' => $cycleInfo['end']->toDateString(),
                'total_collected_cash' => $totalCash,
                'total_remit_amount' => $storeSalesToRemit,
                'rider_delivery_fees' => $deliveryFees,
                'orders_count' => $codOrders->count(),
                'payment_method' => $validated['payment_method'],
                'reference_no' => $validated['reference_no'],
                'proof_image' => $proofPath,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'remitted_at' => now(),
            ]
        );

        return back()->with('success', "Remittance proof for period {$cycleInfo['label']} (₱" . number_format($storeSalesToRemit, 2) . ") has been submitted to Super Admin for verification!");
    }

    public function requestPayout(Request $request)
    {
        $rider = Auth::user()->rider;
        $wallet = RiderWallet::firstOrCreate(['rider_id' => $rider->id]);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100|max:' . $wallet->balance,
            'gcash_number' => 'required|string|max:20',
            'gcash_account_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $amount = (float) $validated['amount'];

        if ($amount > $wallet->balance) {
            return back()->with('error', 'Requested amount exceeds your available wallet balance.');
        }

        // Deduct from active balance and add to pending payout
        $wallet->decrement('balance', $amount);
        $wallet->increment('pending_payout', $amount);

        RiderPayoutRequest::create([
            'rider_id' => $rider->id,
            'amount' => $amount,
            'gcash_number' => $validated['gcash_number'],
            'gcash_account_name' => $validated['gcash_account_name'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'requested_at' => now(),
        ]);

        RiderWalletTransaction::create([
            'rider_id' => $rider->id,
            'type' => 'payout_deduction',
            'amount' => -$amount,
            'description' => "GCash Payout Request to {$validated['gcash_number']} ({$validated['gcash_account_name']})",
        ]);

        return back()->with('success', "Payout request for ₱{$amount} submitted! Admin will disburse the funds to your GCash.");
    }
}
