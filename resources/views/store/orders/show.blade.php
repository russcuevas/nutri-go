@extends('layouts.store')

@section('title', 'Order #' . $order->order_number . ' | ' . $store->store_name)
@section('header_title', 'Order #' . $order->order_number)

@section('content')
    @php
        $riderFee = (float) $order->delivery_fee;
        $totalAmount = (float) $order->total_amount;
        $storeIncome = (float) $order->subtotal;
        if ($storeIncome <= 0 && $totalAmount > 0) {
            $storeIncome = max(0, $totalAmount - $riderFee);
        }
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('store.orders.index') }}"
                class="text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>

        <!-- Main Order Container -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">

            <!-- Top Header & Store Net Income Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-gray-100 gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-2xl font-black font-heading text-gray-900">{{ $order->order_number }}</h3>
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $order->payment_method === 'cash_on_delivery' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-900 border border-blue-300' }}">
                            {{ $order->payment_method === 'cash_on_delivery' ? 'COD Payment' : 'GCash Paid' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y • h:i A') }}</p>
                </div>

                <!-- Store Earnings Display -->
                <div class="sm:text-right bg-lime-50/70 border border-lime-300/80 p-3 sm:px-5 sm:py-3 rounded-2xl">
                    <span class="text-[10px] font-extrabold text-lime-900 uppercase tracking-wider block">Store Net Income
                        (Your Earnings)</span>
                    <div class="text-2xl sm:text-3xl font-black text-nutri-950 font-heading">
                        ₱{{ number_format($storeIncome, 2) }}
                    </div>
                    <span class="text-[11px] text-gray-500 font-medium block mt-0.5">
                        Total Bill: ₱{{ number_format($totalAmount, 2) }} <span
                            class="text-rose-600 font-bold">(-₱{{ number_format($riderFee, 2) }} rider fee)</span>
                    </span>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- FINANCIAL BREAKDOWN: STORE INCOME & RIDER DEDUCTION -->
            <!-- ========================================================================= -->
            <div
                class="rounded-3xl bg-gradient-to-br from-nutri-950 via-nutri-900 to-nutri-950 text-white p-5 sm:p-6 shadow-md space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-nutri-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-xl bg-limey-400 text-nutri-950 flex items-center justify-center font-black text-sm">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm font-heading tracking-tight text-white uppercase">
                                Store Income & Rider Deduction Breakdown
                            </h4>
                            <p class="text-[11px] text-nutri-200">Financial calculation of food sales retained by your store
                                for this order</p>
                        </div>
                    </div>
                    <span
                        class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-white/10 text-limey-300 border border-white/15 w-fit">
                        Store Financial Ledger
                    </span>
                </div>

                <!-- 3 Columns Financial Math Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">

                    <!-- 1. Customer Total Bill -->
                    <div class="bg-white/5 border border-white/10 p-4 rounded-2xl">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-300 block">
                            1. Customer Total Bill
                        </span>
                        <div class="text-xl sm:text-2xl font-black font-heading mt-1 text-white">
                            ₱{{ number_format($totalAmount, 2) }}
                        </div>
                        <p class="text-[11px] text-nutri-200 mt-1">Total order price including meals and delivery fee</p>
                    </div>

                    <!-- 2. Minus: Rider Delivery Fee -->
                    <div class="bg-rose-950/40 border border-rose-500/30 p-4 rounded-2xl">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-300 block">
                            2. Minus: Rider Delivery Fee
                        </span>
                        <div class="text-xl sm:text-2xl font-black font-heading mt-1 text-rose-400">
                            - ₱{{ number_format($riderFee, 2) }}
                        </div>
                        <p class="text-[11px] text-rose-200 mt-1">
                            @if ($order->rider)
                                Trip fee of {{ $order->rider->user->name }} ({{ $order->rider->plate_number ?? 'Rider' }})
                            @else
                                Direct trip fee paid to the delivery rider
                            @endif
                        </p>
                    </div>

                    <!-- 3. Store Net Income (Clean Profit) -->
                    <div class="bg-gradient-to-br from-lime-500 to-lime-600 text-nutri-950 p-4 rounded-2xl shadow-lg">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-950 block">
                            3. Store Net Income
                        </span>
                        <div class="text-2xl sm:text-3xl font-black font-heading mt-1 text-nutri-950">
                            = ₱{{ number_format($storeIncome, 2) }}
                        </div>
                        <p class="text-[11px] font-bold text-nutri-900 mt-1">
                            ✓ 100% meal sales credited to your store
                        </p>
                    </div>

                </div>

                <!-- Equation Math Strip -->
                <div
                    class="p-3 bg-white/10 rounded-xl border border-white/10 text-xs flex flex-wrap items-center justify-between gap-2 text-nutri-100">
                    <div class="flex items-center gap-2 font-mono text-[12px] flex-wrap">
                        <span class="font-bold text-white">₱{{ number_format($totalAmount, 2) }}</span> (Total)
                        <span class="text-rose-400 font-bold">-</span>
                        <span class="font-bold text-rose-300">₱{{ number_format($riderFee, 2) }}</span> (Rider Fee)
                        <span class="text-limey-400 font-bold">=</span>
                        <span class="font-black text-limey-300 text-sm">₱{{ number_format($storeIncome, 2) }} (Store Net
                            Income)</span>
                    </div>

                    @if ($order->payment_method === 'cash_on_delivery')
                        <div class="text-[11px] text-amber-200 flex items-center gap-1">
                            <i class="fa-solid fa-hand-holding-dollar text-amber-400"></i>
                            <span>COD Remittance: Rider collects cash and remits the ₱{{ number_format($storeIncome, 2) }}
                                store sales according to the 15-day schedule (15th & 30th).</span>
                        </div>
                    @else
                        {{-- <div class="text-[11px] text-blue-200 flex items-center gap-1">
                            <i class="fa-solid fa-credit-card text-blue-400"></i>
                            <span>GCash Online Payment: Customer has settled the full payment via GCash.</span>
                        </div> --}}
                    @endif
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
                    <p class="text-xs text-emerald-800">
                        <strong class="font-bold">Cash on Delivery (COD):</strong> Delivery rider will collect the total
                        customer payment of
                        <strong class="font-black text-gray-900">₱{{ number_format($order->total_amount, 2) }}</strong>
                        upon arrival.
                        The <strong class="text-rose-700">₱{{ number_format($order->delivery_fee, 2) }}</strong> delivery
                        fee belongs to the rider, while your store's food sales of
                        <strong class="text-nutri-900 font-black">₱{{ number_format($storeIncome, 2) }}</strong> will be
                        remitted to your store according to the 15-day remittance schedule (15th & 30th of each month).
                    </p>
                @endif
            </div>

            <!-- Customer & Delivery / Rider Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-gray-700">
                <div class="space-y-2">
                    <span class="font-bold text-gray-900 uppercase">Customer Information:</span>
                    <div>Name: <span class="font-bold text-gray-900">{{ $order->recipient_name }}</span></div>
                    <div>Phone: <span class="font-bold text-gray-900">{{ $order->recipient_phone }}</span></div>
                </div>
                <div class="space-y-2">
                    <span class="font-bold text-gray-900 uppercase">Delivery & Rider Information:</span>
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
                    @if ($order->rider)
                        <div class="pt-1 border-t border-gray-100">
                            <span class="font-bold text-gray-900">Assigned Rider:</span>
                            <span class="font-bold text-nutri-900">{{ $order->rider->user->name }}</span>
                            <span class="text-gray-500">({{ $order->rider->vehicle_type }} •
                                {{ $order->rider->plate_number ?? 'Rider' }})</span>
                            <span class="text-emerald-700 font-bold block text-[11px] mt-0.5">
                                • Rider Delivery Fee: ₱{{ number_format($riderFee, 2) }}
                            </span>
                        </div>
                    @else
                        <div class="text-gray-400 italic">No rider assigned yet (Will be assigned once ready).</div>
                    @endif
                    @if ($order->delivery_notes)
                        <div>Rider Notes: <span class="italic text-gray-600">"{{ $order->delivery_notes }}"</span></div>
                    @endif
                </div>
            </div>

            <!-- Ordered Food Items Table with Financial Reconciliation -->
            <div>
                <h4 class="text-xs font-bold text-gray-900 uppercase mb-3">Food Items & Pricing Breakdown:</h4>
                <div class="border rounded-2xl overflow-hidden text-xs">
                    <div class="divide-y divide-gray-100">
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

                    <!-- Itemized Financial Settlement Footer -->
                    <div class="bg-gray-100/70 border-t border-gray-200 p-4 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-gray-700">
                            <span class="font-bold">Food Items Subtotal (Store Sales):</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($storeIncome, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span class="font-bold">Less: Rider Delivery Fee:</span>
                            <span class="font-bold text-rose-600">- ₱{{ number_format($riderFee, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 text-sm">
                            <span class="font-black text-gray-900 uppercase">Customer Total Bill:</span>
                            <span
                                class="font-black font-heading text-gray-900">₱{{ number_format($totalAmount, 2) }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center pt-2 border-t border-gray-200 bg-lime-100/60 p-2.5 rounded-xl">
                            <span class="font-black text-nutri-950 uppercase text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-coins text-lime-700"></i> Store Net Income (Your Take-Home Earnings):
                            </span>
                            <span
                                class="font-black font-heading text-base text-nutri-950">₱{{ number_format($storeIncome, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons for Store -->
            @if ($order->status === 'pending_store')
                <div class="pt-4 border-t flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('store.orders.accept', $order->id) }}" method="POST" class="flex-1 w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Accept Order & Start Preparation
                        </button>
                    </form>

                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel and delete this order? (e.g. Delivery location too far). The order record will be removed.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @elseif($order->status === 'store_accepted_preparing')
                <div class="pt-4 border-t flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('store.orders.ready', $order->id) }}" method="POST" class="flex-1 w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-box"></i> Food is Packed - Mark Ready for Rider Pickup
                        </button>
                    </form>

                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel and delete this order? The order record will be removed.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @elseif(!in_array($order->status, ['delivered', 'rider_picked_up', 'on_the_way']))
                <div class="pt-4 border-t flex justify-end">
                    <form action="{{ route('store.orders.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel and delete this order? The order record will be removed.')"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-ban text-rose-600"></i> Cancel & Delete Order
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection
