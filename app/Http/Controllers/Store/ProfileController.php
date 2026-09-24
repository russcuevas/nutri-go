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
            'business_permit_no' => 'nullable|string|max:100',
            'health_certificate' => 'nullable|string|max:100',
            'business_registration_number' => 'nullable|string|max:100',
            'tax_identification_number' => 'nullable|string|max:100',
            'business_establishment_date' => 'nullable|date|before_or_equal:today',
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
            if ($store->logo && file_exists(public_path($store->logo))) {
                @unlink(public_path($store->logo));
            }
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/stores/logos');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $logoPath = 'images/stores/logos/' . $filename;
        } elseif ($request->boolean('remove_logo')) {
            if ($store->logo && file_exists(public_path($store->logo))) {
                @unlink(public_path($store->logo));
            }
            $logoPath = null;
        }

        $bannerPath = $store->banner;
        if ($request->hasFile('banner')) {
            if ($store->banner && file_exists(public_path($store->banner))) {
                @unlink(public_path($store->banner));
            }
            $file = $request->file('banner');
            $filename = 'banner_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/stores/banners');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $bannerPath = 'images/stores/banners/' . $filename;
        } elseif ($request->boolean('remove_banner')) {
            if ($store->banner && file_exists(public_path($store->banner))) {
                @unlink(public_path($store->banner));
            }
            $bannerPath = null;
        }

        $gcashQrPath = $store->gcash_qr;
        if ($request->hasFile('gcash_qr')) {
            if ($store->gcash_qr && file_exists(public_path($store->gcash_qr))) {
                @unlink(public_path($store->gcash_qr));
            }
            $file = $request->file('gcash_qr');
            $filename = 'gcash_qr_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/stores/gcash');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $gcashQrPath = 'images/stores/gcash/' . $filename;
        } elseif ($request->boolean('remove_gcash_qr')) {
            if ($store->gcash_qr && file_exists(public_path($store->gcash_qr))) {
                @unlink(public_path($store->gcash_qr));
            }
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
            'business_permit_no' => $validated['business_permit_no'] ?? null,
            'health_certificate' => $validated['health_certificate'] ?? null,
            'business_registration_number' => $validated['business_registration_number'] ?? null,
            'tax_identification_number' => $validated['tax_identification_number'] ?? null,
            'business_establishment_date' => $validated['business_establishment_date'] ?? null,
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
