<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiderPayoutRequest;
use App\Models\RiderWallet;
use App\Models\RiderWalletTransaction;

class RiderPayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $payoutsQuery = RiderPayoutRequest::with(['rider.user', 'rider.wallet'])->orderBy('requested_at', 'desc');

        if ($status !== 'all') {
            $payoutsQuery->where('status', $status);
        }

        $payouts = $payoutsQuery->paginate(15)->withQueryString();

        $counts = [
            'all' => RiderPayoutRequest::count(),
            'pending' => RiderPayoutRequest::where('status', 'pending')->count(),
            'completed' => RiderPayoutRequest::where('status', 'completed')->count(),
            'rejected' => RiderPayoutRequest::where('status', 'rejected')->count(),
        ];

        return view('superadmin.payouts.index', compact('payouts', 'status', 'counts'));
    }

    public function process(Request $request, int $id)
    {
        $payout = RiderPayoutRequest::with('rider.wallet')->findOrFail($id);

        $validated = $request->validate([
            'admin_reference_no' => 'required|string|max:100',
            'proof_image' => 'nullable|image|max:3072',
            'notes' => 'nullable|string|max:500',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = 'proof_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/payouts/proofs');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $proofPath = 'images/payouts/proofs/' . $filename;
        }

        $wallet = $payout->rider->wallet;
        if ($wallet) {
            $wallet->decrement('pending_payout', $payout->amount);
        }

        $payout->update([
            'status' => 'completed',
            'admin_reference_no' => $validated['admin_reference_no'],
            'proof_image' => $proofPath,
            'notes' => $validated['notes'] ?? null,
            'processed_at' => now(),
        ]);

        return back()->with('success', "GCash Payout of ₱{$payout->amount} to {$payout->gcash_account_name} marked as COMPLETED! (Ref #{$validated['admin_reference_no']})");
    }

    public function reject(Request $request, int $id)
    {
        $payout = RiderPayoutRequest::with('rider.wallet')->findOrFail($id);

        $validated = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        // Refund pending payout back to active balance
        $wallet = $payout->rider->wallet;
        if ($wallet) {
            $wallet->decrement('pending_payout', $payout->amount);
            $wallet->increment('balance', $payout->amount);

            RiderWalletTransaction::create([
                'rider_id' => $payout->rider_id,
                'type' => 'adjustment',
                'amount' => $payout->amount,
                'description' => "Refund for Rejected GCash Payout (Reason: {$validated['notes']})",
            ]);
        }

        $payout->update([
            'status' => 'rejected',
            'notes' => $validated['notes'],
            'processed_at' => now(),
        ]);

        return back()->with('info', "Payout request rejected and ₱{$payout->amount} refunded to rider wallet.");
    }
}
