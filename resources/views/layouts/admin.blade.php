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
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

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
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen flex antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-nutri-950 text-white flex flex-col shrink-0 min-h-screen border-r border-nutri-900 transition-transform duration-300 ease-in-out md:static md:translate-x-0 md:min-h-screen">
        <!-- Brand Header -->
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-1 shadow shrink-0">
                    <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="Logo"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-xl font-black text-white font-heading tracking-tight">Nutri<span
                            class="text-limey-400">Admin</span></span>
                    <p class="text-[10px] text-nutri-300 uppercase font-semibold">Lipa City Control Hub</p>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false"
                class="md:hidden text-gray-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition"
                title="Close Menu">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('superadmin.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.dashboard') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-gauge-high text-sm w-5 text-center"></i> Dashboard
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Vetting & Approvals
            </div>

            <a href="{{ route('superadmin.stores.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.stores*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-store text-sm w-5 text-center"></i> Store Approvals
                </div>
                @php $pendingStore = \App\Models\Store::where('status', 'pending')->count(); @endphp
                @if ($pendingStore > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-nutri-950">{{ $pendingStore }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.riders.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.riders*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-motorcycle text-sm w-5 text-center"></i> Rider Approvals
                </div>
                @php $pendingRiders = \App\Models\Rider::where('status', 'pending')->count(); @endphp
                @if ($pendingRiders > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-nutri-950">{{ $pendingRiders }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.payouts.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.payouts*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-wallet text-sm w-5 text-center"></i> Rider GCash Payouts
                </div>
                @php $pendingPayouts = \App\Models\RiderPayoutRequest::where('status', 'pending')->count(); @endphp
                @if ($pendingPayouts > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400 text-nutri-950">{{ $pendingPayouts }}</span>
                @endif
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Partners & Management
            </div>

            <a href="{{ route('superadmin.reviews.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.reviews*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-star text-sm w-5 text-center"></i> Customer Reviews
                </div>
                @php $activeReviews = \App\Models\CustomerReview::where('is_active', true)->count(); @endphp
                <span
                    class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-limey-400 text-nutri-950" title="Active on Website">{{ $activeReviews }}</span>
            </a>

            <a href="{{ route('superadmin.creators.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.creators*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-video text-sm w-5 text-center"></i> Creator Vlogs & Recipes
            </a>

            <a href="{{ route('superadmin.subscriptions.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('superadmin.subscriptions*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-crown text-sm w-5 text-center"></i> VIP Subscriptions
            </a>
        </nav>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header
            class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition"
                    title="Toggle Menu">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 font-heading truncate">@yield('header_title', 'NutriGo Super Admin')</h2>
            </div>

            <!-- Top Right: Super Admin Profile & Logout -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-2.5 pl-2">
                    <div
                        class="w-9 h-9 rounded-xl bg-limey-400 text-nutri-950 font-black flex items-center justify-center text-xs shadow-sm font-heading shrink-0">
                        AD
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold text-gray-900 leading-tight">
                            {{ auth()->user()->name ?? 'NutriGo Admin' }}</p>
                        <p class="text-[10px] font-semibold text-emerald-700 leading-tight">Super Administrator</p>
                    </div>
                </div>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5 text-xs font-bold cursor-pointer"
                        title="Log Out">
                        <i class="fa-solid fa-power-off text-sm text-rose-500"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- SweetAlert2 Toast Alerts -->
        @include('partials.sweetalert')

        <main class="p-4 sm:p-6 flex-1 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
