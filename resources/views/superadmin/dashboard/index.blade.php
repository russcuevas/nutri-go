@extends('layouts.admin')

@section('title', 'Super Admin Overview | NutriGo Lipa')
@section('header_title', 'Platform Analytics & Overview')

@section('content')
<div class="space-y-8">
    
    <!-- Top Platform Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gross Platform GMV</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 font-heading mt-2">₱{{ number_format($totalGrossSales, 2) }}</div>
            <div class="flex items-center justify-between mt-1">
                <span class="text-[11px] text-emerald-600 font-bold"><i class="fa-solid fa-chart-line mr-1"></i> {{ $deliveredOrders }} Delivered Orders</span>
                <span class="text-[10px] text-gray-400 font-semibold">₱{{ number_format($avgOrderValue, 2) }} AOV</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Platform Fee Revenue</span>
                <div class="w-10 h-10 rounded-2xl bg-lime-50 text-lime-700 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-lime-700 font-heading mt-2">₱{{ number_format($totalPlatformFees, 2) }}</div>
            <span class="text-[11px] text-gray-500 font-semibold">Fixed ₱10 / delivered order</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Store Vetting</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 font-heading mt-2">{{ $pendingStoresCount }}</div>
            <span class="text-[11px] text-amber-600 font-bold"><i class="fa-solid fa-shield-halved mr-1"></i> {{ $approvedStoresCount }} Approved Stores</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Rider Payouts</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-xs">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 font-heading mt-2">₱{{ number_format($pendingPayoutsAmount, 2) }}</div>
            <span class="text-[11px] text-rose-600 font-bold"><i class="fa-solid fa-clock mr-1"></i> {{ $pendingPayoutsCount }} Requests Pending</span>
        </div>
    </div>

    <!-- Analytics & Visual Intelligence Section -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-xl font-black font-heading text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-nutri-600"></i> Platform Analytics & Insights
                </h3>
                <p class="text-xs text-gray-500">Real-time performance metrics across Lipa City operations</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Lipa City Feed
            </span>
        </div>

        <!-- Row 1: Line Chart (Sales & Fees Trend) + Bar Chart (Delivery Address Distribution) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- 7-Day Revenue Trend Line Chart -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">7-Day Financial Trend</span>
                            <h4 class="text-base font-black font-heading text-gray-900">Gross Sales vs Platform Fees</h4>
                        </div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1.5 text-gray-600 font-medium">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> GMV (₱)
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-gray-600 font-medium">
                                <span class="w-3 h-3 rounded-full bg-lime-500 inline-block"></span> Fees (₱)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 relative h-64 sm:h-72 w-full">
                    <canvas id="superAdminRevenueChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-calendar-week mr-1 text-emerald-600"></i> Past 7 Days Platform Activity</span>
                    <span class="font-bold text-gray-700">Total: ₱{{ number_format(array_sum($dailySales), 2) }}</span>
                </div>
            </div>

            <!-- Address Heatmap / Order Volume Bar Chart -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">Lipa City Demand</span>
                            <h4 class="text-base font-black font-heading text-gray-900">Orders by Delivery Address</h4>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-xl bg-gray-100 text-gray-600 font-bold">Top Locations</span>
                    </div>
                </div>

                <div class="mt-4 relative h-64 sm:h-72 w-full">
                    @if(empty($addressCounts) || array_sum($addressCounts) === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-map-location-dot text-3xl mb-2 text-gray-300"></i>
                            No delivery address data available yet.
                        </div>
                    @else
                        <canvas id="superAdminAddressChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-location-dot mr-1 text-rose-500"></i> Lipa City Coverage</span>
                    <span class="font-bold text-gray-700">{{ count($addressLabels) }} Active Locations</span>
                </div>
            </div>

        </div>

        <!-- Row 2: Store Health Categories Pie/Doughnut + Order Status Doughnut -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Store Health Categories Doughnut -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Ecosystem Diversity</span>
                        <h4 class="text-base font-black font-heading text-gray-900">Store Health Categories</h4>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 font-bold">Diet Spectrum</span>
                </div>

                <div class="mt-4 relative h-60 sm:h-64 w-full flex items-center justify-center">
                    @if(empty($healthCatCounts) || array_sum($healthCatCounts) === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-apple-whole text-3xl mb-2 text-gray-300"></i>
                            No categorized stores registered yet.
                        </div>
                    @else
                        <canvas id="superAdminHealthCatChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-leaf mr-1 text-emerald-600"></i> Active Health Niches</span>
                    <span class="font-bold text-gray-700">{{ array_sum($healthCatCounts) }} Registered Stores</span>
                </div>
            </div>

            <!-- Platform Order Fulfillment Status Pie/Doughnut -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-nutri-700">Platform Health</span>
                        <h4 class="text-base font-black font-heading text-gray-900">Order Fulfillment Status</h4>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 font-bold">Total: {{ $totalOrders }}</span>
                </div>

                <div class="mt-4 relative h-60 sm:h-64 w-full flex items-center justify-center">
                    @if($totalOrders === 0)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 text-xs text-center p-4">
                            <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300"></i>
                            No orders placed yet.
                        </div>
                    @else
                        <canvas id="superAdminOrderStatusChart"></canvas>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i> Delivery Success Rate</span>
                    <span class="font-bold text-emerald-600">
                        {{ $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

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
                            <span class="text-gray-500">Category: <span class="font-semibold text-emerald-700">{{ $s->health_category ?? 'Healthy Kitchen' }}</span> • Brgy. {{ $s->barangay }}</span>
                        </div>
                        <a href="{{ route('superadmin.stores.index') }}" class="px-3 py-1.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-xs transition">
                            Review
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-400">No pending stores awaiting approval.</div>
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
                        <a href="{{ route('superadmin.riders.index') }}" class="px-3 py-1.5 rounded-xl bg-lime-500 hover:bg-lime-600 text-nutri-950 font-bold text-xs shadow-xs transition">
                            Review
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-400">No pending riders awaiting approval.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Platform Orders -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
            <h3 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-nutri-600"></i> Recent Lipa City Orders
            </h3>
            <span class="text-xs text-gray-400 font-semibold">Latest 8 Activities</span>
        </div>
        <div class="divide-y divide-gray-100 text-xs">
            @forelse($recentOrders as $ro)
                <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-gray-900">{{ $ro->order_number }}</span> • {{ $ro->store->store_name ?? 'Store' }} → {{ $ro->delivery_address }}
                        <span class="text-gray-400 text-[10px] block">{{ $ro->created_at->format('M d, Y • h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-black text-gray-900 font-heading">₱{{ number_format($ro->total_amount, 2) }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $ro->status_badge_class }}">
                            {{ $ro->status_label }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-gray-400">No platform orders placed yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Shared chart font & styling configuration
    Chart.defaults.font.family = '"Plus Jakarta Sans", sans-serif';
    Chart.defaults.color = '#64748b';

    // 1. Revenue & GMV Trend Line Chart
    const revCtx = document.getElementById('superAdminRevenueChart');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: @json($revenueDates),
                datasets: [
                    {
                        label: 'Gross GMV (₱)',
                        data: @json($dailySales),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Platform Fees (₱)',
                        data: @json($dailyFees),
                        borderColor: '#84cc16',
                        backgroundColor: 'rgba(132, 204, 22, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointBackgroundColor: '#84cc16',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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

    // 2. Lipa City Actual Delivery Address Distribution Bar Chart
    const addrCtx = document.getElementById('superAdminAddressChart');
    if (addrCtx) {
        const rawAddressLabels = @json($addressLabels);
        new Chart(addrCtx, {
            type: 'bar',
            data: {
                labels: rawAddressLabels,
                datasets: [{
                    label: 'Orders',
                    data: @json($addressCounts),
                    backgroundColor: [
                        '#16a34a',
                        '#22c55e',
                        '#4ade80',
                        '#84cc16',
                        '#a3e635',
                        '#10b981'
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
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
                                return rawAddressLabels[idx] || '';
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

    // 3. Store Health Categories Doughnut Chart
    const catCtx = document.getElementById('superAdminHealthCatChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: @json($healthCatLabels),
                datasets: [{
                    data: @json($healthCatCounts),
                    backgroundColor: [
                        '#16a34a',
                        '#0284c7',
                        '#84cc16',
                        '#f59e0b',
                        '#ec4899',
                        '#8b5cf6',
                        '#14b8a6'
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

    // 4. Order Fulfillment Status Doughnut/Pie Chart
    const statusCtx = document.getElementById('superAdminOrderStatusChart');
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
});
</script>
@endpush
