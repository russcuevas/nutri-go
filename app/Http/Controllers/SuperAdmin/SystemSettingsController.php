<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        return view('superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_delivery_fare' => 'required|numeric|min:0',
            'base_distance_km' => 'required|numeric|min:0.5',
            'rate_per_km' => 'required|numeric|min:0',
            'platform_fee' => 'required|numeric|min:0',
            'max_active_rider_orders' => 'required|integer|min:1|max:10',
            'app_contact_phone' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, (string) $value);
        }

        return back()->with('success', 'Distance pricing & delivery settings updated successfully!');
    }
}
