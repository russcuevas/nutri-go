@extends('layouts.admin')

@section('title', 'Super Admin Overview | NutriGo Lipa')
@section('header_title', 'Platform Analytics & Overview')

@section('content')
<div class="space-y-8">
    
    <!-- Top Platform Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gross Platform GMV</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">₱{{ number_format($totalGrossSales, 2) }}</div>
            <span class="text-[10px] text-emerald-600 font-bold"><i class="fa-solid fa-chart-line mr-1"></i> {{ $deliveredOrders }} Delivered Orders</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Platform Fee Revenue</span>
                <div class="w-10 h-10 rounded-2xl bg-lime-50 text-lime-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-lime-700 font-heading mt-2">₱{{ number_format($totalPlatformFees, 2) }}</div>
            <span class="text-[10px] text-gray-400 font-semibold">Fixed ₱10 / order</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Store Vetting</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 font-heading mt-2">{{ $pendingStoresCount }}</div>
            <span class="text-[10px] text-amber-600 font-bold">Needs Healthy Vetting</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Rider Payouts</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 font-heading mt-2">₱{{ number_format($pendingPayoutsAmount, 2) }}</div>
            <span class="text-[10px] text-rose-600 font-bold">{{ $pendingPayoutsCount }} Requests Pending</span>
        </div>
    </div>

    <!-- Pending Vetting Action Queues -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Pending Stores Queue -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-store text-nutri-600"></i> Pending Store Registrations ({{ $pendingStoresCount }})
                </h3>
                <a href="{{ route('superadmin.stores.index') }}" class="text-xs font-bold text-nutri-600 hover:underline">View All</a>
            </div>

            <div class="divide-y divide-gray-100 text-xs">
                @forelse($pendingStores as $s)
                    <div class="py-3.5 flex items-center justify-between gap-3">
                        <div>
                            <span class="font-bold text-gray-900 block text-sm">{{ $s->store_name }}</span>
                            <span class="text-gray-500">Category: <span class="font-semibold text-emerald-700">{{ $s->health_category }}</span> • Brgy. {{ $s->barangay }}</span>
                        </div>
                        <a href="{{ route('superadmin.stores.index') }}" class="px-3 py-1.5 rounded-xl bg-nutri-900 text-white font-bold text-xs shadow-xs">
                            Review
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-400">No pending stores.</div>
                @endforelse
            </div>
        </div>

        <!-- Pending Riders Queue -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-motorcycle text-lime-600"></i> Pending Rider Applications ({{ $pendingRidersCount }})
                </h3>
                <a href="{{ route('superadmin.riders.index') }}" class="text-xs font-bold text-nutri-600 hover:underline">View All</a>
            </div>

            <div class="divide-y divide-gray-100 text-xs">
                @forelse($pendingRiders as $r)
                    <div class="py-3.5 flex items-center justify-between gap-3">
                        <div>
                            <span class="font-bold text-gray-900 block text-sm">{{ $r->user->name }}</span>
                            <span class="text-gray-500">{{ $r->vehicle_type }} • Plate: {{ $r->plate_number }} • Brgy. {{ $r->barangay }}</span>
                        </div>
                        <a href="{{ route('superadmin.riders.index') }}" class="px-3 py-1.5 rounded-xl bg-lime-500 text-nutri-950 font-bold text-xs shadow-xs">
                            Review
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-400">No pending riders.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Platform Orders -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <h3 class="text-base font-black font-heading text-gray-900">Recent Lipa City Orders</h3>
        <div class="divide-y divide-gray-100 text-xs">
            @foreach($recentOrders as $ro)
                <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-gray-900">{{ $ro->order_number }}</span> • {{ $ro->store->store_name }} → Brgy. {{ $ro->delivery_barangay }}
                        <span class="text-gray-400 text-[10px] block">{{ $ro->created_at->format('M d, Y • h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-black text-gray-900 font-heading">₱{{ number_format($ro->total_amount, 2) }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $ro->status_badge_class }}">
                            {{ $ro->status_label }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
