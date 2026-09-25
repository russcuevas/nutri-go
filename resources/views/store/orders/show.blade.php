@extends('layouts.store')

@section('title', 'Order #' . $order->order_number . ' | ' . $store->store_name)
@section('header_title', 'Order #' . $order->order_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('store.orders.index') }}"
                class="text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>

        <!-- Main Order Container -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-gray-100 gap-4">
                <div>
                    <h3 class="text-2xl font-black font-heading text-gray-900">{{ $order->order_number }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y • h:i A') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400">Total Order Amount</span>
                    <div class="text-2xl font-black text-nutri-900 font-heading">
                        ₱{{ number_format($order->total_amount, 2) }}</div>
                </div>
            </div>

            <!-- Payment Verification Details & Receipt Inspection -->
            <div
                class="p-5 rounded-2xl {{ $order->payment_method === 'gcash' ? 'bg-blue-50/70 border-blue-200' : 'bg-emerald-50/70 border-emerald-200' }} border space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-receipt text-blue-600"></i> Payment Details
                        ({{ strtoupper($order->payment_method) }})
                    </h4>
                    <span
                        class="text-xs font-bold uppercase {{ $order->payment_status === 'verified' ? 'text-emerald-700' : 'text-amber-700' }}">
                        Status: {{ $order->payment_status }}
                    </span>
                </div>

                @if ($order->payment_method === 'gcash')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-gray-500 font-semibold">13-Digit GCash Reference:</span>
                            <div class="text-sm font-mono font-black text-blue-900 mt-0.5">
                                {{ $order->payment_reference_no ?? 'No reference entered' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Receipt Screenshot:</span>
                            @if ($order->payment_proof_url)
                                <div class="mt-1">
                                    <a href="{{ $order->payment_proof_url }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:underline">
                                        <i class="fa-solid fa-image"></i> View Full Size Screenshot
                                    </a>
                                    <div class="w-32 h-32 rounded-xl overflow-hidden border mt-1 bg-gray-900">
                                        <img src="{{ $order->payment_proof_url }}" alt="Proof"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-400 italic mt-0.5">No screenshot attached.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-xs text-emerald-800">Cash on Delivery: Delivery rider will collect
                        ₱{{ number_format($order->total_amount, 2) }} from customer upon arrival.</p>
                @endif
            </div>

            <!-- Customer & Delivery Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-gray-700">
                <div class="space-y-2">
                    <span class="font-bold text-gray-900 uppercase">Customer Information:</span>
                    <div>Name: <span class="font-bold text-gray-900">{{ $order->recipient_name }}</span></div>
                    <div>Phone: <span class="font-bold text-gray-900">{{ $order->recipient_phone }}</span></div>
                </div>
                <div class="space-y-2">
                    <span class="font-bold text-gray-900 uppercase">Delivery Location:</span>
                    <div>Address: <span class="font-bold text-gray-900">{{ $order->delivery_address }}</span></div>

                    @if ($order->delivery_landmark)
                        <div>Landmark / House No.: <span
                                class="font-semibold text-gray-900">{{ $order->delivery_landmark }}</span></div>
                    @endif
                    @if ($order->distance_km)
                        <div>Distance: <span
                                class="font-bold text-nutri-700 bg-nutri-50 px-2 py-0.5 rounded-lg border border-nutri-200 inline-flex items-center gap-1"><i
                                    class="fa-solid fa-route text-nutri-600"></i>
                                {{ number_format($order->distance_km, 1) }} km</span></div>
                    @endif
                    @if ($order->delivery_notes)
                        <div>Rider Notes: <span class="italic text-gray-600">"{{ $order->delivery_notes }}"</span></div>
                    @endif
                </div>
            </div>

            <!-- Ordered Items Table -->
            <div>
                <h4 class="text-xs font-bold text-gray-900 uppercase mb-3">Food Items:</h4>
                <div class="border rounded-2xl overflow-hidden divide-y divide-gray-100 text-xs">
                    @foreach ($order->items as $item)
                        <div class="p-3.5 flex justify-between items-center bg-gray-50/50">
                            <div>
                                <span class="font-bold text-gray-900">{{ $item->quantity }}x
                                    {{ $item->product_name }}</span>
                                <span class="text-gray-400 text-[11px] block">🔥 {{ $item->calories }} kcal each</span>
                            </div>
                            <span class="font-bold text-gray-900">₱{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons for Store -->
            @if ($order->status === 'pending_store')
                <div class="pt-4 border-t flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('store.orders.accept', $order->id) }}" method="POST" class="flex-1 w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Accept Order & Start Preparation
                        </button>
                    </form>

                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Sigurado ka ba na gusto mong i-cancel at i-delete ang order na ito? (Halimbawa: Masyadong malayo ang delivery location). Mabubura ang order record at data nito.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @elseif($order->status === 'store_accepted_preparing')
                <div class="pt-4 border-t flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('store.orders.ready', $order->id) }}" method="POST" class="flex-1 w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-box"></i> Food is Packed - Mark Ready for Rider Pickup
                        </button>
                    </form>

                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Sigurado ka ba na gusto mong i-cancel at i-delete ang order na ito? Mabubura ang order record at data nito.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @elseif(!in_array($order->status, ['delivered', 'rider_picked_up', 'on_the_way']))
                <div class="pt-4 border-t flex justify-end">
                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Sigurado ka ba na gusto mong i-cancel at i-delete ang order na ito? Mabubura ang order record at data nito.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection
