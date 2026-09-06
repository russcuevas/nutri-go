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
            <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ openRejectModal: false }">
                <div class="space-y-1 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900 text-sm">{{ $r->user->name }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $r->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($r->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $r->status }}
                        </span>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-xs">
                        <strong>Vehicle:</strong> <span class="font-semibold text-gray-900">{{ $r->vehicle_type }}</span> • <strong>Plate:</strong> <span class="font-mono font-bold text-gray-900">{{ $r->plate_number }}</span>
                        <br>
                        <strong>Phone:</strong> {{ $r->phone }} • <strong>License:</strong> <span class="font-mono font-bold text-gray-800">{{ $r->license_number }}</span>
                        @if($r->address_line)
                            <br>
                            <strong>Address:</strong> {{ $r->address_line }}
                        @endif
                    </p>
                    @if($r->rejection_reason)
                        <p class="text-rose-600 font-semibold italic pt-1">Rejection Reason: "{{ $r->rejection_reason }}"</p>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if($r->status !== 'approved')
                        <form action="{{ route('superadmin.riders.approve', $r->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                <i class="fa-solid fa-check"></i> Approve Rider
                            </button>
                        </form>
                    @endif

                    @if($r->status !== 'rejected')
                        <button @click="openRejectModal = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i> Reject & Delete
                        </button>
                    @endif
                </div>

                <!-- Reject & Delete Modal -->
                <div x-show="openRejectModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
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
