<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NutriGo - Healthy Food, Anytime, Anywhere | Lipa City')</title>
    <meta name="description" content="NutriGo is Lipa City's premier healthy food, nutrition, and meal prep delivery platform. Order fresh organic meals, track live distance fees, and watch partner health vlogs.">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/nutrigo-logo.jpg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nutri: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            300: '#86EFAC',
                            400: '#4ADE80',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            800: '#166534',
                            900: '#0F4A2B',
                            950: '#052e16',
                        },
                        limey: {
                            400: '#A3E635',
                            500: '#84CC16',
                            600: '#65A30D',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow': '0 0 25px -5px rgba(34, 197, 94, 0.4)',
                        'card': '0 10px 30px -10px rgba(15, 74, 43, 0.08)',
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome & Leaflet Map CSS/JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAF8;
            color: #1F2937;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(34, 197, 94, 0.12);
        }
        .hero-pattern {
            background-color: #0F4A2B;
            background-image: radial-gradient(rgba(132, 204, 22, 0.15) 1px, transparent 1px), radial-gradient(rgba(34, 197, 94, 0.1) 1px, #0F4A2B 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-rider {
            animation: pulse-ring 2s infinite ease-in-out;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased">

    <!-- Quick Role Switcher Banner for Pair Programming Demo -->
    <div class="bg-gradient-to-r from-nutri-900 via-nutri-800 to-nutri-900 text-white text-xs py-1.5 px-4 shadow-inner">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-limey-500 text-nutri-950 uppercase tracking-wider">
                    <i class="fa-solid fa-bolt mr-1"></i> Lipa City Live Mode
                </span>
                <span class="hidden sm:inline text-nutri-200">Switch role for testing:</span>
            </div>
            <div class="flex items-center gap-1.5 overflow-x-auto text-[11px]">
                <span class="text-nutri-300 mr-1 hidden md:inline">Quick Login:</span>
                <a href="{{ route('quick.login', 'admin') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-white/20 transition text-white font-medium">
                    <i class="fa-solid fa-shield-halved text-amber-400 mr-1"></i> Super Admin
                </a>
                <a href="{{ route('quick.login', 'store') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-white/20 transition text-white font-medium">
                    <i class="fa-solid fa-store text-emerald-400 mr-1"></i> Store (Green Bites)
                </a>
                <a href="{{ route('quick.login', 'rider') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-white/20 transition text-white font-medium">
                    <i class="fa-solid fa-motorcycle text-limey-400 mr-1"></i> Rider (Juan)
                </a>
                <a href="{{ route('quick.login', 'customer') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-white/20 transition text-white font-medium">
                    <i class="fa-solid fa-user text-blue-400 mr-1"></i> Customer (VIP)
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative w-12 h-12 rounded-xl overflow-hidden shadow-md group-hover:scale-105 transition-transform duration-300 bg-white p-1 border border-nutri-200">
                        <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="NutriGo Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-2xl font-black tracking-tight text-nutri-900 font-heading">Nutri<span class="text-nutri-600">Go</span></span>
                            <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-limey-400/30 text-nutri-800 uppercase tracking-widest border border-limey-500/40">Lipa</span>
                        </div>
                        <p class="text-[10px] font-semibold text-nutri-600 tracking-wider uppercase">Healthy Food, Anytime</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-nutri-600 font-bold' : 'text-gray-700 hover:text-nutri-600' }} transition">
                        Home
                    </a>
                    <a href="{{ route('users.explore') }}" class="text-sm font-semibold {{ request()->routeIs('users.explore*') ? 'text-nutri-600 font-bold' : 'text-gray-700 hover:text-nutri-600' }} transition flex items-center gap-1.5">
                        <i class="fa-solid fa-utensils text-nutri-500"></i> Healthy Foods & Stores
                    </a>
                    <a href="{{ route('creators.index') }}" class="text-sm font-semibold {{ request()->routeIs('creators*') ? 'text-nutri-600 font-bold' : 'text-gray-700 hover:text-nutri-600' }} transition flex items-center gap-1.5">
                        <i class="fa-solid fa-play text-rose-500"></i> Cooking Vlogs
                    </a>
                    <a href="{{ route('users.subscriptions.index') }}" class="text-sm font-semibold {{ request()->routeIs('users.subscriptions*') ? 'text-nutri-600 font-bold' : 'text-gray-700 hover:text-nutri-600' }} transition flex items-center gap-1.5">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        VIP Meal Prep
                    </a>
                </nav>

                <!-- Actions & Profile -->
                <div class="flex items-center gap-3">
                    <!-- Cart Button -->
                    @php
                        $cartCount = session()->has('cart') ? count(session('cart')) : 0;
                    @endphp
                    <a href="{{ route('users.cart.index') }}" class="relative p-2.5 rounded-xl text-nutri-900 bg-nutri-50 hover:bg-nutri-100 transition border border-nutri-200/80 shadow-sm group">
                        <i class="fa-solid fa-bag-shopping text-lg group-hover:scale-110 transition-transform"></i>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[11px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <!-- Authenticated User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl hover:bg-nutri-50 transition border border-transparent hover:border-nutri-200">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-nutri-600 to-limey-500 flex items-center justify-center text-white font-bold text-sm shadow">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-bold text-gray-900 truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] uppercase font-extrabold text-nutri-600">{{ auth()->user()->role }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </button>

                            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-nutri-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-nutri-800 hover:bg-nutri-50 transition">
                                        <i class="fa-solid fa-gauge-high text-nutri-600"></i> Super Admin Portal
                                    </a>
                                @elseif(auth()->user()->isStore())
                                    <a href="{{ route('store.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-nutri-800 hover:bg-nutri-50 transition">
                                        <i class="fa-solid fa-store text-nutri-600"></i> Store Dashboard
                                    </a>
                                @elseif(auth()->user()->isRider())
                                    <a href="{{ route('riders.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-nutri-800 hover:bg-nutri-50 transition">
                                        <i class="fa-solid fa-motorcycle text-nutri-600"></i> Rider Dashboard & Trips
                                    </a>
                                @endif

                                <a href="{{ route('users.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-nutri-50 transition">
                                    <i class="fa-solid fa-receipt text-nutri-600"></i> My Orders & Live Tracking
                                </a>

                                <a href="{{ route('users.subscriptions.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-nutri-50 transition">
                                    <i class="fa-solid fa-crown text-amber-500"></i> VIP Subscription
                                </a>

                                <div class="border-t border-gray-100 mt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-nutri-900 hover:bg-nutri-100 transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-extrabold text-nutri-950 bg-limey-400 hover:bg-limey-500 shadow-sm transition">
                                Join NutriGo
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- SweetAlert2 Toast Alerts -->
    @include('partials.sweetalert')

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Modern NutriGo Footer -->
    <footer class="bg-nutri-900 text-white pt-16 pb-12 mt-20 border-t border-nutri-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-1">
                            <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="NutriGo Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white font-heading">Nutri<span class="text-limey-400">Go</span></span>
                    </div>
                    <p class="text-xs text-nutri-200 leading-relaxed">
                        Lipa City’s dedicated healthy food, nutrition, and meal prep ecosystem. Empowering local stores, riders, and health-conscious foodies.
                    </p>
                    <div class="flex items-center gap-3 text-nutri-300">
                        <span class="text-xs font-semibold"><i class="fa-solid fa-location-dot text-limey-400 mr-1"></i> Lipa City, Batangas</span>
                    </div>
                </div>

                <!-- Col 2: Healthy Marketplace -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 font-heading">Explore Healthy</h4>
                    <ul class="space-y-2.5 text-xs text-nutri-200">
                        <li><a href="{{ route('users.explore') }}?category=fresh-salads-bowls" class="hover:text-limey-400 transition">Fresh Salads & Bowls</a></li>
                        <li><a href="{{ route('users.explore') }}?category=high-protein-gym-meals" class="hover:text-limey-400 transition">High-Protein Gym Meals</a></li>
                        <li><a href="{{ route('users.explore') }}?category=keto-low-carb" class="hover:text-limey-400 transition">Keto & Low-Carb</a></li>
                        <li><a href="{{ route('users.explore') }}?category=plant-based-vegan" class="hover:text-limey-400 transition">100% Plant-Based & Vegan</a></li>
                        <li><a href="{{ route('users.explore') }}?supplements=1" class="hover:text-limey-400 transition">Certified Supplements Corner</a></li>
                    </ul>
                </div>

                <!-- Col 3: Partners & Community -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 font-heading">Join the Movement</h4>
                    <ul class="space-y-2.5 text-xs text-nutri-200">
                        <li><a href="{{ route('register') }}?tab=store" class="hover:text-limey-400 transition flex items-center gap-1.5"><i class="fa-solid fa-store text-emerald-400"></i> Register Healthy Store</a></li>
                        <li><a href="{{ route('register') }}?tab=rider" class="hover:text-limey-400 transition flex items-center gap-1.5"><i class="fa-solid fa-motorcycle text-limey-400"></i> Apply as Delivery Rider</a></li>
                        <li><a href="{{ route('creators.index') }}" class="hover:text-limey-400 transition flex items-center gap-1.5"><i class="fa-solid fa-video text-rose-400"></i> Partner Cooking Vlogs</a></li>
                        <li><a href="{{ route('users.subscriptions.index') }}" class="hover:text-limey-400 transition flex items-center gap-1.5"><i class="fa-solid fa-crown text-amber-400"></i> VIP Meal Prep Club</a></li>
                    </ul>
                </div>

                <!-- Col 4: Distance & Pricing Standard -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-2 font-heading">Distance Pricing</h4>
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 text-xs text-nutri-200 space-y-1.5">
                        <div class="flex justify-between">
                            <span>Base Fare (First 1.5 km):</span>
                            <span class="font-bold text-white">₱40.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Per succeeding KM:</span>
                            <span class="font-bold text-white">₱10.00 / km</span>
                        </div>
                        <p class="text-[10px] text-nutri-300 pt-1 border-t border-white/10">
                            Accurate point-to-point GPS calculation across all 72 Lipa City barangays.
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-nutri-300 gap-4">
                <p>&copy; {{ date('Y') }} NutriGo Philippines. Lipa City, Batangas. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <span>Designed for NutriGo Ecosystem</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Quick Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-nutri-100 shadow-lg px-4 py-2 flex items-center justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-[10px] font-bold {{ request()->routeIs('home') ? 'text-nutri-600' : 'text-gray-500' }}">
            <i class="fa-solid fa-house text-lg mb-0.5"></i> Home
        </a>
        <a href="{{ route('users.explore') }}" class="flex flex-col items-center text-[10px] font-bold {{ request()->routeIs('users.explore*') ? 'text-nutri-600' : 'text-gray-500' }}">
            <i class="fa-solid fa-magnifying-glass text-lg mb-0.5"></i> Explore
        </a>
        <a href="{{ route('users.cart.index') }}" class="flex flex-col items-center text-[10px] font-bold relative {{ request()->routeIs('users.cart*') ? 'text-nutri-600' : 'text-gray-500' }}">
            <i class="fa-solid fa-bag-shopping text-lg mb-0.5"></i> Cart
            @if($cartCount > 0)
                <span class="absolute -top-1 -right-2 bg-rose-500 text-white text-[9px] font-extrabold w-4 h-4 rounded-full flex items-center justify-center">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('creators.index') }}" class="flex flex-col items-center text-[10px] font-bold {{ request()->routeIs('creators*') ? 'text-nutri-600' : 'text-gray-500' }}">
            <i class="fa-solid fa-play text-lg mb-0.5"></i> Vlogs
        </a>
        <a href="{{ auth()->check() ? route('users.orders.index') : route('login') }}" class="flex flex-col items-center text-[10px] font-bold {{ request()->routeIs('users.orders*') ? 'text-nutri-600' : 'text-gray-500' }}">
            <i class="fa-solid fa-user text-lg mb-0.5"></i> {{ auth()->check() ? 'Orders' : 'Account' }}
        </a>
    </div>

    @stack('scripts')
</body>
</html>
