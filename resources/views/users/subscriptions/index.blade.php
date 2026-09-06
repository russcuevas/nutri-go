@extends('layouts.app')

@section('title', 'NutriGo VIP Meal Prep & Perks Subscription')

@section('content')
<div class="bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-widest bg-amber-400 text-nutri-950 inline-block mb-3 shadow-lg">
            👑 NutriGo VIP Lifestyle
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight">Unlock Exclusive Healthy Meal Preps & Free Deliveries</h1>
        <p class="text-xs sm:text-sm text-nutri-200 mt-3 leading-relaxed">
            Elevate your fitness journey with chef masterclasses, custom macro nutrition meal plans, priority rider dispatch, and 15% discount on partner supplements.
        </p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    @if($currentSubscription)
        <div class="mb-10 p-6 rounded-3xl bg-emerald-50 border-2 border-emerald-300 shadow-card flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white text-2xl flex items-center justify-center shadow">
                    👑
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase text-emerald-700">Active Membership</span>
                    <h3 class="text-lg font-black text-gray-900 font-heading">{{ $currentSubscription->plan->name }}</h3>
                    <p class="text-xs text-gray-600">Valid until: <span class="font-bold">{{ $currentSubscription->ends_at?->format('M d, Y') ?? 'Lifetime' }}</span></p>
                </div>
            </div>
            <span class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow">
                Active VIP Benefits Enabled
            </span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($plans as $plan)
            <div class="bg-white rounded-3xl border-2 {{ $plan->is_featured ? 'border-amber-400 shadow-2xl' : 'border-gray-200 shadow-card' }} p-8 flex flex-col justify-between relative overflow-hidden" x-data="{ openSubscribe: false }">
                
                @if($plan->badge)
                    <div class="absolute top-0 right-0 bg-amber-400 text-nutri-950 text-[10px] font-black uppercase tracking-wider py-1.5 px-4 rounded-bl-2xl shadow-sm">
                        {{ $plan->badge }}
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <h3 class="text-2xl font-black font-heading text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $plan->description }}</p>
                    </div>

                    <div class="flex items-baseline gap-1 pb-6 border-b border-gray-100">
                        <span class="text-4xl font-black font-heading text-nutri-900">₱{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs font-bold text-gray-400">/ {{ $plan->billing_period }}</span>
                    </div>

                    <div class="space-y-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-gray-900">Included VIP Perks:</span>
                        <ul class="space-y-2.5 text-xs text-gray-700">
                            @if(!empty($plan->perks))
                                @foreach($plan->perks as $perk)
                                    <li class="flex items-start gap-2.5">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                                        <span>{{ $perk }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="pt-8">
                    @auth
                        <button @click="openSubscribe = !openSubscribe" class="w-full py-4 rounded-2xl {{ $plan->is_featured ? 'bg-amber-400 hover:bg-amber-500 text-nutri-950' : 'bg-nutri-900 hover:bg-nutri-800 text-white' }} font-black text-sm shadow-md transition font-heading flex items-center justify-center gap-2">
                            <span>Join {{ $plan->name }}</span>
                            <i class="fa-solid fa-crown"></i>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-4 rounded-2xl bg-nutri-900 hover:bg-nutri-800 text-white font-black text-sm shadow-md transition font-heading flex items-center justify-center gap-2 text-center">
                            Sign In to Subscribe
                        </a>
                    @endauth

                    <!-- Subscribe GCash Modal Form -->
                    <div x-show="openSubscribe" x-transition class="mt-4 p-5 rounded-2xl bg-gray-50 border border-gray-200 text-xs space-y-4">
                        <p class="font-bold text-gray-900 flex items-center gap-1.5">
                            <i class="fa-solid fa-qrcode text-blue-600"></i> Send ₱{{ number_format($plan->price, 0) }} to NutriGo Admin GCash:
                        </p>
                        <div class="p-3 rounded-xl bg-white border border-gray-200 text-[11px] space-y-0.5">
                            <p class="font-semibold text-gray-700">GCash Name: <span class="font-bold text-gray-900">NutriGo Admin Services</span></p>
                            <p class="font-semibold text-gray-700">GCash Number: <span class="font-bold text-blue-600 font-mono">09171234567</span></p>
                        </div>

                        <form action="{{ route('users.subscriptions.subscribe', $plan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">GCash Reference Number *</label>
                                <input type="text" name="payment_reference" required placeholder="e.g. 1002938475819" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs bg-white outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Upload Receipt (Optional)</label>
                                <input type="file" name="payment_proof" accept="image/*" class="w-full text-[11px] text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                            </div>
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition">
                                Confirm VIP Subscription
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection
