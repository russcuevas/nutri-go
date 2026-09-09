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
            <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition" x-data="{ openApproveModal: false, openRejectModal: false }">
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
                                {{ $s->user->name }} ({{ $s->phone }})
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

                <div class="flex items-center gap-2">
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
