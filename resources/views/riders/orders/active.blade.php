@extends('layouts.rider')

@section('title', 'Active Trip #' . $order->order_number . ' | NutriGo Rider')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('riders.dashboard') }}" class="text-xs font-bold text-nutri-700 hover:text-nutri-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Radar
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $order->status_badge_class }}">
            {{ $order->status_label }}
        </span>
    </div>

    <!-- Active Navigation Map Card -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                @php
                    $isPickedUp = in_array($order->status, ['rider_picked_up', 'on_the_way', 'delivered']);
                @endphp
                
                @if(!$isPickedUp)
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                        <i class="fa-solid fa-store mr-1 text-amber-600 animate-pulse"></i> Phase 1: Going to Store for Pickup
                    </span>
                    <h3 class="text-lg font-black font-heading text-gray-900 mt-1">
                        Heading to: <span class="text-emerald-700">{{ $order->store->store_name }}</span>
                    </h3>
                    <p class="text-xs text-gray-500 font-medium">
                        <i class="fa-solid fa-location-dot text-amber-500 mr-1"></i> {{ $order->store->address_line ?? $order->store->barangay }}
                    </p>
                @else
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        <i class="fa-solid fa-motorcycle mr-1 text-emerald-600 animate-pulse"></i> Phase 2: Delivering to Customer
                    </span>
                    <h3 class="text-lg font-black font-heading text-gray-900 mt-1">
                        Delivering to: <span class="text-emerald-700">{{ $order->recipient_name }}</span>
                    </h3>
                    <p class="text-xs text-gray-500 font-medium">
                        <i class="fa-solid fa-house-chimney text-rose-500 mr-1"></i> {{ $order->delivery_address }}
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-xl border border-gray-200">
                    <i class="fa-solid fa-map-location-dot text-emerald-600 mr-1"></i> In-App GPS Active
                </span>
            </div>
        </div>

        <!-- Live Distance & Speed Metric Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 rounded-2xl bg-nutri-950 text-white text-xs">
            <div>
                <span class="text-[10px] text-nutri-400 uppercase font-bold block">Current Step</span>
                <span class="font-extrabold text-limey-400 font-heading">
                    @if($order->status === 'rider_assigned')
                        1. Go to Store
                    @elseif($order->status === 'rider_picked_up')
                        Order in Bag (Ready to Go)
                    @elseif($order->status === 'on_the_way')
                        2. On The Way to Customer
                    @else
                        {{ $order->status_label }}
                    @endif
                </span>
            </div>
            <div>
                <span class="text-[10px] text-nutri-400 uppercase font-bold block">
                    {{ !$isPickedUp ? 'Distance to Store' : 'Distance to Customer' }}
                </span>
                <span class="font-extrabold text-white text-sm" id="rider-live-dist">{{ $order->distance_km }} km</span>
            </div>
            <div>
                <span class="text-[10px] text-nutri-400 uppercase font-bold block">
                    {{ !$isPickedUp ? 'ETA to Store' : 'ETA to Customer' }}
                </span>
                <span class="font-extrabold text-white text-sm" id="rider-live-eta">~{{ $order->estimated_delivery_time_mins }} mins</span>
            </div>
            <div>
                <span class="text-[10px] text-nutri-400 uppercase font-bold block">Earnings for Trip</span>
                <span class="font-extrabold text-limey-400 text-sm">₱{{ number_format($order->delivery_fee, 2) }}</span>
            </div>
        </div>

        <!-- Leaflet Map Container with Floating Recenter Button -->
        <div class="relative w-full rounded-2xl overflow-hidden border border-gray-200 shadow-inner" style="height: 400px; min-height: 400px;">
            <div id="rider-nav-map" class="w-full h-full z-0" style="height: 400px; min-height: 400px;"></div>
            
            <!-- Recenter on Rider GPS -->
            <button type="button" onclick="window.recenterOnRider()" title="Recenter to My Motorcycle"
                    class="absolute bottom-4 right-4 z-[400] px-3.5 py-2 rounded-xl bg-white text-nutri-900 shadow-2xl border border-gray-300 text-xs font-bold flex items-center gap-2 hover:bg-nutri-50 transition cursor-pointer">
                <i class="fa-solid fa-location-crosshairs text-emerald-600 text-sm"></i>
                <span>Center on Me</span>
            </button>
            
            <!-- GPS Signal Status Badge -->
            <div class="absolute top-3 left-14 z-[400] px-3 py-1 rounded-full bg-white/95 backdrop-blur shadow-md border border-emerald-200 text-[11px] font-bold text-nutri-900 flex items-center gap-1.5" id="gps-status-badge">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span id="gps-status-text">Rider GPS Active</span>
            </div>
        </div>
    </div>

    <!-- Action Workflow Buttons based on status -->
    <div class="bg-white p-6 rounded-3xl border border-lime-200 shadow-card space-y-6">
        <h4 class="text-sm font-black font-heading uppercase text-gray-900 tracking-wider pb-3 border-b border-gray-100">
            Trip Step Actions
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Step 1: Picked Up at Store -->
            <div>
                <form action="{{ route('riders.orders.pickedup', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" {{ $order->status !== 'rider_assigned' ? 'disabled' : '' }} class="w-full py-3.5 px-4 rounded-2xl text-xs font-black transition flex items-center justify-center gap-2 {{ $order->status === 'rider_assigned' ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md cursor-pointer' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        <i class="fa-solid fa-box"></i> 1. Picked Up at Store
                    </button>
                </form>
            </div>

            <!-- Step 2: On The Way -->
            <div>
                <form action="{{ route('riders.orders.ontheway', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" {{ $order->status !== 'rider_picked_up' ? 'disabled' : '' }} class="w-full py-3.5 px-4 rounded-2xl text-xs font-black transition flex items-center justify-center gap-2 {{ $order->status === 'rider_picked_up' ? 'bg-teal-600 hover:bg-teal-700 text-white shadow-md cursor-pointer' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        <i class="fa-solid fa-motorcycle"></i> 2. On The Way
                    </button>
                </form>
            </div>

            <!-- Step 3: Delivered -->
            <div x-data="{ openProofModal: false }">
                <button type="button" @click="openProofModal = true" {{ !in_array($order->status, ['rider_picked_up', 'on_the_way']) ? 'disabled' : '' }} class="w-full py-3.5 px-4 rounded-2xl text-xs font-black transition flex items-center justify-center gap-2 {{ in_array($order->status, ['rider_picked_up', 'on_the_way']) ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md cursor-pointer' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                    <i class="fa-solid fa-circle-check"></i> 3. Mark Delivered & Earn ₱{{ number_format($order->delivery_fee, 2) }}
                </button>

                <!-- Delivery Proof Upload Modal -->
                <div x-show="openProofModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" @keydown.escape.window="openProofModal = false">
                    <div @click.outside="openProofModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <h4 class="font-black font-heading text-base text-gray-900">Confirm Order Delivery</h4>
                            <button @click="openProofModal = false" class="text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
                        </div>

                        <p class="text-xs text-gray-600">
                            Marking this order delivered will automatically credit <span class="font-bold text-nutri-800">₱{{ number_format($order->delivery_fee, 2) }}</span> to your NutriGo In-App Wallet!
                        </p>

                        <form action="{{ route('riders.orders.delivered', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Upload Delivery Doorstep Photo (Optional)</label>
                                <input type="file" name="rider_proof_image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-emerald-600 file:text-white">
                            </div>

                            <button type="submit" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-md transition font-heading cursor-pointer">
                                Complete Delivery & Credit Wallet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Drop-off Customer & Landmark Info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Store Pickup Address -->
        <div class="bg-white p-6 rounded-3xl border {{ !$isPickedUp ? 'border-amber-300 ring-2 ring-amber-100' : 'border-gray-200' }} shadow-card space-y-2 text-xs text-gray-700">
            <div class="flex items-center justify-between">
                <span class="font-bold uppercase text-gray-400 block">Pickup Store:</span>
                @if(!$isPickedUp)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">Current Destination</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-gray-100 text-gray-600"><i class="fa-solid fa-check mr-0.5"></i> Picked Up</span>
                @endif
            </div>
            <h4 class="text-base font-black text-gray-900 font-heading">{{ $order->store->store_name }}</h4>
            <p><i class="fa-solid fa-location-dot text-amber-600 mr-1"></i> {{ $order->store->address_line ?? $order->store->barangay }}</p>
            <p><i class="fa-solid fa-phone text-gray-400 mr-1"></i> {{ $order->store->phone }}</p>
        </div>

        <!-- Customer Drop-off Address -->
        <div class="bg-white p-6 rounded-3xl border {{ $isPickedUp ? 'border-emerald-300 ring-2 ring-emerald-100' : 'border-gray-200' }} shadow-card space-y-2 text-xs text-gray-700">
            <div class="flex items-center justify-between">
                <span class="font-bold uppercase text-gray-400 block">Customer Destination:</span>
                @if($isPickedUp)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Current Destination</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-gray-100 text-gray-500">Step 2 Destination</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <h4 class="text-base font-black text-gray-900 font-heading">{{ $order->recipient_name }}</h4>
                <a href="tel:{{ $order->recipient_phone }}" class="px-2.5 py-1 rounded-lg bg-nutri-50 text-nutri-700 font-bold hover:bg-nutri-100 transition">
                    <i class="fa-solid fa-phone mr-1"></i> Call
                </a>
            </div>
            <p><i class="fa-solid fa-house text-rose-500 mr-1"></i> {{ $order->delivery_address }}</p>
            @if($order->delivery_landmark)
                <p class="p-2 rounded-xl bg-amber-50 text-amber-900 font-medium">
                    📍 <strong>Landmark:</strong> {{ $order->delivery_landmark }}
                </p>
            @endif
            @if($order->delivery_notes)
                <p class="p-2 rounded-xl bg-gray-50 text-gray-700 italic">
                    📝 <strong>Notes:</strong> "{{ $order->delivery_notes }}"
                </p>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const storeLat = {{ $order->store->latitude }};
        const storeLng = {{ $order->store->longitude }};
        const destLat = {{ $order->delivery_latitude }};
        const destLng = {{ $order->delivery_longitude }};
        const isPickedUp = {{ in_array($order->status, ['rider_picked_up', 'on_the_way', 'delivered']) ? 'true' : 'false' }};
        // Phase 1 (not picked up yet): Target is STORE
        // Phase 2 (picked up / on the way): Target is CUSTOMER
        const targetLat = isPickedUp ? destLat : storeLat;
        const targetLng = isPickedUp ? destLng : storeLng;

        // Initial rider position estimate (near store or mid-point)
        let riderLat = storeLat;
        let riderLng = storeLng;

        const initialCenterLat = (riderLat + targetLat) / 2;
        const initialCenterLng = (riderLng + targetLng) / 2;

        const map = L.map('rider-nav-map').setView([initialCenterLat, initialCenterLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        setTimeout(function() {
            map.invalidateSize();
        }, 250);

        // 1. Store Pin (Store Logo or Green/Amber Store Icon)
        @php
            $hasStoreLogo = !empty($order->store->logo);
            $storeLogoUrl = $order->store->logo_url;
        @endphp
        const storeIcon = L.divIcon({
            className: 'border-0 bg-transparent',
            html: !isPickedUp 
                ? ({!! json_encode(
                    $hasStoreLogo
                        ? '<div class="relative flex items-center justify-center"><div class="absolute w-12 h-12 rounded-full bg-amber-400/80 animate-ping"></div><div class="relative w-10 h-10 rounded-full bg-white shadow-xl border-2 border-amber-500 p-0.5 overflow-hidden flex items-center justify-center"><img src="' . e($storeLogoUrl) . '" alt="' . e($order->store->store_name) . '" class="w-full h-full rounded-full object-cover"></div></div>'
                        : '<div class="relative flex items-center justify-center"><div class="absolute w-12 h-12 rounded-full bg-amber-400/80 animate-ping"></div><div class="relative w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-lg border-2 border-white text-base"><i class="fa-solid fa-store"></i></div></div>'
                ) !!})
                : ({!! json_encode(
                    $hasStoreLogo
                        ? '<div class="w-10 h-10 rounded-full bg-white shadow-xl border-2 border-emerald-600 p-0.5 overflow-hidden flex items-center justify-center"><img src="' . e($storeLogoUrl) . '" alt="' . e($order->store->store_name) . '" class="w-full h-full rounded-full object-cover"></div>'
                        : '<div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white text-base"><i class="fa-solid fa-store"></i></div>'
                ) !!}),
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map)
            .bindPopup("<b>Pickup Store</b><br>{{ addslashes($order->store->store_name) }}<br><small>{{ !$isPickedUp ? '📍 Current Destination (Step 1)' : '✓ Picked Up' }}</small>");

        // 2. Customer Destination Pin (Red/Pink Location Dot or Highlighted if active target)
        const destIcon = L.divIcon({
            html: isPickedUp
                ? '<div class="relative flex items-center justify-center"><div class="absolute w-10 h-10 rounded-full bg-rose-400/80 animate-ping"></div><div class="relative w-9 h-9 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-lg border-2 border-white"><i class="fa-solid fa-location-dot text-sm"></i></div></div>'
                : '<div class="w-8 h-8 rounded-full bg-gray-500 text-white flex items-center justify-center shadow-lg border-2 border-white opacity-80"><i class="fa-solid fa-location-dot text-xs"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });
        L.marker([destLat, destLng], { icon: destIcon }).addTo(map)
            .bindPopup("<b>Customer Drop-off</b><br>{{ $order->recipient_name }}<br><small>{{ $isPickedUp ? '📍 Current Destination (Step 2)' : 'Step 2 Drop-off' }}</small>");

        // 3. Live Rider Marker (Motorcycle Badge with Pulsing Radar Effect)
        const riderIcon = L.divIcon({
            html: '<div class="relative flex items-center justify-center"><div class="absolute w-10 h-10 rounded-full bg-limey-400/80 animate-ping"></div><div class="relative w-10 h-10 rounded-full bg-nutri-900 border-2 border-limey-400 text-limey-400 flex items-center justify-center shadow-2xl font-black"><i class="fa-solid fa-motorcycle text-base"></i></div></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        const riderMarker = L.marker([riderLat, riderLng], { 
            icon: riderIcon,
            zIndexOffset: 1000 
        }).addTo(map).bindPopup("<b>You (NutriRider)</b><br>Live GPS Location").openPopup();

        // Dedicated LayerGroup for the Route - exactly ONE single road line
        const routeLayerGroup = L.layerGroup().addTo(map);

        // Function to draw road-following route from Rider -> Active Target (Store or Customer)
        async function fetchRoadRoute(fromLat, fromLng, toLat, toLng) {
            routeLayerGroup.clearLayers();

            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    const coordinates = data.routes[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
                    const remainingKm = (data.routes[0].distance / 1000).toFixed(2);
                    const remainingMins = Math.round(data.routes[0].duration / 60);

                    // Update live metrics on screen
                    const distElem = document.getElementById('rider-live-dist');
                    const etaElem = document.getElementById('rider-live-eta');
                    if (distElem) distElem.innerText = remainingKm + ' km';
                    if (etaElem) etaElem.innerText = '~' + Math.max(1, remainingMins) + ' mins';

                    // Draw Single crisp solid road line (amber if going to store, emerald if going to customer)
                    const routeColor = isPickedUp ? '#16A34A' : '#D97706';
                    const singleRoadLine = L.polyline(coordinates, {
                        color: routeColor,
                        weight: 5,
                        opacity: 0.95,
                        lineCap: 'round',
                        lineJoin: 'round'
                    });
                    routeLayerGroup.addLayer(singleRoadLine);
                    return coordinates;
                }
            } catch (err) {
                console.warn('Road routing service error:', err);
            }
        }

        // Center on Rider Handler
        window.recenterOnRider = function() {
            map.flyTo([riderLat, riderLng], 16, { animate: true, duration: 1 });
            riderMarker.openPopup();
        };

        // Live Server GPS Sync
        let lastSyncTime = 0;
        function syncRiderGpsToServer(lat, lng) {
            const now = Date.now();
            if (now - lastSyncTime < 3000) return; // Sync every 3s
            lastSyncTime = now;

            fetch('{{ route('riders.update.location') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ lat: lat, lng: lng })
            })
            .then(r => r.json())
            .then(data => {
                const statusText = document.getElementById('gps-status-text');
                if (statusText) statusText.innerText = 'Rider GPS: Live & Synced';
            })
            .catch(err => console.warn('Rider GPS server sync error:', err));
        }

        // Real-Time Browser GPS Geolocation Tracking
        if (navigator.geolocation) {
            // Watch rider position continuously
            navigator.geolocation.watchPosition(
                function(pos) {
                    riderLat = pos.coords.latitude;
                    riderLng = pos.coords.longitude;

                    // Update Rider Marker position on Rider's Map
                    riderMarker.setLatLng([riderLat, riderLng]);

                    // Recalculate road route from current rider location to ACTIVE TARGET (store or customer)
                    fetchRoadRoute(riderLat, riderLng, targetLat, targetLng);

                    // Sync live GPS coordinates to database so customer sees real-time movement!
                    syncRiderGpsToServer(riderLat, riderLng);
                },
                function(err) {
                    console.warn('Rider GPS error:', err);
                    const statusText = document.getElementById('gps-status-text');
                    if (statusText) statusText.innerText = 'GPS Signal Weak';
                    
                    // Fallback route from rider estimate to active target
                    fetchRoadRoute(riderLat, riderLng, targetLat, targetLng);
                },
                { enableHighAccuracy: true, maximumAge: 2000, timeout: 10000 }
            );
        } else {
            // Fallback initial route
            fetchRoadRoute(riderLat, riderLng, targetLat, targetLng);
        }
    });
</script>
@endpush

