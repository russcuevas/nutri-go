<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Healthy Store Portal | NutriGo Lipa')</title>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen flex antialiased">

    <!-- Store Sidebar -->
    <aside
        class="w-64 bg-nutri-900 text-white flex flex-col shrink-0 hidden md:flex min-h-screen sticky top-0 border-r border-nutri-800">
        <!-- Store Brand Header -->
        <div class="p-6 border-b border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-1 shadow">
                <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <span class="text-lg font-black text-white font-heading tracking-tight">Store<span
                        class="text-limey-400">Portal</span></span>
                <p class="text-[10px] text-nutri-300 truncate max-w-[130px] font-semibold">
                    {{ auth()->user()->store->store_name ?? 'Healthy Partner' }}</p>
            </div>
        </div>

        <!-- Store Open / Close Status Badge -->
        @php $store = auth()->user()->store; @endphp
        @if ($store)
            <div
                class="p-4 mx-4 my-3 rounded-2xl {{ $store->is_open ? 'bg-emerald-500/20 border-emerald-400/40 text-emerald-200' : 'bg-rose-500/20 border-rose-400/40 text-rose-200' }} border flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span
                        class="w-2.5 h-2.5 rounded-full {{ $store->is_open ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                    <span class="font-bold">{{ $store->is_open ? 'Accepting Orders' : 'Store Closed' }}</span>
                </div>
                <form method="POST" action="{{ route('store.toggle.status') }}">
                    @csrf
                    <button type="submit"
                        class="px-2 py-0.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold transition">
                        Toggle
                    </button>
                </form>
            </div>
        @endif

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-3 space-y-1.5 overflow-y-auto">
            <a href="{{ route('store.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('store.dashboard') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-chart-pie text-sm w-5 text-center"></i> Store Dashboard
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Orders & GCash Verification
            </div>

            <a href="{{ route('store.orders.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('store.orders*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-receipt text-sm w-5 text-center"></i> Orders Queue
                </div>
                @php $pendingStoreOrders = $store ? \App\Models\Order::where('store_id', $store->id)->where('status', 'pending_store')->count() : 0; @endphp
                @if ($pendingStoreOrders > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-nutri-950 animate-bounce">{{ $pendingStoreOrders }}</span>
                @endif
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Menu & Nutrition Facts
            </div>

            <a href="{{ route('store.products.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('store.products*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-apple-whole text-sm w-5 text-center"></i> Healthy Foods & Calories
            </a>

            <a href="{{ route('store.products.create') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-nutri-200 hover:bg-white/10 hover:text-white transition">
                <i class="fa-solid fa-plus-circle text-sm w-5 text-center text-limey-400"></i> Add New Healthy Item
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-widest text-nutri-400">
                Settings
            </div>

            <a href="{{ route('store.profile.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('store.profile*') ? 'bg-limey-400 text-nutri-950 shadow' : 'text-nutri-200 hover:bg-white/10 hover:text-white' }} transition">
                <i class="fa-solid fa-qrcode text-sm w-5 text-center"></i> Store & GCash QR Setup
            </a>

        </nav>

        <!-- Store Owner Profile Footer -->
        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-8 h-8 rounded-lg bg-emerald-500 text-white font-black flex items-center justify-center text-xs">
                    ST
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-nutri-300">Brgy. {{ $store->barangay ?? 'Lipa' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-rose-400 hover:bg-rose-500/20 transition"
                    title="Log Out">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900 font-heading">@yield('header_title', 'Store Management')</h2>
                @if ($store && $store->status === 'pending')
                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                        <i class="fa-solid fa-clock mr-1"></i> Awaiting Admin Healthy Food Vetting
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('store.orders.index') }}?status=pending_store"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-500 text-white shadow-sm hover:bg-amber-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-bell"></i>
                    <span>Verify GCash Orders</span>
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
