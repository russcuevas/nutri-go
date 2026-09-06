@extends('layouts.app')

@section('title', 'My Orders & Live Tracking | NutriGo Lipa')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-black font-heading text-gray-900 tracking-tight">My Healthy Orders</h1>
        <p class="text-xs text-gray-500 mt-1">Track live order status, rider GPS, and meal nutrition history</p>
    </div>

    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card hover:shadow-lg transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-base font-black text-gray-900 font-heading">{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $order->created_at->format('M d, Y • h:i A') }} • From <span class="font-bold text-gray-800">{{ $order->store->store_name }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($order->status === 'pending_store')
                            <form action="{{ route('users.orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Sigurado ka bang nais mong i-cancel at i-delete ang Order #{{ $order->order_number }}? Dahil hindi pa ito tinatanggap ng restaurant, tuluyan itong mabubura.')">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-trash-can text-rose-500"></i> Cancel
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('users.orders.track', $order->order_number) }}" class="px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-location-crosshairs text-limey-400"></i> Live GPS Map
                        </a>
                    </div>
                </div>

                <!-- Items preview -->
                <div class="py-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-700">
                    <div>
                        <span class="font-bold text-gray-900">Items:</span>
                        <ul class="list-disc list-inside mt-1 text-gray-600 space-y-0.5">
                            @foreach($order->items as $item)
                                <li>{{ $item->quantity }}x {{ $item->product_name }} ({{ $item->calories }} kcal)</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="sm:text-right space-y-1">
                        <div><span class="text-gray-500">Total Calories:</span> <span class="font-bold text-nutri-800">🔥 {{ $order->total_calories }} kcal</span></div>
                        <div><span class="text-gray-500">Address:</span> <span class="font-semibold text-gray-800">{{ $order->delivery_address }}</span></div>
                        <div><span class="text-gray-500">Total Paid:</span> <span class="text-base font-black text-gray-900 font-heading">₱{{ number_format($order->total_amount, 2) }}</span></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl border border-gray-200 text-center space-y-4 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-nutri-50 text-nutri-600 text-2xl flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 font-heading">No orders placed yet</h3>
                <p class="text-xs text-gray-500">Order your first healthy meal in Lipa City now!</p>
                <a href="{{ route('users.explore') }}" class="inline-flex px-6 py-3 rounded-xl bg-nutri-900 text-white text-xs font-bold shadow-md hover:bg-nutri-800 transition">
                    Explore Healthy Stores
                </a>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
