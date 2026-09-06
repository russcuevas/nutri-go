<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\LipaLocationService;

class ProfileController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        $barangays = LipaLocationService::getBarangayList();
        $barangayCoords = LipaLocationService::$barangays;
        return view('store.profile.index', compact('store', 'barangays', 'barangayCoords'));
    }

    public function update(Request $request)
    {
        $store = Auth::user()->store;

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barangay' => 'required|string',
            'address_line' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'required|string|max:20',
            'health_category' => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'gcash_name' => 'required|string',
            'gcash_number' => 'required|string',
            'logo' => 'nullable|image|max:3072',
            'banner' => 'nullable|image|max:5120',
            'gcash_qr' => 'nullable|image|max:5120',
        ]);

        $fallbackCoords = LipaLocationService::getCoordinates($validated['barangay']);
        $latitude = !empty($validated['latitude']) ? (float) $validated['latitude'] : ($store->latitude ?? $fallbackCoords['lat']);
        $longitude = !empty($validated['longitude']) ? (float) $validated['longitude'] : ($store->longitude ?? $fallbackCoords['lng']);

        $logoPath = $store->logo;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/stores/logos');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $logoPath = 'uploads/stores/logos/' . $filename;
        } elseif ($request->boolean('remove_logo')) {
            $logoPath = null;
        }

        $bannerPath = $store->banner;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = 'banner_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/stores/banners');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $bannerPath = 'uploads/stores/banners/' . $filename;
        } elseif ($request->boolean('remove_banner')) {
            $bannerPath = null;
        }

        $gcashQrPath = $store->gcash_qr;
        if ($request->hasFile('gcash_qr')) {
            $file = $request->file('gcash_qr');
            $filename = 'gcash_qr_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/stores/gcash');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $gcashQrPath = 'uploads/stores/gcash/' . $filename;
        } elseif ($request->boolean('remove_gcash_qr')) {
            $gcashQrPath = null;
        }

        $store->update([
            'store_name' => $validated['store_name'],
            'description' => $validated['description'] ?? null,
            'barangay' => $validated['barangay'],
            'address_line' => $validated['address_line'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'phone' => $validated['phone'],
            'health_category' => $validated['health_category'],
            'opening_time' => $validated['opening_time'],
            'closing_time' => $validated['closing_time'],
            'gcash_name' => $validated['gcash_name'],
            'gcash_number' => $validated['gcash_number'],
            'logo' => $logoPath,
            'banner' => $bannerPath,
            'gcash_qr' => $gcashQrPath,
        ]);

        return back()->with('success', 'Store profile and GCash details updated successfully.');
    }
}
