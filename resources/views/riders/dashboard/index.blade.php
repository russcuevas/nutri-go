@extends('layouts.rider')

@section('title', 'Rider Dashboard & Available Trips | NutriGo Lipa')

@section('content')
<div class="space-y-6">
    
    <!-- Rider Top Stats & 3 Orders Limit Meter -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Wallet Card -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200 shadow-card">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Withdrawable Balance</span>
            <div class="text-2xl font-black text-nutri-900 font-heading mt-1">₱{{ number_format($wallet->balance, 2) }}</div>
            <a href="{{ route('riders.wallet.index') }}" class="text-[11px] font-bold text-nutri-600 hover:underline mt-1 block">
                Request GCash Cashout →
            </a>
        </div>

        <!-- Active Orders Concurrent Meter (Max 3 Active Orders) -->
        <div class="bg-white p-5 rounded-3xl border {{ $activeOrdersCount >= 3 ? 'border-rose-300 bg-rose-50/40' : 'border-lime-200 bg-lime-50/40' }} shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-700">Concurrent Trip Load</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $activeOrdersCount >= 3 ? 'bg-rose-500 text-white' : 'bg-lime-500 text-nutri-950' }}">
                    {{ $activeOrdersCount }} / 3 Active
                </span>
            </div>
            <div class="text-xl font-black font-heading text-gray-900 mt-1">
                {{ $activeOrdersCount >= 3 ? 'Trip Limit Reached' : (3 - $activeOrdersCount) . ' Trips Available' }}
            </div>
            <p class="text-[10px] text-gray-500 mt-1">Maximum 3 active orders per trip to keep food fresh.</p>
        </div>

        <!-- Completed Deliveries Today -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200 shadow-card">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Delivered Today</span>
            <div class="text-2xl font-black text-emerald-600 font-heading mt-1">{{ $todayCompletedCount }} Trips</div>
            <span class="text-[10px] text-gray-400 font-semibold">Total Career: {{ $rider->total_deliveries }}</span>
        </div>
    </div>

    <!-- Active Orders Currently Handling -->
    @if($myActiveOrders->isNotEmpty())
        <div class="bg-white p-6 rounded-3xl border border-lime-300 shadow-card space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-motorcycle text-lime-600 animate-pulse"></i> Your Active Deliveries in Progress ({{ $myActiveOrders->count() }})
                </h3>
            </div>

            <div class="space-y-3">
                @foreach($myActiveOrders as $order)
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-sm">#{{ $order->order_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <p class="text-gray-700">
                                <span class="font-bold text-gray-900">🏬 Pickup Store:</span> <span class="font-semibold">{{ $order->store->store_name }}</span> ({{ $order->store->address_line ?? $order->store->barangay }})
                            </p>
                            <p class="text-gray-700">
                                <span class="font-bold text-gray-900">👤 Deliver to:</span> <span class="font-semibold">{{ $order->recipient_name }}</span> ({{ $order->recipient_phone }})
                            </p>
                            <p class="text-gray-700">
                                <span class="font-bold text-gray-900">📍 Address:</span> <span class="font-medium text-gray-900">{{ $order->delivery_address }}</span> • <span class="font-bold text-nutri-700">{{ $order->distance_km }} km</span>
                            </p>
                            @if($order->delivery_landmark)
                                <p class="text-[11px] text-emerald-800">
                                    <span class="font-bold">🚩 Landmark / House No.:</span> {{ $order->delivery_landmark }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right text-xs mr-2">
                                <span class="text-gray-400 block">Earnings:</span>
                                <span class="text-sm font-black text-nutri-800 font-heading">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <a href="{{ route('riders.orders.active', $order->id) }}" class="px-4 py-2.5 rounded-xl bg-lime-500 hover:bg-lime-600 text-nutri-950 font-black text-xs shadow-sm transition flex items-center gap-1.5">
                                <i class="fa-solid fa-location-arrow"></i> Open GPS Navigation
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Lipa Orders Ready for Pickup -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-radar text-nutri-600"></i> Available Orders Ready for Pickup in Lipa City
                </h3>
                <p class="text-xs text-gray-500">Foods packed and ready to deliver</p>
            </div>
            <span class="text-xs text-gray-400 font-bold">{{ $availableOrders->count() }} Orders</span>
        </div>

        @if($availableOrders->isEmpty())
            <div class="p-8 text-center text-xs text-gray-400">
                <i class="fa-solid fa-satellite text-2xl text-gray-300 mb-2 block"></i>
                No orders ready for pickup at this moment. Stay tuned on radar!
            </div>
        @else
            <div class="space-y-4">
                @foreach($availableOrders as $order)
                    <div class="p-5 rounded-2xl bg-nutri-50/50 border border-nutri-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-sm">#{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-800">
                                    Ready for Pickup
                                </span>
                            </div>
                            <p class="text-gray-700">
                                <span class="font-bold text-gray-900">🏬 Store:</span> <span class="font-semibold">{{ $order->store->store_name }}</span> ({{ $order->store->address_line ?? $order->store->barangay }})
                            </p>
                            <p class="text-gray-700">
                                <span class="font-bold text-gray-900">📍 Drop-off:</span> <span class="font-medium text-gray-900">{{ $order->delivery_address }}</span> (Distance: <span class="font-bold text-nutri-700">{{ $order->distance_km }} km</span>)
                            </p>
                            @if($order->delivery_landmark)
                                <p class="text-[11px] text-emerald-800">
                                    <span class="font-bold">🚩 Landmark / House No.:</span> {{ $order->delivery_landmark }}
                                </p>
                            @endif
                            <p class="text-gray-500 text-[11px]">
                                Payment: <span class="font-semibold uppercase text-gray-800">{{ $order->payment_method }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <span class="text-[10px] text-gray-500 font-semibold block">Your Trip Pay:</span>
                                <span class="text-lg font-black text-nutri-900 font-heading">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>

                            @if($canAcceptMore)
                                <form action="{{ route('riders.orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-3 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-black text-xs shadow-md transition flex items-center gap-1.5 font-heading">
                                        <i class="fa-solid fa-check"></i> Accept Trip
                                    </button>
                                </form>
                            @else
                                <button disabled class="px-4 py-3 rounded-xl bg-gray-200 text-gray-500 font-bold text-xs cursor-not-allowed" title="Complete current 3 orders first">
                                    Max 3 Orders Reached
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
