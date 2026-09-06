<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rider;
use App\Models\RiderWallet;

class RiderApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $ridersQuery = Rider::with(['user', 'wallet'])->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $ridersQuery->where('status', $status);
        }

        $riders = $ridersQuery->paginate(15)->withQueryString();

        $counts = [
            'all' => Rider::count(),
            'pending' => Rider::where('status', 'pending')->count(),
            'approved' => Rider::where('status', 'approved')->count(),
            'rejected' => Rider::where('status', 'rejected')->count(),
        ];

        return view('superadmin.riders.index', compact('riders', 'status', 'counts'));
    }

    public function approve(int $id)
    {
        $rider = Rider::findOrFail($id);

        $rider->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        RiderWallet::firstOrCreate(['rider_id' => $rider->id]);

        return back()->with('success', "Rider '{$rider->user->name}' has been APPROVED for NutriGo delivery fleet!");
    }

    public function reject(Request $request, int $id)
    {
        $rider = Rider::findOrFail($id);
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $rider->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('info', "Rider application for '{$rider->user->name}' rejected.");
    }
}
