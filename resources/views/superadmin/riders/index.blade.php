@extends('layouts.admin')

@section('title', 'Delivery Rider Approvals | Super Admin')
@section('header_title', 'Rider Fleet Approvals')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Delivery Rider Applications</h3>
            <p class="text-xs text-gray-500">Vet driver's licenses and vehicle details for Lipa City delivery operations</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold">
            <a href="{{ route('superadmin.riders.index') }}" class="px-3 py-1.5 rounded-xl {{ $status == 'all' ? 'bg-nutri-900 text-white' : 'bg-gray-100 text-gray-700' }}">All ({{ $counts['all'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=pending" class="px-3 py-1.5 rounded-xl {{ $status == 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Pending ({{ $counts['pending'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=approved" class="px-3 py-1.5 rounded-xl {{ $status == 'approved' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}">Approved ({{ $counts['approved'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=rejected" class="px-3 py-1.5 rounded-xl {{ $status == 'rejected' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700' }}">Rejected ({{ $counts['rejected'] }})</a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($riders as $r)
            <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition" x-data="{ openRejectModal: false }">
                <div class="flex items-start gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-nutri-50 text-nutri-800 border border-nutri-100 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                        <i class="fa-solid fa-motorcycle text-lg text-nutri-700"></i>
                    </div>

                    <div class="space-y-1.5 min-w-0">
                        <!-- Line 1: Name & Status -->
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-bold text-gray-900 text-sm sm:text-base leading-snug">{{ $r->user->name }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $r->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($r->status === 'rejected' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                {{ $r->status }}
                            </span>
                        </div>

                        <!-- Line 2: Vehicle, Phone & License Badges -->
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-gauge text-[10px] text-nutri-700"></i>
                                {{ $r->vehicle_type }} • <span class="font-mono text-gray-900">{{ $r->plate_number }}</span>
                            </span>

                            <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-phone text-[10px] text-gray-400"></i>
                                {{ $r->phone }}
                            </span>

                            <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-id-card text-[10px] text-gray-400"></i>
                                License: <span class="font-mono font-bold text-gray-800">{{ $r->license_number }}</span>
                            </span>
                        </div>

                        <!-- Line 3: Address -->
                        @if($r->address_line)
                            <div class="text-[11px] text-gray-500 flex items-center gap-1.5 pt-0.5">
                                <i class="fa-solid fa-location-dot text-rose-500 text-xs shrink-0"></i>
                                <span>{{ $r->address_line }}</span>
                            </div>
                        @endif

                        <!-- Line 4: Rejection Reason if any -->
                        @if($r->rejection_reason)
                            <div class="text-[11px] text-rose-700 bg-rose-50 border border-rose-200/60 px-2.5 py-1 rounded-lg font-medium inline-flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                                <span>Rejection Reason: "{{ $r->rejection_reason }}"</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 justify-end">
                    @if($r->status !== 'approved')
                        <form action="{{ route('superadmin.riders.approve', $r->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-check"></i> Approve Rider
                            </button>
                        </form>
                    @endif

                    @if($r->status !== 'rejected')
                        <button @click="openRejectModal = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i> Reject & Delete
                        </button>
                    @endif
                </div>

                <!-- Reject & Delete Modal -->
                <div x-show="openRejectModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                    <div @click.outside="openRejectModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl border border-rose-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="font-black font-heading text-base text-gray-900 text-center">I-reject at I-delete si {{ $r->user->name }}?</h4>
                        <p class="text-xs text-gray-600 text-center leading-relaxed">
                            Kapag na-reject, ang rider na ito kasama ang kanyang user account ay <b>tuluyang mabubura sa database</b> at hindi na makakapasok sa system.
                        </p>
                        <form action="{{ route('superadmin.riders.reject', $r->id) }}" method="POST" class="space-y-3 pt-2">
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
            <div class="py-12 text-center text-xs text-gray-400">No riders found.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $riders->links() }}
    </div>
</div>
@endsection
