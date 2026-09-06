@extends('layouts.app')

@section('title', 'Sign In to NutriGo | Lipa City Healthy Food Delivery')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-12 rounded-3xl shadow-card border border-nutri-100">
        <!-- Header -->
        <div class="text-center">
            <div class="inline-flex p-3 rounded-2xl bg-nutri-50 border border-nutri-200 shadow-sm mb-4">
                <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="NutriGo" class="w-12 h-12 object-contain">
            </div>
            <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight">Welcome to Nutri<span class="text-nutri-600">Go</span></h2>
            <p class="mt-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Healthy Food, Anytime, Anywhere</p>
        </div>

        <!-- Quick Demo Switcher -->
        <div class="p-3.5 rounded-2xl bg-nutri-50/80 border border-nutri-200 text-xs">
            <p class="font-bold text-nutri-900 mb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-bolt text-amber-500"></i> Fast Demo Accounts:
            </p>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <a href="{{ route('quick.login', 'admin') }}" class="p-2 rounded-xl bg-white hover:bg-nutri-100 text-nutri-950 font-bold border border-nutri-200 transition text-center shadow-xs">
                    🛡️ Super Admin
                </a>
                <a href="{{ route('quick.login', 'store') }}" class="p-2 rounded-xl bg-white hover:bg-nutri-100 text-nutri-950 font-bold border border-nutri-200 transition text-center shadow-xs">
                    🥗 Healthy Store
                </a>
                <a href="{{ route('quick.login', 'rider') }}" class="p-2 rounded-xl bg-white hover:bg-nutri-100 text-nutri-950 font-bold border border-nutri-200 transition text-center shadow-xs">
                    🛵 Delivery Rider
                </a>
                <a href="{{ route('quick.login', 'customer') }}" class="p-2 rounded-xl bg-white hover:bg-nutri-100 text-nutri-950 font-bold border border-nutri-200 transition text-center shadow-xs">
                    👑 VIP Customer
                </a>
            </div>
        </div>

        <!-- Form -->
        <form class="mt-8 space-y-5" action="{{ route('login.submit') }}" method="POST">
            @csrf

            @if($errors->any())
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email', 'customer@nutrigo.ph') }}"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 focus:border-nutri-500 text-sm outline-none transition"
                           placeholder="you@example.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           value="password123"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 focus:border-nutri-500 text-sm outline-none transition"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-nutri-600 focus:ring-nutri-500 border-gray-300">
                    <span class="text-gray-600 font-medium">Remember me</span>
                </label>
                <span class="text-gray-400 text-[11px]">Demo: password123</span>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 font-heading tracking-wide">
                Sign In to Account
            </button>
        </form>

        <!-- Register link -->
        <p class="text-center text-xs text-gray-600">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="font-extrabold text-nutri-600 hover:text-nutri-700 ml-1">Create an account</a>
        </p>
    </div>
</div>
@endsection
