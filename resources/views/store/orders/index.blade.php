@extends('layouts.store')

@section('title', 'Store Orders Queue | ' . $store->store_name)
@section('header_title', 'Orders & GCash Verification')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">All Store Orders</h3>
            <p class="text-xs text-gray-500">Filter orders by preparation and delivery status</p>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto text-xs font-bold">
            <a href="{{ route('store.orders.index') }}" class="px-3 py-1.5 rounded-xl {{ empty($statusFilter) ? 'bg-nutri-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
            <a href="{{ route('store.orders.index') }}?status=pending_store" class="px-3 py-1.5 rounded-xl {{ $statusFilter == 'pending_store' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Pending GCash</a>
            <a href="{{ route('store.orders.index') }}?status=store_accepted_preparing" class="px-3 py-1.5 rounded-xl {{ $statusFilter == 'store_accepted_preparing' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Preparing</a>
            <a href="{{ route('store.orders.index') }}?status=ready_for_pickup" class="px-3 py-1.5 rounded-xl {{ $statusFilter == 'ready_for_pickup' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Ready for Rider</a>
            <a href="{{ route('store.orders.index') }}?status=delivered" class="px-3 py-1.5 rounded-xl {{ $statusFilter == 'delivered' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Delivered</a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($orders as $order)
            <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900 text-sm">{{ $order->order_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                        <span class="text-[10px] text-gray-400">{{ $order->created_at->format('M d, Y • h:i A') }}</span>
                    </div>
                    <p class="text-xs text-gray-800">
                        <span class="font-bold text-gray-900"><i class="fa-solid fa-user text-gray-400 mr-1"></i> Customer:</span> <span class="font-semibold">{{ $order->recipient_name }}</span> ({{ $order->recipient_phone }})
                    </p>
                    <p class="text-xs text-gray-700">
                        <span class="font-bold text-gray-900"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> Address:</span> <span class="font-medium text-gray-900">{{ $order->delivery_address }}</span>
                    </p>
                    @if($order->delivery_landmark)
                        <p class="text-[11px] text-emerald-800">
                            <span class="font-bold"><i class="fa-solid fa-map-pin text-emerald-600 mr-1"></i> Landmark / House No.:</span> {{ $order->delivery_landmark }}
                        </p>
                    @endif
                    <p class="text-xs text-gray-500 pt-0.5">
                        <span class="font-bold text-gray-700">Items:</span> {{ $order->items->pluck('product_name')->implode(', ') }} • <span class="font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('store.orders.show', $order->id) }}" class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition">
                        View Details & Receipt
                    </a>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-xs text-gray-400">No orders found in this category.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
