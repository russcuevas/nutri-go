@extends('layouts.admin')

@section('title', 'VIP Subscription Plans | Super Admin')
@section('header_title', 'VIP Plans & Subscribers')

@section('content')
<div class="space-y-8" x-data="{ openPlanModal: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">VIP Subscription Plans</h3>
            <p class="text-xs text-gray-500">Manage VIP tiers, pricing, and perks for healthy subscribers</p>
        </div>
        <button @click="openPlanModal = true" class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-500 text-nutri-950 font-black text-xs shadow-sm transition flex items-center gap-1.5 font-heading">
            <i class="fa-solid fa-crown"></i> Create Subscription Plan
        </button>
    </div>

    <!-- Active Plans Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-base font-black text-gray-900 font-heading">{{ $plan->name }}</h4>
                    <span class="text-xs font-black text-nutri-800">₱{{ number_format($plan->price, 0) }}/{{ $plan->billing_period }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ $plan->description }}</p>
                <div class="text-[11px] font-bold text-gray-600">
                    {{ $plan->subscriptions_count }} Active Subscribers
                </div>
            </div>
        @endforeach
    </div>

    <!-- Subscribers History Table -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <h4 class="text-sm font-black font-heading uppercase tracking-wider text-gray-900">Recent Subscribers</h4>
        <div class="divide-y divide-gray-100 text-xs">
            @forelse($subscribers as $sub)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-gray-900">{{ $sub->user->name }}</span> ({{ $sub->user->email }})
                        <span class="text-gray-400 text-[10px] block">Plan: {{ $sub->plan->name }} • Ref: {{ $sub->payment_reference ?? 'N/A' }}</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                        {{ $sub->status }}
                    </span>
                </div>
            @empty
                <div class="py-6 text-center text-xs text-gray-400">No subscribers yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Add Plan Modal -->
    <div x-show="openPlanModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="openPlanModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 text-xs">
            <h4 class="font-black font-heading text-base text-gray-900">Add Subscription Plan</h4>
            <form action="{{ route('superadmin.subscriptions.plan.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Plan Name *</label>
                    <input type="text" name="name" required placeholder="e.g. NutriGo VIP Monthly" class="w-full px-3 py-2 rounded-xl border">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Price (₱) *</label>
                        <input type="number" name="price" required placeholder="299" class="w-full px-3 py-2 rounded-xl border">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Billing Period *</label>
                        <select name="billing_period" class="w-full px-3 py-2 rounded-xl border bg-white">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Badge Tag</label>
                    <input type="text" name="badge" placeholder="e.g. Most Popular" class="w-full px-3 py-2 rounded-xl border">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Perks (1 per line)</label>
                    <textarea name="perks" rows="3" class="w-full p-2.5 rounded-xl border" placeholder="Free delivery on 5 orders&#10;Access to VIP cooking masterclasses&#10;15% off supplements"></textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-amber-400 text-nutri-950 font-black font-heading">Save Plan</button>
                    <button type="button" @click="openPlanModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
