@extends('layouts.admin')

@section('title', 'Healthy Store Approvals | Super Admin')
@section('header_title', 'Store Vetting & Approvals')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Healthy Store Applications</h3>
            <p class="text-xs text-gray-500">Strictly enforce nutritious, organic, and health-conscious food guidelines for Lipa City</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold">
            <a href="{{ route('superadmin.stores.index') }}" class="px-3 py-1.5 rounded-xl {{ $status == 'all' ? 'bg-nutri-900 text-white' : 'bg-gray-100 text-gray-700' }}">All ({{ $counts['all'] }})</a>
            <a href="{{ route('superadmin.stores.index') }}?status=pending" class="px-3 py-1.5 rounded-xl {{ $status == 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Pending ({{ $counts['pending'] }})</a>
            <a href="{{ route('superadmin.stores.index') }}?status=approved" class="px-3 py-1.5 rounded-xl {{ $status == 'approved' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}">Approved ({{ $counts['approved'] }})</a>
            <a href="{{ route('superadmin.stores.index') }}?status=rejected" class="px-3 py-1.5 rounded-xl {{ $status == 'rejected' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700' }}">Rejected ({{ $counts['rejected'] }})</a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($stores as $s)
            <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition" x-data="{ openApproveModal: false, openRejectModal: false, openViewModal: false }">
                <div class="flex items-start gap-4 min-w-0">
                    <img src="{{ $s->logo_url }}" alt="{{ $s->store_name }}" class="w-14 h-14 rounded-2xl object-cover bg-gray-50 border border-gray-100 shadow-2xs shrink-0">
                    <div class="space-y-1.5 min-w-0">
                        <!-- Line 1: Store Name & Status -->
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-bold text-gray-900 text-sm sm:text-base leading-snug">{{ $s->store_name }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $s->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($s->status === 'rejected' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                {{ $s->status }}
                            </span>
                        </div>

                        <!-- Line 2: Owner, Category & Permit Badges -->
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-user-tie text-[10px] text-nutri-700"></i>
                                {{ $s->user->name ?? 'User' }} ({{ $s->phone }})
                            </span>

                            <span class="inline-flex items-center gap-1.5 text-emerald-800 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-lg text-[11px] font-semibold">
                                <i class="fa-solid fa-leaf text-[10px] text-emerald-600"></i>
                                {{ $s->health_category }}
                            </span>

                            @if($s->business_permit_no)
                                <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px]">
                                    <i class="fa-solid fa-file-invoice text-[10px] text-gray-400"></i>
                                    Permit: <span class="font-mono font-bold text-gray-800">{{ $s->business_permit_no }}</span>
                                </span>
                            @endif

                            @if($s->business_registration_number)
                                <span class="inline-flex items-center gap-1.5 text-purple-700 bg-purple-50 border border-purple-200/60 px-2.5 py-1 rounded-lg text-[11px]">
                                    <i class="fa-solid fa-building text-[10px] text-purple-500"></i>
                                    Reg No: <span class="font-mono font-bold text-purple-900">{{ $s->business_registration_number }}</span>
                                </span>
                            @endif

                            @if($s->tax_identification_number)
                                <span class="inline-flex items-center gap-1.5 text-amber-800 bg-amber-50 border border-amber-200/60 px-2.5 py-1 rounded-lg text-[11px]">
                                    <i class="fa-solid fa-receipt text-[10px] text-amber-600"></i>
                                    TIN: <span class="font-mono font-bold text-amber-900">{{ $s->tax_identification_number }}</span>
                                </span>
                            @endif

                            @if($s->business_establishment_date)
                                <span class="inline-flex items-center gap-1.5 text-teal-800 bg-teal-50 border border-teal-200/60 px-2.5 py-1 rounded-lg text-[11px]">
                                    <i class="fa-solid fa-calendar-day text-[10px] text-teal-600"></i>
                                    Est: <span class="font-semibold text-teal-900">{{ $s->business_establishment_date->format('M d, Y') }}</span>
                                </span>
                            @endif

                            @if($s->gcash_number)
                                <span class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 border border-blue-200/60 px-2.5 py-1 rounded-lg text-[11px]">
                                    <i class="fa-solid fa-wallet text-[10px] text-blue-500"></i>
                                    GCash: {{ $s->gcash_name }} ({{ $s->gcash_number }})
                                </span>
                            @endif
                        </div>

                        <!-- Line 3: Address -->
                        @if($s->address_line)
                            <div class="text-[11px] text-gray-500 flex items-center gap-1.5 pt-0.5">
                                <i class="fa-solid fa-location-dot text-rose-500 text-xs shrink-0"></i>
                                <span>{{ $s->address_line }}</span>
                            </div>
                        @endif

                        <!-- Line 4: Rejection Reason if any -->
                        @if($s->rejection_reason)
                            <div class="text-[11px] text-rose-700 bg-rose-50 border border-rose-200/60 px-2.5 py-1 rounded-lg font-medium inline-flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                                <span>Rejection Reason: "{{ $s->rejection_reason }}"</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button @click="openViewModal = true" class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-eye text-nutri-600"></i> View
                    </button>

                    @if($s->status !== 'approved')
                        <button @click="openApproveModal = true" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                            <i class="fa-solid fa-check mr-1"></i> Approve Store
                        </button>
                    @endif

                    @if($s->status !== 'rejected')
                        <button @click="openRejectModal = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i> Reject & Delete
                        </button>
                    @endif
                </div>

                <!-- VIEW ALL DETAILS MODAL (EXTRA LARGE MODAL) -->
                <div x-show="openViewModal" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 lg:p-8 bg-black/75 backdrop-blur-sm overflow-y-auto">
                    <div @click.outside="openViewModal = false" class="bg-white rounded-3xl max-w-6xl w-full max-h-[94vh] flex flex-col shadow-2xl overflow-hidden border border-gray-100 my-auto">
                        
                        <!-- Modal Header with Large Store Banner -->
                        <div class="relative h-44 sm:h-52 bg-nutri-950 overflow-hidden shrink-0">
                            <img src="{{ $s->banner_url }}" alt="{{ $s->store_name }}" class="w-full h-full object-cover opacity-60">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-black/30"></div>
                            
                            <!-- Close Button Top Right -->
                            <button type="button" @click="openViewModal = false" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center transition z-20 shadow-md">
                                <i class="fa-solid fa-xmark text-base"></i>
                            </button>
                            
                            <!-- Banner Store Info Overlay -->
                            <div class="absolute bottom-4 left-4 sm:left-8 right-4 sm:right-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div class="flex items-end gap-4 sm:gap-5">
                                    <img src="{{ $s->logo_url }}" alt="{{ $s->store_name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl object-cover bg-white p-1.5 border-2 border-white shadow-xl shrink-0">
                                    <div class="text-white pb-1">
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <h3 class="text-xl sm:text-2xl font-black font-heading leading-tight tracking-tight">{{ $s->store_name }}</h3>
                                            <span class="px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider {{ $s->status === 'approved' ? 'bg-emerald-500 text-white' : ($s->status === 'rejected' ? 'bg-rose-500 text-white' : 'bg-amber-400 text-nutri-950') }}">
                                                {{ $s->status }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $s->is_open ? 'bg-emerald-400/30 text-emerald-300 border border-emerald-400/50' : 'bg-gray-400/30 text-gray-300' }}">
                                                {{ $s->is_open ? '● Store Open' : '○ Store Closed' }}
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-nutri-200 mt-1 flex items-center gap-2 flex-wrap">
                                            <span><i class="fa-solid fa-leaf text-emerald-400 mr-1"></i> {{ $s->health_category }}</span>
                                            <span>•</span>
                                            <span class="font-mono text-white/90">Store ID: #{{ $s->id }}</span>
                                            <span>•</span>
                                            <span class="font-mono text-white/80">Slug: {{ $s->slug }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="hidden sm:flex items-center gap-2 self-end pb-1">
                                    <span class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-semibold">
                                        <i class="fa-solid fa-calendar-check mr-1 text-limey-300"></i> Joined: {{ $s->created_at ? $s->created_at->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Scrollable Modal Body (Spacious 2-Column Dashboard Layout) -->
                        <div class="p-6 sm:p-8 overflow-y-auto text-xs space-y-6">
                            
                            <!-- Description (if provided) -->
                            @if($s->description)
                                <div class="p-4 rounded-2xl bg-emerald-50/40 border border-emerald-200/60 flex items-start gap-3.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 text-sm">
                                        <i class="fa-solid fa-quote-left"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-emerald-950 text-xs uppercase tracking-wider block">Store Description & Culinary Profile (description)</span>
                                        <p class="text-gray-700 leading-relaxed text-xs sm:text-sm">{{ $s->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Main Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                
                                <!-- LEFT 2 COLUMNS: Core Data, Location, Legal & Compliance -->
                                <div class="lg:col-span-2 space-y-6">
                                    
                                    <!-- Section 1: Core Identifiers & Owner Profile -->
                                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                                        <h5 class="font-black text-gray-900 uppercase text-xs tracking-wider flex items-center gap-2 text-nutri-700">
                                            <i class="fa-solid fa-id-card text-sm"></i> Core Identifiers & Owner Details
                                        </h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                            <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Store ID & Slug</span>
                                                <span class="font-mono font-bold text-gray-900 text-sm block mt-0.5">#{{ $s->id }}</span>
                                                <span class="text-[11px] text-gray-500 font-mono block truncate mt-0.5" title="{{ $s->slug }}">{{ $s->slug }}</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Owner User Account</span>
                                                <span class="font-bold text-gray-900 text-sm block mt-0.5">{{ $s->user->name ?? 'User #' . $s->user_id }}</span>
                                                <span class="text-[11px] text-gray-500 block truncate mt-0.5" title="{{ $s->user->email ?? 'N/A' }}">{{ $s->user->email ?? 'N/A' }}</span>
                                                <span class="text-[10px] font-mono text-nutri-600 block mt-0.5 font-bold">user_id: {{ $s->user_id }}</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Contact Phone</span>
                                                <span class="font-bold text-gray-900 text-sm block mt-0.5">{{ $s->phone }}</span>
                                                <span class="text-[10px] text-emerald-600 block mt-0.5 font-semibold"><i class="fa-solid fa-circle-check mr-1"></i> Registered Contact</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2: Business Legal, Tax & Compliance Information -->
                                    <div class="p-5 rounded-2xl bg-purple-50/40 border border-purple-200/80 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h5 class="font-black text-purple-950 uppercase text-xs tracking-wider flex items-center gap-2">
                                                <i class="fa-solid fa-file-shield text-purple-700 text-sm"></i> Business Legal, Permits & Tax Data
                                            </h5>
                                            <span class="text-[10px] font-bold text-purple-800 bg-purple-100 px-2.5 py-0.5 rounded-full">Official Compliance</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                                            <div class="p-3.5 rounded-xl bg-white border border-purple-200/70 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider">Business Reg. No.</span>
                                                <span class="font-mono font-bold text-purple-950 text-xs block mt-1 truncate" title="{{ $s->business_registration_number ?? 'N/A' }}">
                                                    {{ $s->business_registration_number ?? 'Not Provided' }}
                                                </span>
                                                <span class="text-[10px] text-purple-600 block mt-0.5">DTI / SEC / CDA No.</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-purple-200/70 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider">Tax ID No. (TIN)</span>
                                                <span class="font-mono font-bold text-purple-950 text-xs block mt-1 truncate" title="{{ $s->tax_identification_number ?? 'N/A' }}">
                                                    {{ $s->tax_identification_number ?? 'Not Provided' }}
                                                </span>
                                                <span class="text-[10px] text-purple-600 block mt-0.5">BIR Registered TIN</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-purple-200/70 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider">Establishment Date</span>
                                                <span class="font-bold text-purple-950 text-xs block mt-1">
                                                    {{ $s->business_establishment_date ? $s->business_establishment_date->format('M d, Y') : 'Not Set' }}
                                                </span>
                                                <span class="text-[10px] text-purple-600 block mt-0.5">Business Founding Date</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-purple-200/70 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider">Permit / Health Cert</span>
                                                <span class="font-mono font-bold text-purple-950 text-[11px] block mt-1 truncate" title="{{ $s->business_permit_no ?? 'Permit: N/A' }}">
                                                    Permit: {{ $s->business_permit_no ?? 'N/A' }}
                                                </span>
                                                <span class="font-mono text-[10px] text-purple-700 block truncate mt-0.5" title="{{ $s->health_certificate ?? 'Health Cert: N/A' }}">
                                                    Health: {{ $s->health_certificate ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 3: Lipa Location & GPS Coordinates -->
                                    <div class="p-5 rounded-2xl bg-emerald-50/40 border border-emerald-200/80 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h5 class="font-black text-emerald-950 uppercase text-xs tracking-wider flex items-center gap-2">
                                                <i class="fa-solid fa-map-location-dot text-emerald-600 text-sm"></i> Current Location & Map Pin
                                            </h5>
                                            <a href="https://www.google.com/maps?q={{ $s->latitude }},{{ $s->longitude }}" target="_blank" class="text-xs font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-3 py-1 rounded-xl transition inline-flex items-center gap-1.5 shadow-2xs">
                                                <i class="fa-solid fa-location-arrow"></i> Open Google Maps
                                            </a>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                            <div class="p-3.5 rounded-xl bg-white border border-emerald-200/70 shadow-2xs sm:col-span-2">
                                                <span class="block text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Current Store Address</span>
                                                <p class="font-bold text-gray-900 text-xs sm:text-sm mt-0.5">{{ $s->address_line }}</p>
                                                <span class="text-[11px] text-gray-500 block mt-1"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> Lipa City, Batangas</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white border border-emerald-200/70 shadow-2xs">
                                                <span class="block text-[10px] font-bold text-emerald-800 uppercase tracking-wider">GPS Coordinates</span>
                                                <span class="font-mono font-bold text-gray-900 text-xs block mt-0.5">{{ $s->latitude }}, {{ $s->longitude }}</span>
                                                <span class="text-[10px] text-gray-500 block mt-1">Exact Map Coordinates</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- RIGHT 1 COLUMN: Operating Schedule, GCash & Timestamps -->
                                <div class="space-y-6">
                                    
                                    <!-- Schedule & Operational Metrics -->
                                    <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-200/80 space-y-4">
                                        <h5 class="font-black text-amber-950 uppercase text-xs tracking-wider flex items-center gap-2">
                                            <i class="fa-solid fa-clock text-amber-600 text-sm"></i> Operating Schedule & Status
                                        </h5>
                                        <div class="space-y-3">
                                            <div class="p-3 rounded-xl bg-white border border-amber-200/70 shadow-2xs flex items-center justify-between">
                                                <div>
                                                    <span class="block text-[10px] font-bold text-amber-800 uppercase">Operating Hours</span>
                                                    <span class="font-bold text-gray-900 text-xs">
                                                        {{ date('h:i A', strtotime($s->opening_time)) }} - {{ date('h:i A', strtotime($s->closing_time)) }}
                                                    </span>
                                                </div>
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $s->is_open ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $s->is_open ? 'Open' : 'Closed' }}
                                                </span>
                                            </div>

                                            <div class="p-3 rounded-xl bg-white border border-amber-200/70 shadow-2xs flex items-center justify-between">
                                                <div>
                                                    <span class="block text-[10px] font-bold text-amber-800 uppercase">Rating & Reviews</span>
                                                    <span class="font-bold text-amber-900 text-xs flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-star text-amber-500"></i> {{ number_format($s->rating, 2) }} / 5.00
                                                    </span>
                                                </div>
                                                <span class="text-[11px] text-gray-600 font-bold">{{ $s->total_reviews }} reviews</span>
                                            </div>
                                        </div>

                                        @if($s->rejection_reason)
                                            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                                                <span class="font-black uppercase text-[10px] block text-rose-900">Rejection Reason:</span>
                                                <p class="leading-relaxed">{{ $s->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Direct GCash Payment Setup with Large QR Preview -->
                                    <div class="p-5 rounded-2xl bg-blue-50/50 border border-blue-200/80 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h5 class="font-black text-blue-950 uppercase text-xs tracking-wider flex items-center gap-2">
                                                <i class="fa-solid fa-qrcode text-blue-600 text-sm"></i> Direct GCash Setup
                                            </h5>
                                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md">Customer Payout</span>
                                        </div>

                                        <div class="p-3.5 rounded-xl bg-white border border-blue-200/70 shadow-2xs space-y-2">
                                            <div>
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase">GCash Account Name</span>
                                                <p class="text-sm font-bold text-gray-900">{{ $s->gcash_name ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase">GCash Account Number</span>
                                                <p class="text-sm font-mono font-bold text-blue-700">{{ $s->gcash_number ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        @if($s->gcash_qr)
                                            <div class="p-4 rounded-xl bg-white border border-blue-200/70 shadow-2xs text-center space-y-3">
                                                <div class="w-36 h-36 mx-auto rounded-2xl overflow-hidden border-2 border-blue-300 bg-gray-50 p-2 shadow-inner">
                                                    <img src="{{ $s->gcash_qr_url }}" alt="GCash QR" class="w-full h-full object-contain">
                                                </div>
                                                <a href="{{ $s->gcash_qr_url }}" target="_blank" class="w-full py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center justify-center gap-1.5">
                                                    <i class="fa-solid fa-expand"></i> View Full QR Image
                                                </a>
                                            </div>
                                        @else
                                            <div class="p-4 rounded-xl bg-white border border-dashed border-gray-300 text-center text-xs text-gray-400 italic">
                                                No direct QR code uploaded yet
                                            </div>
                                        @endif
                                    </div>

                                    <!-- System Audit Timestamps -->
                                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200/70 space-y-1.5 text-[11px] text-gray-600">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Date Created:</span>
                                            <span class="font-medium text-gray-900">{{ $s->created_at ? $s->created_at->format('M d, Y H:i:s') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Last Updated:</span>
                                            <span class="font-medium text-gray-900">{{ $s->updated_at ? $s->updated_at->format('M d, Y H:i:s') : 'N/A' }}</span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Modal Footer Action Bar -->
                        <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0">
                            <button type="button" @click="openViewModal = false" class="px-6 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold text-xs transition shadow-2xs">
                                Close Window
                            </button>
                            <div class="flex items-center gap-3">
                                @if($s->status !== 'approved')
                                    <button type="button" @click="openViewModal = false; openApproveModal = true;" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-check"></i> Approve Store Application
                                    </button>
                                @endif
                                @if($s->status !== 'rejected')
                                    <button type="button" @click="openViewModal = false; openRejectModal = true;" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can"></i> Reject & Delete
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Approve Modal -->
                <div x-show="openApproveModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                    <div @click.outside="openApproveModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                        <h4 class="font-black font-heading text-base text-gray-900">Approve Healthy Store: {{ $s->store_name }}</h4>
                        <form action="{{ route('superadmin.stores.approve', $s->id) }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Commission Rate (%)</label>
                                <input type="number" step="0.5" name="commission_percent" value="{{ $s->commission_percent }}" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white font-bold">Approve & Publish to Lipa Marketplace</button>
                                <button type="button" @click="openApproveModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject & Delete Modal -->
                <div x-show="openRejectModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                    <div @click.outside="openRejectModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl border border-rose-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="font-black font-heading text-base text-gray-900 text-center">I-reject at I-delete ang {{ $s->store_name }}?</h4>
                        <p class="text-xs text-gray-600 text-center leading-relaxed">
                            Kapag na-reject, ang store na ito kasama ang kanyang user account ay <b>tuluyang mabubura sa database</b> at hindi na makakapasok sa system.
                        </p>
                        <form action="{{ route('superadmin.stores.reject', $s->id) }}" method="POST" class="space-y-3 pt-2">
                            @csrf
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-trash-can"></i> Oo, I-reject & I-delete
                                </button>
                                <button type="button" @click="openRejectModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition">Kanselahin</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="py-12 text-center text-xs text-gray-400">No stores found.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $stores->links() }}
    </div>
</div>
@endsection
