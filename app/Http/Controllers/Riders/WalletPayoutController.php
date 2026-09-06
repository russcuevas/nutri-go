<?php

namespace App\Http\Controllers\Riders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RiderWallet;
use App\Models\RiderWalletTransaction;
use App\Models\RiderPayoutRequest;

class WalletPayoutController extends Controller
{
    public function index()
    {
        $rider = Auth::user()->rider;
        $wallet = RiderWallet::firstOrCreate(['rider_id' => $rider->id]);

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
                        'rider_proof_image' => $tx->order->rider_proof_image,
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

        $payoutRequests = RiderPayoutRequest::where('rider_id', $rider->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riders.wallet.index', compact('rider', 'wallet', 'transactions', 'payoutRequests'));
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
