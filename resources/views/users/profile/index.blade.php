@extends('layouts.app')

@section('title', 'My Profile & Account Settings | NutriGo Lipa')

@section('content')
<div class="bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Large User Avatar with VIP Badge -->
            <div class="relative shrink-0">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden bg-gradient-to-br from-nutri-600 to-limey-500 p-1 shadow-2xl">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-[22px]">
                    @else
                        <div class="w-full h-full bg-nutri-950/80 rounded-[22px] flex items-center justify-center text-3xl sm:text-4xl font-black text-limey-400 font-heading uppercase">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                @if($user->isVipSubscriber())
                    <div class="absolute -bottom-2 -right-2 bg-amber-400 text-nutri-950 px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-lg border-2 border-white flex items-center gap-1">
                        <i class="fa-solid fa-crown text-xs"></i> VIP
                    </div>
                @endif
            </div>

            <!-- User Info & Summary -->
            <div class="text-center sm:text-left space-y-1.5 flex-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h1 class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-white">{{ $user->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->isVipSubscriber() ? 'bg-amber-400 text-nutri-950' : 'bg-white/20 text-nutri-200' }}">
                        {{ $user->isVipSubscriber() ? 'VIP Subscriber' : 'Customer' }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-nutri-200 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    <span><i class="fa-solid fa-envelope mr-1.5 opacity-70"></i> {{ $user->email }}</span>
                    @if($user->phone)
                        <span>•</span>
                        <span><i class="fa-solid fa-phone mr-1.5 opacity-70"></i> {{ $user->phone }}</span>
                    @endif
                </p>
                <p class="text-[11px] text-nutri-300/80">
                    <i class="fa-regular fa-calendar-check mr-1 opacity-70"></i> Member since {{ $user->created_at->format('F Y') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Quick Stats & Membership Info -->
        <div class="space-y-6">
            <!-- Account Overview Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4">
                <h3 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Account Overview</h3>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Total Orders</span>
                        <span class="text-xl font-black font-heading text-gray-900 mt-0.5 block">{{ $ordersCount }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">VIP Status</span>
                        <span class="text-sm font-black font-heading {{ $user->isVipSubscriber() ? 'text-amber-600' : 'text-gray-500' }} mt-1 block">
                            {{ $user->isVipSubscriber() ? 'Active VIP' : 'Free User' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-100 text-xs">
                    <a href="{{ route('users.orders.index') }}" class="flex items-center justify-between p-2.5 rounded-xl text-gray-700 hover:bg-nutri-50 hover:text-nutri-900 font-bold transition">
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-receipt text-nutri-600"></i> My Order History</span>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                    </a>
                    <a href="{{ route('users.subscriptions.index') }}" class="flex items-center justify-between p-2.5 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-900 font-bold transition">
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-crown text-amber-500"></i> VIP Subscription</span>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- VIP Membership Card -->
            @if($subscription)
                <div class="p-6 rounded-3xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-card space-y-3 relative overflow-hidden">
                    <i class="fa-solid fa-crown text-6xl text-white/10 absolute -right-3 -bottom-3"></i>
                    <div class="flex items-center justify-between relative">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-wider backdrop-blur-xs">
                            {{ $subscription->plan->name ?? 'NutriGo VIP' }}
                        </span>
                        <span class="text-xs font-bold text-amber-100">Active</span>
                    </div>
                    <h4 class="text-lg font-black font-heading relative leading-tight">VIP Member Privileges Enabled</h4>
                    <p class="text-xs text-amber-100 leading-relaxed relative">
                        Valid until: <strong>{{ $subscription->ends_at?->format('M d, Y') ?? 'Lifetime' }}</strong>
                    </p>
                </div>
            @else
                <div class="p-6 rounded-3xl bg-gradient-to-br from-nutri-900 to-nutri-950 text-white shadow-card space-y-3 relative overflow-hidden">
                    <i class="fa-solid fa-gem text-6xl text-white/5 absolute -right-3 -bottom-3"></i>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-400 text-nutri-950 text-[10px] font-black uppercase tracking-wider inline-block">Upgrade</span>
                    <h4 class="text-base font-black font-heading leading-tight">Unlock NutriGo VIP Meal Preps</h4>
                    <p class="text-xs text-nutri-200 leading-relaxed">
                        Enjoy free deliveries on Lipa healthy stores and chef masterclass recipes.
                    </p>
                    <a href="{{ route('users.subscriptions.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-400 text-nutri-950 font-black text-xs shadow-sm hover:bg-amber-500 transition">
                        View VIP Plans <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column: Edit Profile & Password Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Personal Details & Avatar Form -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6"
                 x-data="{ avatarPreview: '{{ $user->avatar_url }}' }">
                
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-black font-heading text-gray-900">Personal Information</h3>
                        <p class="text-xs text-gray-500">Update your name, contact details, and profile avatar</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-nutri-50 text-nutri-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                </div>

                <form action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Avatar Upload with Live Preview -->
                    <div>
                        <label class="block font-bold text-gray-700 text-xs mb-2">Profile Picture / Avatar</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4 p-4 rounded-2xl bg-gray-50/70 border border-gray-200/80">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-200 border-2 border-white shadow shrink-0 flex items-center justify-center">
                                <template x-if="avatarPreview">
                                    <img :src="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!avatarPreview">
                                    <span class="font-black text-gray-400 text-lg uppercase">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </template>
                            </div>
                            
                            <div class="space-y-1 text-center sm:text-left flex-1">
                                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-900 file:text-white hover:file:bg-nutri-800 transition cursor-pointer"
                                       @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file); }">
                                <p class="text-[11px] text-gray-400">JPG, PNG, or WEBP up to 3MB recommended.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Name & Phone Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                            @error('name')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Contact Phone Number</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" name="phone" placeholder="09xxxxxxxxx" value="{{ old('phone', $user->phone) }}" class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                            </div>
                            @error('phone')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="text-xs">
                        <label class="block font-bold text-gray-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk"></i> Save Profile Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-black font-heading text-gray-900">Security & Password</h3>
                        <p class="text-xs text-gray-500">Ensure your account uses a secure password</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                        <i class="fa-solid fa-key"></i>
                    </div>
                </div>

                <form action="{{ route('users.profile.password') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-gray-700 mb-1.5">Current Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                        @error('current_password')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">New Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required placeholder="Min. 8 characters" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                            @error('password')
                                <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Confirm New Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required placeholder="Repeat new password" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-xs shadow-sm transition flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-lock"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
