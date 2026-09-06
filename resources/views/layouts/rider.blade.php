<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rider Delivery Portal | NutriGo Lipa')</title>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col antialiased">

    <!-- Rider Top Navbar -->
    <header class="bg-nutri-900 text-white sticky top-0 z-40 shadow-md">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Rider Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-1 shadow">
                    <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-black text-white font-heading tracking-tight">Nutri<span class="text-limey-400">Rider</span></span>
                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-white/10 uppercase tracking-widest text-limey-300">Lipa City</span>
                    </div>
                    <p class="text-[11px] text-nutri-200">{{ auth()->user()->name }} ({{ auth()->user()->rider->plate_number ?? 'Rider' }})</p>
                </div>
            </div>

            <!-- Right: Wallet & Duty Toggle -->
            @php 
                $rider = auth()->user()->rider; 
                $wallet = $rider?->wallet;
            @endphp
            <div class="flex items-center gap-3">
                <!-- Wallet Balance Pill -->
                <a href="{{ route('riders.wallet.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 transition text-xs">
                    <i class="fa-solid fa-wallet text-limey-400"></i>
                    <span class="font-extrabold text-limey-300">₱{{ number_format($wallet->balance ?? 0, 2) }}</span>
                </a>

                <!-- Duty Toggle -->
                @if($rider)
                    <form method="POST" action="{{ route('riders.toggle.duty') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $rider->is_online ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-gray-600 hover:bg-gray-500 text-gray-200' }}">
                            <span class="w-2 h-2 rounded-full {{ $rider->is_online ? 'bg-white animate-ping' : 'bg-gray-400' }}"></span>
                            <span>{{ $rider->is_online ? 'Online' : 'Offline' }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <!-- Rider Sub-Nav Links -->
    <div class="bg-nutri-950 text-white border-b border-nutri-800 text-xs font-semibold py-2 px-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('riders.dashboard') }}" class="{{ request()->routeIs('riders.dashboard') ? 'text-limey-400 font-bold' : 'text-gray-300 hover:text-white' }} flex items-center gap-1.5">
                    <i class="fa-solid fa-radar"></i> Available Lipa Trips
                </a>
                <a href="{{ route('riders.wallet.index') }}" class="{{ request()->routeIs('riders.wallet*') ? 'text-limey-400 font-bold' : 'text-gray-300 hover:text-white' }} flex items-center gap-1.5">
                    <i class="fa-solid fa-money-bill-transfer"></i> Earnings & GCash Cashout
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-rose-300 hover:text-rose-100 flex items-center gap-1.5 cursor-pointer font-bold text-xs transition hover:underline">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Toast Alerts -->
    @include('partials.sweetalert')

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 py-6 w-full flex-grow">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
