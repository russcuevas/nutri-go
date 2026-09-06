@extends('layouts.store')

@section('title', 'Store Dashboard | ' . $store->store_name)
@section('header_title', $store->store_name . ' Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gross Sales</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-peso-sign"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">₱{{ number_format($totalRevenue, 2) }}</div>
            <span class="text-[10px] text-emerald-600 font-bold"><i class="fa-solid fa-check mr-1"></i> From Delivered Orders</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending GCash Review</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 font-heading mt-2">{{ $pendingOrders->count() }}</div>
            <span class="text-[10px] text-amber-600 font-bold">Needs Store Verification</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Active in Kitchen</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-blue-600 font-heading mt-2">{{ $activeOrders->count() }}</div>
            <span class="text-[10px] text-blue-600 font-bold">Preparing / Awaiting Pickup</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Store Health Rating</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">{{ number_format($store->rating, 2) }}</div>
            <span class="text-[10px] text-gray-400 font-semibold">{{ $store->total_reviews }} Verified Reviews</span>
        </div>
    </div>

    <!-- Urgent Pending Orders with Payment Verification Modal -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-amber-200 shadow-card space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800">Action Required</span>
                <h3 class="text-xl font-black font-heading text-gray-900 mt-1">Pending Orders Awaiting Payment Confirmation</h3>
            </div>
            <span class="text-xs text-gray-400 font-semibold">{{ $pendingOrders->count() }} Orders</span>
        </div>

        @if($pendingOrders->isEmpty())
            <div class="p-8 text-center text-xs text-gray-400">
                <i class="fa-solid fa-circle-check text-2xl text-emerald-400 mb-2 block"></i>
                All pending orders verified! Your order queue is clean.
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                    <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-200 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ openModal: false, openDecline: false }">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-gray-900 text-sm font-heading">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                                    {{ $order->payment_method }}
                                </span>
                                <span class="text-[10px] text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
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

                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Inspect Payment Proof Button -->
                            @if($order->payment_proof_url || $order->payment_reference_no)
                                <button @click="openModal = true" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-receipt"></i> Inspect GCash Proof
                                </button>
                            @endif

                            <!-- Accept Order -->
                            <form action="{{ route('store.orders.accept', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-check"></i> Accept & Cook
                                </button>
                            </form>

                            <!-- Decline Button -->
                            <button @click="openDecline = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition">
                                Decline
                            </button>
                        </div>

                        <!-- GCash Inspection Modal -->
                        <div x-show="openModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                            <div @click.outside="openModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-gray-100 space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                    <h4 class="font-black font-heading text-base text-gray-900">GCash Payment Proof Verification</h4>
                                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                                </div>

                                <div class="space-y-2 text-xs">
                                    <div class="p-3 rounded-xl bg-blue-50 text-blue-950 font-mono font-bold">
                                        Reference No: {{ $order->payment_reference_no ?? 'No ref number' }}
                                    </div>
                                    <div class="text-gray-600">Expected Total Amount: <span class="font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</span></div>
                                    
                                    @if($order->payment_proof_url)
                                        <div class="rounded-xl overflow-hidden border bg-gray-900 max-h-72">
                                            <img src="{{ $order->payment_proof_url }}" alt="Proof Screenshot" class="w-full h-full object-contain">
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">No image receipt attached.</p>
                                    @endif
                                </div>

                                <div class="pt-3 flex gap-2">
                                    <form action="{{ route('store.orders.accept', $order->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs">
                                            Verify & Accept Order
                                        </button>
                                    </form>
                                    <button @click="openModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Decline Reason Modal -->
                        <div x-show="openDecline" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                            <div @click.outside="openDecline = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                                <h4 class="font-black font-heading text-base text-gray-900">Decline Order #{{ $order->order_number }}</h4>
                                <form action="{{ route('store.orders.decline', $order->id) }}" method="POST" class="space-y-3 text-xs">
                                    @csrf
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-1">Reason for declining:</label>
                                        <textarea name="store_decline_reason" required rows="3" class="w-full p-3 rounded-xl border border-gray-200 text-xs" placeholder="e.g. Invalid GCash reference number, ingredients out of stock..."></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white font-bold">Decline Order</button>
                                        <button type="button" @click="openDecline = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Active Orders Preparing & Ready for Pickup -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
        <h3 class="text-xl font-black font-heading text-gray-900">Active Orders In Kitchen & Dispatch</h3>

        @if($activeOrders->isEmpty())
            <div class="p-8 text-center text-xs text-gray-400">No active kitchen orders right now.</div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($activeOrders as $order)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-sm">{{ $order->order_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-800">
                                <span class="font-bold text-gray-900"><i class="fa-solid fa-user text-gray-400 mr-1"></i> Customer:</span> <span class="font-semibold text-gray-800">{{ $order->recipient_name }}</span>
                            </p>
                            <p class="text-xs text-gray-700">
                                <span class="font-bold text-gray-900"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> Address:</span> <span class="font-medium text-gray-800">{{ $order->delivery_address }}</span>
                            </p>
                            @if($order->delivery_landmark)
                                <p class="text-[11px] text-emerald-800">
                                    <span class="font-bold"><i class="fa-solid fa-map-pin text-emerald-600 mr-1"></i> Landmark / House No.:</span> {{ $order->delivery_landmark }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if($order->status === 'store_accepted_preparing')
                                <form action="{{ route('store.orders.ready', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-box"></i> Mark Ready for Rider Pickup
                                    </button>
                                </form>
                            @elseif($order->rider)
                                <div class="text-right text-xs">
                                    <span class="text-gray-400">Assigned Rider:</span>
                                    <span class="font-bold text-gray-900 block">{{ $order->rider->user->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
