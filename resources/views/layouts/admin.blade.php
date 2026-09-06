<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Portal | NutriGo Lipa')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/nutrigo-logo.jpg') }}">

    <!-- Google Fonts -->
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
                            500: '#22C55E',
                            600: '#16A34A',
                            800: '#166534',
                            900: '#0F4A2B',
                            950: '#052e16',
                        },
                        limey: {
                            400: '#A3E635',
                            500: '#84CC16',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex antialiased">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-nutri-950 text-white flex flex-col shrink-0 hidden md:flex min-h-screen sticky top-0 border-r border-nutri-900">
        <!-- Brand Header -->
        <div class="p-6 border-b border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-1 shadow">
                <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <span class="text-xl font-black text-white font-heading tracking-tight">Nutri<span class="text-limey-400">Admin</span></span>
                <p class="text-[10px] text-nutri-300 uppercase font-semibold">Lipa City Control Hub</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.dashboard') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-gauge-high text-sm w-5 text-center"></i> Dashboard Overview
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Vetting & Approvals
            </div>

            <a href="{{ route('superadmin.stores.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.stores*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-store text-sm w-5 text-center"></i> Store Approvals
                </div>
                @php $pendingStore = \App\Models\Store::where('status', 'pending')->count(); @endphp
                @if($pendingStore > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-nutri-950">{{ $pendingStore }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.riders.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.riders*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-motorcycle text-sm w-5 text-center"></i> Rider Approvals
                </div>
                @php $pendingRiders = \App\Models\Rider::where('status', 'pending')->count(); @endphp
                @if($pendingRiders > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-nutri-950">{{ $pendingRiders }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.payouts.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.payouts*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-wallet text-sm w-5 text-center"></i> Rider GCash Payouts
                </div>
                @php $pendingPayouts = \App\Models\RiderPayoutRequest::where('status', 'pending')->count(); @endphp
                @if($pendingPayouts > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400 text-nutri-950">{{ $pendingPayouts }}</span>
                @endif
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Partners & Ecosystem
            </div>

            <a href="{{ route('superadmin.creators.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.creators*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-video text-sm w-5 text-center"></i> Creator Vlogs & Recipes
            </a>

            <a href="{{ route('superadmin.subscriptions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.subscriptions*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-crown text-sm w-5 text-center"></i> VIP Subscriptions
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Configuration
            </div>

            <a href="{{ route('superadmin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.settings*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-calculator text-sm w-5 text-center"></i> Distance Rates & Pricing
            </a>

            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-nutri-300 hover:bg-white/10 hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-sm w-5 text-center"></i> Visit Marketplace
            </a>
        </nav>

        <!-- Admin Profile Footer -->
        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-limey-400 text-nutri-950 font-black flex items-center justify-center text-xs">
                    AD
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-nutri-300">Super Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-400 hover:bg-rose-500/20 transition" title="Log Out">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900 font-heading">@yield('header_title', 'NutriGo Super Admin')</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.stores.index') }}?status=pending" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-nutri-50 text-nutri-800 border border-nutri-200 hover:bg-nutri-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-store text-nutri-600"></i>
                    <span>Stores ({{ \App\Models\Store::where('status', 'pending')->count() }})</span>
                </a>
                <a href="{{ route('superadmin.riders.index') }}?status=pending" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-lime-50 text-lime-800 border border-lime-200 hover:bg-lime-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-motorcycle text-lime-600"></i>
                    <span>Riders ({{ \App\Models\Rider::where('status', 'pending')->count() }})</span>
                </a>
            </div>
        </header>

        <!-- SweetAlert2 Toast Alerts -->
        @include('partials.sweetalert')

        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
