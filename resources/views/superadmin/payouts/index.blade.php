@extends('layouts.admin')

@section('title', 'Rider GCash Payouts Queue | Super Admin')
@section('header_title', 'Rider GCash Payouts')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Rider In-App Wallet GCash Payouts</h3>
            <p class="text-xs text-gray-500">Disburse delivery fees to Lipa delivery riders via GCash</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold">
            <a href="{{ route('superadmin.payouts.index') }}" class="px-3 py-1.5 rounded-xl {{ $status == 'all' ? 'bg-nutri-900 text-white' : 'bg-gray-100 text-gray-700' }}">All ({{ $counts['all'] }})</a>
            <a href="{{ route('superadmin.payouts.index') }}?status=pending" class="px-3 py-1.5 rounded-xl {{ $status == 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Pending ({{ $counts['pending'] }})</a>
            <a href="{{ route('superadmin.payouts.index') }}?status=completed" class="px-3 py-1.5 rounded-xl {{ $status == 'completed' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}">Completed ({{ $counts['completed'] }})</a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($payouts as $p)
            <div class="py-5 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ openProcessModal: false, openRejectModal: false }">
                <div class="space-y-1 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-base font-black text-gray-900 font-heading">₱{{ number_format($p->amount, 2) }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $p->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($p->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $p->status }}
                        </span>
                        <span class="text-gray-400 text-[10px]">{{ $p->requested_at->format('M d, Y • h:i A') }}</span>
                    </div>
                    <p class="text-gray-700">
                        Rider: <span class="font-bold">{{ $p->rider->user->name }}</span> • Send to GCash: <span class="font-mono font-bold text-blue-800">{{ $p->gcash_number }}</span> ({{ $p->gcash_account_name }})
                    </p>
                    @if($p->admin_reference_no)
                        <p class="text-emerald-700 font-mono font-bold">Disbursement GCash Ref: {{ $p->admin_reference_no }}</p>
                    @endif
                    @if($p->notes)
                        <p class="text-gray-500 italic">"{{ $p->notes }}"</p>
                    @endif
                </div>

                @if($p->status === 'pending')
                    <div class="flex items-center gap-2">
                        <button @click="openProcessModal = true" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i> Send & Enter Ref No.
                        </button>
                        <button @click="openRejectModal = true" class="px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition">
                            Reject & Refund
                        </button>
                    </div>

                    <!-- Process Modal -->
                    <div x-show="openProcessModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                        <div @click.outside="openProcessModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                            <h4 class="font-black font-heading text-base text-gray-900">Mark GCash Payout Completed</h4>
                            <p class="text-xs text-gray-500">Send ₱{{ number_format($p->amount, 2) }} to {{ $p->gcash_number }} ({{ $p->gcash_account_name }}) and enter GCash reference number below:</p>
                            
                            <form action="{{ route('superadmin.payouts.process', $p->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                                @csrf
                                <div>
                                    <label class="block font-bold text-gray-700 mb-1">GCash Reference Number *</label>
                                    <input type="text" name="admin_reference_no" required placeholder="e.g. 1002938475819" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs">
                                </div>
                                <div>
                                    <label class="block font-bold text-gray-700 mb-1">Upload GCash Receipt (Optional)</label>
                                    <input type="file" name="proof_image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white font-bold">Confirm Disbursed</button>
                                    <button type="button" @click="openProcessModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div x-show="openRejectModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                        <div @click.outside="openRejectModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                            <h4 class="font-black font-heading text-base text-gray-900">Reject Payout Request</h4>
                            <form action="{{ route('superadmin.payouts.reject', $p->id) }}" method="POST" class="space-y-4 text-xs">
                                @csrf
                                <div>
                                    <label class="block font-bold text-gray-700 mb-1">Rejection Reason *</label>
                                    <textarea name="notes" required rows="3" class="w-full p-3 rounded-xl border border-gray-200 text-xs" placeholder="e.g. GCash account name does not match rider name..."></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white font-bold">Reject & Refund to Wallet</button>
                                    <button type="button" @click="openRejectModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="py-12 text-center text-xs text-gray-400">No payout requests in this queue.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $payouts->links() }}
    </div>
</div>
@endsection
