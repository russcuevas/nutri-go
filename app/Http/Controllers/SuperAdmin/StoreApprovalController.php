<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $storesQuery = Store::with(['user', 'products'])->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $storesQuery->where('status', $status);
        }

        $stores = $storesQuery->paginate(15)->withQueryString();

        $counts = [
            'all' => Store::count(),
            'pending' => Store::where('status', 'pending')->count(),
            'approved' => Store::where('status', 'approved')->count(),
            'rejected' => Store::where('status', 'rejected')->count(),
        ];

        return view('superadmin.stores.index', compact('stores', 'status', 'counts'));
    }

    public function approve(Request $request, int $id)
    {
        $store = Store::findOrFail($id);
        $commission = (float) $request->input('commission_percent', 10.00);

        $store->update([
            'status' => 'approved',
            'commission_percent' => $commission,
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Healthy Store '{$store->store_name}' has been APPROVED for NutriGo Lipa!");
    }

    public function reject(Request $request, int $id)
    {
        $store = Store::findOrFail($id);
        $storeName = $store->store_name;
        $user = $store->user;

        // Clean up uploaded files if present
        if ($store->logo && file_exists(public_path($store->logo))) {
            @unlink(public_path($store->logo));
        }
        if ($store->gcash_qr && file_exists(public_path($store->gcash_qr))) {
            @unlink(public_path($store->gcash_qr));
        }

        // Deleting user will cascade delete the store and products
        if ($user) {
            $user->delete();
        } else {
            $store->delete();
        }

        return back()->with('info', "Store '{$storeName}' has been rejected and permanently deleted from the system.");
    }
}
