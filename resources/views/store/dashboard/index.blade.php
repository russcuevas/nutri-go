@extends('layouts.store')

@section('title', 'Store Dashboard | ' . $store->store_name)
@section('header_title', $store->store_name . ' Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gross Sales</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-peso-sign"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">₱{{ number_format($totalRevenue, 2) }}</div>
            <div class="flex items-center justify-between mt-1">
                <span class="text-[11px] text-emerald-600 font-bold"><i class="fa-solid fa-check mr-1"></i> {{ $completedOrdersCount }} Delivered</span>
                <span class="text-[10px] text-gray-400 font-semibold">₱{{ number_format($avgOrderValue, 2) }} AOV</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending GCash Review</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 font-heading mt-2">{{ $pendingOrders->count() }}</div>
            <span class="text-[11px] text-amber-600 font-bold"><i class="fa-solid fa-clock mr-1"></i> Needs Store Verification</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Active in Kitchen</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-blue-600 font-heading mt-2">{{ $activeOrders->count() }}</div>
            <span class="text-[11px] text-blue-600 font-bold"><i class="fa-solid fa-kitchen-set mr-1"></i> Preparing / On Delivery</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Store Health Rating</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">{{ number_format($store->rating, 1) }} / 5.0</div>
            <span class="text-[11px] text-gray-500 font-semibold">{{ $store->total_reviews ?? 0 }} Verified Reviews</span>
        </div>
    </div>

    <!-- Store Analytics & Visual Intelligence Section -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-xl font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-nutri-600"></i> Sales Performance & Menu Intelligence
                </h3>
                <p class="text-xs text-gray-500">Visual sales trends, top-selling dishes, and customer distribution</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-lime-50 text-nutri-900 border border-lime-200">
                <i class="fa-solid fa-utensils text-nutri-600"></i> {{ $totalItemsSold }} Items Delivered
            </span>
        </div>

        <!-- Row 1: Sales Revenue Line Chart + Top Selling Dishes Bar Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Daily Sales Line Chart -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">7-Day Revenue</span>
                            <h4 class="text-base font-black font-heading text-gray-900">Daily Sales Growth (₱)</h4>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700">
                            ₱{{ number_format(array_sum($dailySales), 2) }} (7 Days)
                        </span>
                    </div>
                </div>

                <div class="mt-4 relative h-64 sm:h-72 w-full">
                    <canvas id="storeRevenueChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-calendar-day mr-1 text-emerald-600"></i> Past 7 Days Delivered Food Sales</span>
                    <span class="font-bold text-gray-700">Average: ₱{{ number_format(count($dailySales) > 0 ? array_sum($dailySales) / count($dailySales) : 0, 2) }}/day</span>
                </div>
            </div>

            <!-- Top Selling Dishes Bar Chart -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">Best Sellers</span>
                            <h4 class="text-base font-black font-heading text-gray-900">Top Ordered Dishes</h4>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-xl bg-gray-100 text-gray-600 font-bold">By Quantity</span>
                    </div>
                </div>

                <div class="mt-4 relative h-64 sm:h-72 w-full">
                    @if(empty($topProductQuantities) || array_sum($topProductQuantities) === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-bowl-food text-3xl mb-2 text-gray-300"></i>
                            No dish order data yet. Complete orders to see top sellers!
                        </div>
                    @else
                        <canvas id="storeTopProductsChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-fire text-amber-500 mr-1"></i> Customer Favorites</span>
                    <span class="font-bold text-gray-700">{{ count($topProductLabels) }} Dishes Ranked</span>
                </div>
            </div>

        </div>

        <!-- Row 2: Order Fulfillment Doughnut + Top Lipa Customer Delivery Addresses Bar Chart -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Order Fulfillment Doughnut Chart -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">Fulfillment Pipeline</span>
                        <h4 class="text-base font-black font-heading text-gray-900">Order Status Breakdown</h4>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 font-bold">Total: {{ $totalOrdersCount }}</span>
                </div>

                <div class="mt-4 relative h-60 sm:h-64 w-full flex items-center justify-center">
                    @if($totalOrdersCount === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
                            No orders placed yet for this store.
                        </div>
                    @else
                        <canvas id="storeOrderStatusChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i> Store Fulfillment Rate</span>
                    <span class="font-bold text-emerald-600">
                        {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- Top Delivery Addresses Bar Chart -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">Customer Locations</span>
                        <h4 class="text-base font-black font-heading text-gray-900">Orders by Delivery Address</h4>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-xl bg-lime-50 text-nutri-900 font-bold">Top Hotspots</span>
                </div>

                <div class="mt-4 relative h-60 sm:h-64 w-full flex items-center justify-center">
                    @if(empty($addressCounts) || array_sum($addressCounts) === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-map-location-dot text-3xl mb-2 text-gray-300"></i>
                            No customer address records yet.
                        </div>
                    @else
                        <canvas id="storeAddressChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-truck mr-1 text-nutri-600"></i> Local Lipa Reach</span>
                    <span class="font-bold text-gray-700">{{ count($addressLabels) }} Active Locations</span>
                </div>
            </div>

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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = '"Plus Jakarta Sans", sans-serif';
    Chart.defaults.color = '#64748b';

    // 1. Store 7-Day Revenue Line Chart
    const revCtx = document.getElementById('storeRevenueChart');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: @json($revenueDates),
                datasets: [{
                    label: 'Store Revenue (₱)',
                    data: @json($dailySales),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#16a34a',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ' Revenue: ₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            },
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // 2. Top Selling Dishes Bar Chart (Horizontal)
    const topProdCtx = document.getElementById('storeTopProductsChart');
    if (topProdCtx) {
        new Chart(topProdCtx, {
            type: 'bar',
            data: {
                labels: @json($topProductLabels),
                datasets: [{
                    label: 'Units Sold',
                    data: @json($topProductQuantities),
                    backgroundColor: [
                        '#16a34a',
                        '#84cc16',
                        '#0284c7',
                        '#f59e0b',
                        '#8b5cf6'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        cornerRadius: 10,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.x + ' items ordered';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' } }
                    }
                }
            }
        });
    }

    // 3. Store Order Fulfillment Doughnut Chart
    const statusCtx = document.getElementById('storeOrderStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatusData['labels']),
                datasets: [{
                    data: @json($orderStatusData['counts']),
                    backgroundColor: [
                        '#16a34a', // Delivered (Emerald)
                        '#3b82f6', // In Kitchen / Transit (Blue)
                        '#f59e0b', // Pending (Amber)
                        '#f43f5e'  // Declined/Cancelled (Rose)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        cornerRadius: 10,
                        padding: 10
                    }
                },
                cutout: '68%'
            }
        });
    }

    // 4. Customer Delivery Addresses Bar Chart
    const addrCtx = document.getElementById('storeAddressChart');
    if (addrCtx) {
        const rawStoreAddressLabels = @json($addressLabels);
        new Chart(addrCtx, {
            type: 'bar',
            data: {
                labels: rawStoreAddressLabels,
                datasets: [{
                    label: 'Orders',
                    data: @json($addressCounts),
                    backgroundColor: [
                        '#84cc16',
                        '#16a34a',
                        '#22c55e',
                        '#10b981',
                        '#0284c7'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        cornerRadius: 10,
                        padding: 10,
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                const idx = items[0].dataIndex;
                                return rawStoreAddressLabels[idx] || '';
                            },
                            label: function(context) {
                                return ' ' + context.parsed.y + ' Orders Delivered';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, weight: '600' },
                            callback: function(val, index) {
                                const text = this.getLabelForValue(val) || '';
                                return text.length > 15 ? text.substring(0, 13) + '...' : text;
                            },
                            maxRotation: 30,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }
});
</script>
@endpush
