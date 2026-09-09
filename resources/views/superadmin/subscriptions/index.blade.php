@extends('layouts.admin')

@section('title', 'VIP Subscription Plans & Members | Super Admin')
@section('header_title', 'VIP Plans & Subscribers')

@section('content')
<div class="space-y-8" x-data="{ openPlanModal: false }">
    
    <!-- Header Actions & Info -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">VIP Subscription Plans & Members</h3>
            <p class="text-xs text-gray-500">Manage VIP tiers, member benefits, and track active subscriber time remaining</p>
        </div>
        <button @click="openPlanModal = true" class="px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-nutri-950 font-black text-xs shadow-sm transition flex items-center gap-2 font-heading cursor-pointer">
            <i class="fa-solid fa-crown text-sm"></i> Create Subscription Plan
        </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-3xl bg-gradient-to-br from-nutri-900 to-nutri-950 text-white shadow-card flex items-center justify-between">
            <div>
                <span class="text-nutri-300 font-bold text-xs uppercase tracking-wider block">Active VIP Members</span>
                <span class="text-2xl font-black font-heading tracking-tight mt-1 block">{{ number_format($activeSubscribersCount) }}</span>
                <span class="text-[11px] text-limey-400 font-semibold mt-0.5 block"><i class="fa-solid fa-circle-check mr-1"></i> Current Active Plans</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/10 text-limey-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-crown"></i>
            </div>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-gray-200 shadow-card flex items-center justify-between">
            <div>
                <span class="text-gray-500 font-bold text-xs uppercase tracking-wider block">Active VIP Volume</span>
                <span class="text-2xl font-black font-heading text-gray-900 tracking-tight mt-1 block">₱{{ number_format($totalRevenue, 2) }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Member Revenue</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-gray-200 shadow-card flex items-center justify-between">
            <div>
                <span class="text-gray-500 font-bold text-xs uppercase tracking-wider block">Available VIP Tiers</span>
                <span class="text-2xl font-black font-heading text-gray-900 tracking-tight mt-1 block">{{ $plans->count() }} Plans</span>
                <span class="text-[11px] text-gray-400 font-semibold mt-0.5 block"><i class="fa-solid fa-layer-group mr-1"></i> Configured Tiers</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-gem"></i>
            </div>
        </div>
    </div>

    <!-- Active Plans Grid -->
    <div class="space-y-4">
        <h4 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Subscription Tiers ({{ $plans->count() }})</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card flex flex-col justify-between relative overflow-hidden group hover:border-amber-300 transition">
                    @if($plan->badge)
                        <div class="absolute top-4 right-4 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 font-black text-[10px] uppercase tracking-wider border border-amber-200">
                            {{ $plan->badge }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base font-bold shadow-2xs">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-gray-900 font-heading leading-tight">{{ $plan->name }}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $plan->description ?? 'Exclusive access to VIP features & special discounts.' }}</p>
                        </div>

                        <div class="py-2 flex items-baseline gap-1.5 border-y border-gray-100">
                            <span class="text-2xl font-black text-gray-900 font-heading">₱{{ number_format($plan->price, 0) }}</span>
                            <span class="text-xs font-bold text-gray-500">/ {{ $plan->billing_period }}</span>
                        </div>

                        @if(!empty($plan->perks) && is_array($plan->perks))
                            <ul class="space-y-1.5 text-xs text-gray-600 pt-1">
                                @foreach($plan->perks as $perk)
                                    <li class="flex items-center gap-2 text-[11px]">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs shrink-0"></i>
                                        <span>{{ $perk }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-50 text-gray-700 font-bold text-xs border border-gray-100">
                            <i class="fa-solid fa-users text-amber-500 text-[10px]"></i>
                            {{ $plan->subscriptions_count }} Active Members
                        </span>

                        <form action="{{ route('superadmin.subscriptions.plan.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscription plan: {{ addslashes($plan->name) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Delete Plan">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Subscribers History Table with Live Countdown -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-5">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-black font-heading uppercase tracking-wider text-gray-900">Recent VIP Subscribers ({{ $subscribers->total() }})</h4>
        </div>

        @if($subscribers->isEmpty())
            <div class="py-12 text-center text-xs text-gray-400">
                <i class="fa-solid fa-crown text-3xl mb-2.5 text-gray-300 block"></i>
                No VIP subscribers found yet.
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($subscribers as $sub)
                    <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition"
                         x-data="subscriptionTimer('{{ $sub->ends_at ? $sub->ends_at->toISOString() : '' }}', '{{ $sub->status }}')">
                        
                        <!-- Subscriber Info & Countdown -->
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200/70 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs relative">
                                <span class="uppercase">{{ substr($sub->user->name ?? 'U', 0, 2) }}</span>
                                <i class="fa-solid fa-crown text-[10px] text-amber-500 absolute -top-1.5 -right-1.5 bg-white rounded-full p-0.5 shadow-2xs border border-amber-200"></i>
                            </div>

                            <div class="space-y-1.5 min-w-0">
                                <!-- Line 1: Name & Status -->
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-bold text-gray-900 text-sm sm:text-base leading-snug">{{ $sub->user->name ?? 'Customer' }}</span>
                                    <span class="text-gray-400 text-xs">({{ $sub->user->email ?? 'No email' }})</span>
                                    
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                          :class="isExpired ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'">
                                        <span x-text="isExpired ? 'EXPIRED' : 'ACTIVE'"></span>
                                    </span>
                                </div>

                                <!-- Line 2: Plan, Ref & Dates -->
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-amber-900 bg-amber-100/70 px-2.5 py-1 rounded-lg text-[11px] border border-amber-200/50">
                                        <i class="fa-solid fa-crown text-amber-600 text-[10px]"></i>
                                        {{ $sub->plan->name ?? 'NutriGo VIP' }}
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px] font-mono">
                                        <i class="fa-solid fa-receipt text-gray-400 text-[10px]"></i>
                                        Ref: <span class="font-bold text-gray-900">{{ $sub->payment_reference ?? 'N/A' }}</span>
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 text-gray-500 bg-gray-50 px-2.5 py-1 rounded-lg text-[11px]">
                                        <i class="fa-regular fa-calendar text-gray-400 text-[10px]"></i>
                                        Started: {{ $sub->starts_at ? $sub->starts_at->format('M d, Y') : ($sub->created_at ? $sub->created_at->format('M d, Y') : 'N/A') }}
                                    </span>

                                    @if($sub->ends_at)
                                        <span class="inline-flex items-center gap-1.5 text-gray-500 bg-gray-50 px-2.5 py-1 rounded-lg text-[11px]">
                                            <i class="fa-regular fa-calendar-xmark text-gray-400 text-[10px]"></i>
                                            Expires: {{ $sub->ends_at->format('M d, Y h:i A') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Line 3: Dynamic Live Countdown Timer -->
                                <div class="pt-1">
                                    <template x-if="!isExpired">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-emerald-900 text-xs shadow-2xs">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            <span class="font-semibold text-[11px] text-emerald-800">Time Remaining:</span>
                                            <span class="font-mono font-black text-xs text-emerald-950 bg-white/80 border border-emerald-300 px-2 py-0.5 rounded-lg shadow-2xs tracking-wide" x-text="countdownText"></span>
                                        </div>
                                    </template>

                                    <template x-if="isExpired">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                                            <i class="fa-solid fa-clock-rotate-left text-rose-500"></i>
                                            <span>Subscription Ended</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 justify-end">
                            <form action="{{ route('superadmin.subscriptions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscription record for {{ addslashes($sub->user->name ?? 'User') }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition flex items-center gap-1.5 text-xs font-bold cursor-pointer" title="Remove Record">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-gray-100">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

    <!-- Create Plan Modal -->
    <div x-show="openPlanModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.outside="openPlanModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h4 class="font-black font-heading text-base text-gray-900">Create VIP Subscription Plan</h4>
                        <p class="text-[11px] text-gray-500">Add a new membership tier and benefits</p>
                    </div>
                </div>
                <button type="button" @click="openPlanModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form action="{{ route('superadmin.subscriptions.plan.store') }}" method="POST" class="flex flex-col overflow-hidden">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Plan Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. NutriGo VIP Monthly" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Price (₱) <span class="text-rose-500">*</span></label>
                            <input type="number" step="1" name="price" required placeholder="299" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Billing Period <span class="text-rose-500">*</span></label>
                            <select name="billing_period" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                <option value="monthly">Monthly (30 Days)</option>
                                <option value="quarterly">Quarterly (90 Days)</option>
                                <option value="annual">Annual (365 Days)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Badge Tag (Optional)</label>
                        <input type="text" name="badge" placeholder="e.g. Most Popular, Best Value" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" placeholder="Short description of this plan" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Perks & Benefits (1 per line)</label>
                        <textarea name="perks" rows="3" class="w-full p-3 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 bg-gray-50/50 focus:bg-white text-xs transition" placeholder="Free delivery on orders above ₱300&#10;Access to VIP cooking masterclasses&#10;10% off selected healthy items"></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openPlanModal = false" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold text-xs transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-nutri-950 font-black font-heading text-xs shadow-sm transition">Save Plan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function subscriptionTimer(endsAtStr, status) {
        return {
            endsAt: endsAtStr ? new Date(endsAtStr).getTime() : null,
            status: status,
            countdownText: 'Calculating...',
            isExpired: false,
            timer: null,

            init() {
                if (!this.endsAt) {
                    this.countdownText = 'Ongoing';
                    return;
                }
                this.updateTime();
                this.timer = setInterval(() => {
                    this.updateTime();
                }, 1000);
            },

            updateTime() {
                const now = new Date().getTime();
                const distance = this.endsAt - now;

                if (distance <= 0 || this.status !== 'active') {
                    this.isExpired = true;
                    this.countdownText = 'Expired';
                    if (this.timer) clearInterval(this.timer);
                    return;
                }

                this.isExpired = false;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let parts = [];
                if (days > 0) parts.push(days + 'd');
                parts.push(String(hours).padStart(2, '0') + 'h');
                parts.push(String(minutes).padStart(2, '0') + 'm');
                parts.push(String(seconds).padStart(2, '0') + 's');

                this.countdownText = parts.join(' ');
            }
        };
    }
</script>
@endpush

