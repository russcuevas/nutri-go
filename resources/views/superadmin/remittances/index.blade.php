@extends('layouts.admin')

@section('title', 'Rider 15-Day Remittances & Store Sales Reconciliation | Super Admin')
@section('header_title', 'Rider Remittances (15-Day Cycle)')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Cycle Filter Selector -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-lime-100 text-lime-900 border border-lime-300">
                    Semi-Monthly Accounting (15-Day Schedule)
                </span>
                <span class="text-xs font-bold text-gray-500">• Lipa City Deliveries</span>
            </div>
            <h2 class="text-2xl font-black font-heading text-gray-900 mt-1">Rider COD Remittances & Store Sales</h2>
            <p class="text-xs text-gray-500 mt-0.5">
                Every 15 days (1st–15th and 16th–end of month), riders remit collected store food sales to Super Admin.
            </p>
        </div>

        <!-- Cycle Switcher Dropdown -->
        <form method="GET" action="{{ route('superadmin.remittances.index') }}" class="flex flex-wrap items-center gap-2">
            <label class="text-xs font-bold text-gray-700 uppercase">Period:</label>
            <select name="cycle" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 bg-gray-50 focus:ring-2 focus:ring-lime-500">
                @foreach($availableCycles as $c)
                    <option value="{{ $c['key'] }}" {{ $selectedCycle['key'] === $c['key'] ? 'selected' : '' }}>
                        {{ $c['label'] }} {{ $c['is_current'] ? '★ Current' : '' }}
                    </option>
                @endforeach
            </select>

            <label class="inline-flex items-center gap-1.5 ml-2 text-xs font-semibold text-gray-600 cursor-pointer">
                <input type="checkbox" name="all_riders" value="1" {{ $showAll ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300 text-nutri-600 focus:ring-nutri-500">
                <span>Show idle riders</span>
            </label>
        </form>
    </div>

    <!-- Cycle Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Money to Remit -->
        <div class="bg-gradient-to-br from-nutri-900 to-nutri-950 text-white p-5 rounded-3xl shadow-card relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-limey-300">Total Money Need to Remit</span>
                <div class="text-2xl sm:text-3xl font-black font-heading mt-1 text-white">
                    ₱{{ number_format($cycleTotalToRemit, 2) }}
                </div>
                <p class="text-[11px] text-nutri-200 mt-1">Total store food sales collected by riders</p>
            </div>
            <i class="fa-solid fa-money-bill-wave absolute right-3 -bottom-2 text-white/10 text-6xl"></i>
        </div>

        <!-- Total Cash Collected -->
        <div class="bg-white p-5 rounded-3xl border border-gray-200 shadow-card">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Cash Collected</span>
            <div class="text-2xl sm:text-3xl font-black text-gray-900 font-heading mt-1">
                ₱{{ number_format($cycleTotalCollected, 2) }}
            </div>
            <p class="text-[11px] text-gray-500 mt-1">Across {{ $cycleDeliveredOrders }} delivered COD orders</p>
        </div>

        <!-- Rider Delivery Fees Retained -->
        <div class="bg-white p-5 rounded-3xl border border-lime-200 shadow-card">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Riders' Trip Earnings</span>
            <div class="text-2xl sm:text-3xl font-black text-lime-700 font-heading mt-1">
                ₱{{ number_format($cycleTotalFees, 2) }}
            </div>
            <p class="text-[11px] text-gray-500 mt-1">Credited straight to rider in-app wallets</p>
        </div>

        <!-- Pending Verification Submissions -->
        <div class="bg-white p-5 rounded-3xl border {{ $pendingCount > 0 ? 'border-amber-300 bg-amber-50/40' : 'border-gray-200' }} shadow-card">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Remittances for Review</span>
            <div class="text-2xl sm:text-3xl font-black {{ $pendingCount > 0 ? 'text-amber-600' : 'text-gray-900' }} font-heading mt-1">
                {{ $pendingCount }}
            </div>
            <p class="text-[11px] text-gray-500 mt-1">{{ $verifiedCount }} verified & reconciled for this cycle</p>
        </div>
    </div>

    <!-- Riders Remittance Ledger & Store Breakdown -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-card overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50">
            <div>
                <h3 class="text-lg font-black font-heading text-gray-900">
                    Riders Remittance Ledger & Store Sales Breakdown
                </h3>
                <p class="text-xs text-gray-500">Period: <strong class="text-gray-800">{{ $selectedCycle['label'] }}</strong></p>
            </div>
            <span class="text-xs font-bold text-gray-600">
                Showing {{ $activeSummaries->count() }} rider(s)
            </span>
        </div>

        @if($activeSummaries->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h4 class="text-base font-bold text-gray-800">No COD Deliveries in this Period</h4>
                <p class="text-xs text-gray-500 max-w-md mx-auto">
                    There are no recorded cash-on-delivery meal completions for the selected 15-day cycle. You can switch to other cycles using the dropdown above.
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($activeSummaries as $item)
                    @php
                        $r = $item['rider'];
                        $remit = $item['remittance'];
                        $remitStatus = $remit ? $remit->status : 'unsubmitted';
                    @endphp
                    <div class="p-6 transition hover:bg-gray-50/60" x-data="{ openBreakdown: true, openVerifyModal: false, openRejectModal: false }">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <!-- Rider Identity -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-nutri-100 text-nutri-900 flex items-center justify-center font-black text-lg shrink-0 overflow-hidden border border-nutri-200">
                                    @if($r->face_image_url)
                                        <img src="{{ $r->face_image_url }}" alt="Rider Face" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($r->user->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-base font-black text-gray-900 font-heading">{{ $r->user->name }}</h4>
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-gray-100 text-gray-700 font-bold">
                                            {{ $r->plate_number ?? 'Rider' }}
                                        </span>
                                        @if($remitStatus === 'verified')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Verified Remitted
                                            </span>
                                        @elseif($remitStatus === 'pending')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                                <i class="fa-solid fa-clock mr-1"></i> Proof Submitted (Review)
                                            </span>
                                        @elseif($remitStatus === 'rejected')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-800 border border-rose-300">
                                                <i class="fa-solid fa-circle-xmark mr-1"></i> Rejected
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-gray-100 text-gray-700 border border-gray-300">
                                                Unsubmitted
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Phone: <strong class="text-gray-700">{{ $r->phone }}</strong> • Barangay: <span class="text-gray-700">{{ $r->barangay ?? 'Lipa City' }}</span> • Vehicle: {{ $r->vehicle_type }}
                                    </p>
                                </div>
                            </div>

                            <!-- Financial Numbers -->
                            <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-xs">
                                <div>
                                    <span class="text-gray-400 text-[10px] font-bold uppercase block">COD Orders</span>
                                    <span class="text-sm font-black text-gray-900">{{ $item['orders_count'] }} delivered</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-[10px] font-bold uppercase block">Total Collected</span>
                                    <span class="text-sm font-bold text-gray-700">₱{{ number_format($item['total_cash_collected'], 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-[10px] font-bold uppercase block">Rider Trip Fee</span>
                                    <span class="text-sm font-bold text-lime-700">₱{{ number_format($item['rider_delivery_fees'], 2) }}</span>
                                </div>
                                <div class="bg-lime-50 border border-lime-300 px-4 py-2 rounded-2xl">
                                    <span class="text-lime-900 text-[10px] font-black uppercase tracking-wider block">Money Need to Remit</span>
                                    <span class="text-lg font-black text-lime-950 font-heading">
                                        ₱{{ number_format($item['total_money_to_remit'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submitted Proof Information & Actions -->
                        @if($remit)
                            <div class="mt-4 pt-3 border-t border-gray-100 bg-nutri-50/50 p-4 rounded-2xl text-xs space-y-2">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900">Remittance Reference:</span>
                                            <span class="font-mono font-bold text-nutri-900 bg-white px-2 py-0.5 rounded border border-gray-200">
                                                {{ $remit->reference_no }}
                                            </span>
                                            <span class="text-gray-500 uppercase text-[10px] font-bold">({{ $remit->payment_method }})</span>
                                            <span class="text-gray-400 text-[10px]">{{ $remit->remitted_at?->format('M d, Y • h:i A') }}</span>
                                        </div>
                                        @if($remit->notes)
                                            <p class="text-gray-600 italic">Rider Note: "{{ $remit->notes }}"</p>
                                        @endif
                                        @if($remit->admin_notes)
                                            <p class="text-emerald-800 font-semibold">Admin Note: "{{ $remit->admin_notes }}"</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($remit->proof_url)
                                            <a href="{{ $remit->proof_url }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-white border border-gray-300 text-gray-800 hover:bg-gray-50 font-bold text-xs inline-flex items-center gap-1.5 shadow-sm transition">
                                                <i class="fa-solid fa-receipt text-lime-600"></i> View Payment Proof
                                            </a>
                                        @endif

                                        @if($remit->status === 'pending')
                                            <button @click="openVerifyModal = true" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                                <i class="fa-solid fa-check"></i> Verify & Settle
                                            </button>
                                            <button @click="openRejectModal = true" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Each Store Total Sales Breakdown Table -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <button @click="openBreakdown = !openBreakdown" class="text-xs font-bold text-nutri-800 hover:text-nutri-950 flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-store"></i>
                                <span>Each Store Total Sales ({{ count($item['store_breakdown']) }} stores)</span>
                                <i class="fa-solid text-[10px] transition-transform" :class="openBreakdown ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>

                            <div x-show="openBreakdown" class="mt-3 overflow-x-auto">
                                <table class="w-full text-left text-xs border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-gray-100/80 text-gray-700 uppercase font-bold text-[10px]">
                                        <tr>
                                            <th class="py-2.5 px-3">Partner Store</th>
                                            <th class="py-2.5 px-3">Store Location</th>
                                            <th class="py-2.5 px-3 text-center">Orders</th>
                                            <th class="py-2.5 px-3 text-right">Rider Trip Fee</th>
                                            <th class="py-2.5 px-3 text-right font-black text-gray-900">Store Sales (Need to Remit)</th>
                                            <th class="py-2.5 px-3 text-right">Total Cash Collected</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($item['store_breakdown'] as $sb)
                                            <tr class="hover:bg-lime-50/30 transition">
                                                <td class="py-2 px-3 font-bold text-gray-900 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-utensils text-lime-600 text-xs"></i>
                                                    {{ $sb['store_name'] }}
                                                </td>
                                                <td class="py-2 px-3 text-gray-500">
                                                    Brgy. {{ $sb['store_barangay'] }}
                                                </td>
                                                <td class="py-2 px-3 text-center font-bold text-gray-700">
                                                    {{ $sb['orders_count'] }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-semibold text-lime-700">
                                                    ₱{{ number_format($sb['delivery_fees'], 2) }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-black text-nutri-900 bg-lime-50/50">
                                                    ₱{{ number_format($sb['total_sales'], 2) }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-bold text-gray-800">
                                                    ₱{{ number_format($sb['total_cash_collected'], 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-3 px-3 text-center text-gray-400 italic">
                                                    No store orders delivered during this period.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Verify Modal -->
                        @if($remit)
                            <div x-show="openVerifyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-gray-200 space-y-4" @click.outside="openVerifyModal = false">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-black font-heading text-gray-900">Verify & Settle Remittance</h4>
                                        <button @click="openVerifyModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <p class="text-xs text-gray-600">
                                        Confirming remittance of <strong class="text-nutri-900">₱{{ number_format($remit->total_remit_amount, 2) }}</strong> from <strong class="text-gray-900">{{ $r->user->name }}</strong> for cycle <span class="font-bold text-gray-800">{{ $selectedCycle['label'] }}</span>.
                                    </p>
                                    <form action="{{ route('superadmin.remittances.verify', $remit->id) }}" method="POST" class="space-y-4 text-xs">
                                        @csrf
                                        <div>
                                            <label class="block font-bold text-gray-700 uppercase mb-1">Reconciliation Notes (Optional)</label>
                                            <input type="text" name="admin_notes" value="Remittance verified and reconciled against store ledgers." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs">
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" @click="openVerifyModal = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Cancel</button>
                                            <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black shadow-md">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Confirm Verification
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div x-show="openRejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-gray-200 space-y-4" @click.outside="openRejectModal = false">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-black font-heading text-rose-700">Reject Remittance Submission</h4>
                                        <button @click="openRejectModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <p class="text-xs text-gray-600">
                                        Please provide a clear reason to notify {{ $r->user->name }} why this remittance could not be verified (e.g. incorrect reference number or incomplete amount).
                                    </p>
                                    <form action="{{ route('superadmin.remittances.reject', $remit->id) }}" method="POST" class="space-y-4 text-xs">
                                        @csrf
                                        <div>
                                            <label class="block font-bold text-gray-700 uppercase mb-1">Reason for Rejection *</label>
                                            <textarea name="admin_notes" required rows="3" placeholder="e.g. GCash reference number does not match receipt screenshot. Please resubmit." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs"></textarea>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" @click="openRejectModal = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Cancel</button>
                                            <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-md">
                                                <i class="fa-solid fa-circle-xmark mr-1"></i> Confirm Rejection
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

