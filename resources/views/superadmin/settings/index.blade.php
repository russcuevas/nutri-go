@extends('layouts.admin')

@section('title', 'Distance Rates & System Settings | Super Admin')
@section('header_title', 'Distance Rates & Platform Settings')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="pb-4 border-b border-gray-100">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-lime-100 text-lime-800">
            Grab / Foodpanda / Angkas Standard
        </span>
        <h3 class="text-xl font-black font-heading text-gray-900 mt-1">Point-to-Point Distance & Gas Pricing Engine</h3>
        <p class="text-xs text-gray-500">Configure how delivery fees and rider allowances are computed across Lipa City</p>
    </div>

    <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-5 text-xs">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Base Delivery Fare (₱) *</label>
                <input type="number" step="0.5" name="base_delivery_fare" required value="{{ old('base_delivery_fare', $settings['base_delivery_fare'] ?? '40.00') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                <span class="text-[10px] text-gray-400">Covers dispatch & initial booking</span>
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Base Distance (km) *</label>
                <input type="number" step="0.1" name="base_distance_km" required value="{{ old('base_distance_km', $settings['base_distance_km'] ?? '1.5') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                <span class="text-[10px] text-gray-400">Distance included in base fare</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Succeeding Gas & Travel Rate (₱/km) *</label>
                <input type="number" step="0.5" name="rate_per_km" required value="{{ old('rate_per_km', $settings['rate_per_km'] ?? '10.00') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                <span class="text-[10px] text-gray-400">Added for each km beyond base distance</span>
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Platform Maintenance Fee (₱) *</label>
                <input type="number" step="0.5" name="platform_fee" required value="{{ old('platform_fee', $settings['platform_fee'] ?? '10.00') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                <span class="text-[10px] text-gray-400">Fixed platform maintenance fee</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Max Active Orders Per Rider *</label>
                <input type="number" min="1" max="10" name="max_active_rider_orders" required value="{{ old('max_active_rider_orders', $settings['max_active_rider_orders'] ?? '3') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                <span class="text-[10px] text-gray-400">Ensures fresh food delivery</span>
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Support Hotline Phone</label>
                <input type="text" name="app_contact_phone" value="{{ old('app_contact_phone', $settings['app_contact_phone'] ?? '+63 917 123 4567') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <!-- Formula Preview Box -->
        <div class="p-4 rounded-2xl bg-nutri-50 border border-nutri-200 space-y-1.5 text-xs text-nutri-900">
            <span class="font-extrabold uppercase tracking-wider block">Live Delivery Fee Formula Preview:</span>
            <p class="font-mono text-[11px]">
                Delivery Fee = Base Fare (₱40) + max(0, Distance - 1.5 km) × ₱10.00/km
            </p>
            <p class="text-[10px] text-gray-500">
                Example: 3.5 km trip in Lipa = ₱40 + (3.5 - 1.5) × ₱10 = <strong>₱60.00</strong>
            </p>
        </div>

        <div class="pt-4 border-t">
            <button type="submit" class="w-full py-3.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-black text-xs shadow-md transition font-heading">
                Save & Update Distance Engine
            </button>
        </div>
    </form>
</div>
@endsection
