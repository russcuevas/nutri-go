@extends('layouts.app')

@section('title', 'Checkout & Map Pin Drop | NutriGo Lipa')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
        barangay: '{{ $defaultBarangay }}',
        address: '{{ $user->address ?? '' }}',
        lat: {{ $defaultCoords['lat'] }},
        lng: {{ $defaultCoords['lng'] }},
        storeLat: {{ $store->latitude }},
        storeLng: {{ $store->longitude }},
        distance: {{ $distanceKm }},
        baseFare: {{ $feeData['base_fare'] }},
        gasFee: {{ $feeData['gas_allowance_fee'] }},
        deliveryFee: {{ $feeData['delivery_fee'] }},
        etaMins: {{ $feeData['estimated_time_mins'] ?? 25 }},
        subtotal: {{ $subtotal }},
        platformFee: 10.00,
        discount: {{ $discount }},
        isVip: {{ $isVip ? 'true' : 'false' }},
        paymentMethod: 'gcash',
        deliveryType: 'immediate',
        isLocating: false,
    
        updateFareAndLocation(data) {
            this.lat = data.lat;
            this.lng = data.lng;
    
            if (data.barangay) {
                this.barangay = data.barangay;
            }
            if (data.address) {
                this.address = data.address;
            }
    
            const dist = parseFloat(data.distance);
            this.distance = dist;
            this.baseFare = 40.00;
            const succeedingDist = Math.max(0, dist - 1.5);
            this.gasFee = Math.round(succeedingDist * 10.00 * 100) / 100;
            this.deliveryFee = Math.round((this.baseFare + this.gasFee) * 100) / 100;
            this.etaMins = Math.max(15, Math.round(15 + (dist * 3.5)));
    
            if (this.isVip) {
                this.discount = Math.min(50.00, this.deliveryFee);
            }
        }
    }"
        @update-checkout-fare.window="updateFareAndLocation($event.detail)">

        <div class="mb-8">
            <h1 class="text-3xl font-black font-heading text-gray-900 tracking-tight">Checkout & Delivery Pin</h1>
            <p class="text-xs text-gray-500 mt-1">Pinpoint your exact delivery location on the map. Distance, live fare, and
                ETA calculate dynamically.</p>
        </div>

        <form action="{{ route('users.checkout.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="delivery_latitude" :value="lat">
            <input type="hidden" name="delivery_longitude" :value="lng">
            <input type="hidden" name="delivery_barangay" :value="barangay">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Left: Address, Map Pin & Delivery Timing -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Section 1: Interactive Map Pin -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-600">Step
                                    1</span>
                                <h3 class="text-lg font-black text-gray-900 font-heading">Pinpoint Your Exact Delivery
                                    Location</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Auto Location GPS Button -->
                                <button type="button" onclick="window.useCurrentGpsLocation()" id="btn-auto-gps"
                                    class="px-3.5 py-1.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer transform hover:scale-[1.02] border border-nutri-700">
                                    <i class="fa-solid fa-crosshairs text-limey-400 text-sm"></i>
                                    <span>Auto Location (GPS)</span>
                                </button>
                            </div>
                        </div>

                        <!-- Leaflet Interactive Map Container with Floating GPS trigger -->
                        <div class="relative w-full h-80 rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                            <div id="checkout-map" class="w-full h-full z-0"></div>
                            <button type="button" onclick="window.useCurrentGpsLocation()"
                                title="Detect My Current GPS Location"
                                class="absolute bottom-4 right-4 z-[400] w-11 h-11 rounded-full bg-white text-nutri-900 shadow-2xl border-2 border-emerald-500 flex items-center justify-center hover:bg-emerald-50 transition cursor-pointer transform hover:scale-110">
                                <i class="fa-solid fa-crosshairs text-lg text-emerald-600"></i>
                            </button>
                        </div>

                        <!-- Customer Address Details Form -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Recipient
                                    Name *</label>
                                <input type="text" name="recipient_name" required
                                    value="{{ auth()->user()->name ?? '' }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500"
                                    placeholder="e.g. Maria Clara">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Recipient
                                    Mobile Phone *</label>
                                <input type="tel" name="recipient_phone" required
                                    value="{{ auth()->user()->phone ?? '' }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500"
                                    placeholder="09171234567">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 flex items-center justify-between">
                                <span>Address</span>
                                <span class="text-[10px] text-gray-400 font-medium normal-case flex items-center gap-1"><i
                                        class="fa-solid fa-lock text-[9px]"></i> Auto-detected from map pin</span>
                            </label>
                            <input type="text" name="delivery_address" id="delivery_address" x-model="address" readonly
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 text-xs outline-none font-semibold cursor-default"
                                placeholder="Pin location on map to detect address">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Delivery
                                    Landmark *</label>
                                <input type="text" name="delivery_landmark" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500"
                                    placeholder="e.g Such as your House no. etc ..">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Rider
                                    Notes / Instructions (Optional)</label>
                                <input type="text" name="delivery_notes"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500"
                                    placeholder="e.g. Leave at guard house / Ring doorbell 2x">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Delivery Schedule Timing -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-600">Step 2</span>
                            <h3 class="text-lg font-black text-gray-900 font-heading">Delivery Timing Option</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition"
                                :class="deliveryType === 'immediate' ? 'border-nutri-600 bg-nutri-50/50 ring-1 ring-nutri-600' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="delivery_type" value="immediate" x-model="deliveryType"
                                    class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-limey-400 text-nutri-950 flex items-center justify-center font-bold">
                                        <i class="fa-solid fa-bolt text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-xs font-heading">Deliver Immediately</p>
                                        <p class="text-[11px] text-gray-500">Cook right away & dispatch rider</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition"
                                :class="deliveryType === 'scheduled' ? 'border-nutri-600 bg-nutri-50/50 ring-1 ring-nutri-600' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="delivery_type" value="scheduled" x-model="deliveryType"
                                    class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-nutri-900 text-white flex items-center justify-center font-bold">
                                        <i class="fa-regular fa-clock text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-xs font-heading">Scheduled Delivery</p>
                                        <p class="text-[11px] text-gray-500">Set scheduled time for lunch / dinner</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div x-show="deliveryType === 'scheduled'" x-cloak class="pt-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Select Scheduled Delivery
                                Date & Time</label>
                            <input type="datetime-local" name="scheduled_for"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500">
                        </div>
                    </div>

                    <!-- Section 3: Payment Verification Mode -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-600">Step 3</span>
                            <h3 class="text-lg font-black text-gray-900 font-heading">Payment Method</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition"
                                :class="paymentMethod === 'gcash' ? 'border-blue-600 bg-blue-50/40 ring-1 ring-blue-600' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="gcash" x-model="paymentMethod"
                                    class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                                        G
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-xs font-heading">GCash (Manual Proof)</p>
                                        <p class="text-[11px] text-gray-500">Send to Store GCash & upload reference</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition"
                                :class="paymentMethod === 'cod' ?
                                    'border-nutri-600 bg-nutri-50/40 ring-1 ring-nutri-600' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod"
                                    class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                                        ₱
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-xs font-heading">Cash on Delivery (COD)</p>
                                        <p class="text-[11px] text-gray-500">Pay cash directly upon food arrival</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- GCash Upload Box -->
                        <div x-show="paymentMethod === 'gcash'" x-cloak
                            class="p-5 rounded-2xl bg-blue-50/60 border border-blue-200 space-y-4 text-xs">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-3 bg-white rounded-xl border border-blue-100 shadow-sm"
                                x-data="{ showQrModal: false }">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase text-blue-600 tracking-wider">Store
                                        GCash Account</span>
                                    <p class="font-black text-sm text-gray-900 font-heading">{{ $store->gcash_name }}</p>
                                    <p class="text-xs font-mono font-bold text-blue-700">{{ $store->gcash_number }}</p>
                                </div>
                                @if ($store->gcash_qr_url)
                                    <div class="flex items-center gap-2">
                                        <div @click="showQrModal = true"
                                            class="w-16 h-16 rounded-xl overflow-hidden border border-blue-200 shrink-0 bg-white p-1 shadow-sm cursor-pointer hover:opacity-90 transition group relative">
                                            <img src="{{ $store->gcash_qr_url }}" alt="QR Code"
                                                class="w-full h-full object-contain">
                                            <div
                                                class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[10px] font-bold rounded-xl transition">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </div>
                                        </div>
                                        <button type="button" @click="showQrModal = true"
                                            class="text-[11px] text-blue-600 font-bold hover:underline">
                                            Scan / View QR
                                        </button>
                                    </div>

                                    <!-- QR Modal -->
                                    <div x-show="showQrModal" x-cloak @click.self="showQrModal = false"
                                        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                                        <div
                                            class="bg-white rounded-3xl p-6 max-w-sm w-full space-y-4 shadow-2xl text-center relative animate-scale-in">
                                            <button type="button" @click="showQrModal = false"
                                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                            <h4 class="font-black text-base text-gray-900 font-heading">
                                                {{ $store->store_name }} GCash QR</h4>
                                            <p class="text-xs text-gray-500">Scan using GCash App to pay direct to store
                                            </p>
                                            <div
                                                class="w-64 h-64 mx-auto rounded-2xl border-2 border-blue-200 overflow-hidden bg-gray-50 p-2">
                                                <img src="{{ $store->gcash_qr_url }}" alt="Store GCash QR"
                                                    class="w-full h-full object-contain">
                                            </div>
                                            <div
                                                class="text-xs bg-blue-50 p-3 rounded-xl text-blue-950 font-mono font-bold">
                                                {{ $store->gcash_name }} ({{ $store->gcash_number }})
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">13-Digit GCash
                                        Reference No. *</label>
                                    <input type="text" name="payment_reference_no" placeholder="e.g. 1002938475819"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs bg-white outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Upload Receipt
                                        Screenshot *</label>
                                    <input type="file" name="payment_proof_image" accept="image/*"
                                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Order Summary & Real-time Distance Transparent Breakdown -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-nutri-200 shadow-card space-y-4 sticky top-24">
                        <h3
                            class="text-xs font-extrabold uppercase tracking-widest text-nutri-900 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-nutri-600"></i> Live Fare Summary
                            </span>
                            <span
                                class="text-[10px] text-emerald-700 bg-emerald-100 font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Realtime GPS
                            </span>
                        </h3>

                        <!-- Realtime Distance Calculation Box -->
                        <div
                            class="p-4 rounded-2xl bg-nutri-50 border border-nutri-200 space-y-2.5 text-xs transition-all duration-300">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Point-to-Point Distance:</span>
                                <span
                                    class="font-extrabold text-sm text-gray-900 bg-white px-2.5 py-0.5 rounded-lg border border-nutri-200 shadow-sm"
                                    x-text="distance.toFixed(2) + ' km'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Estimated Delivery ETA:</span>
                                <span
                                    class="font-bold text-xs text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200"
                                    x-text="'~' + etaMins + ' mins'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Base Fare (First 1.5km):</span>
                                <span class="font-bold text-gray-900" x-text="'₱' + baseFare.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Distance Gas Rate (₱10/km):</span>
                                <span class="font-bold text-gray-900" x-text="'₱' + gasFee.toFixed(2)"></span>
                            </div>
                            <div
                                class="flex justify-between pt-2 border-t border-nutri-200 font-black text-sm text-nutri-800">
                                <span>Delivery Total:</span>
                                <span class="text-emerald-700 font-extrabold text-base"
                                    x-text="'₱' + deliveryFee.toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Items Summary -->
                        <div class="space-y-2 pt-2 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Food Items Subtotal:</span>
                                <span class="font-bold text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Platform Fee:</span>
                                <span class="font-bold text-gray-900">₱10.00</span>
                            </div>
                            @if ($isVip)
                                <div class="flex justify-between text-amber-600 font-bold">
                                    <span>👑 VIP Free Delivery Perk:</span>
                                    <span x-text="'-₱' + discount.toFixed(2)"></span>
                                </div>
                            @endif

                            <div
                                class="flex justify-between pt-3 border-t border-gray-200 text-base font-black text-gray-900 font-heading">
                                <span>Grand Total:</span>
                                <span class="text-nutri-700 text-xl font-black"
                                    x-text="'₱' + (subtotal + deliveryFee + platformFee - discount).toFixed(2)"></span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-2xl font-black text-sm transition font-heading flex items-center justify-center gap-2 bg-limey-400 hover:bg-limey-500 text-nutri-950 shadow-md transform hover:scale-[1.02] cursor-pointer">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-lock"></i> Place Order &
                                Dispatch</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allBarangays = @json($barangayCoords ?? []);
            const defaultLat = {{ $defaultCoords['lat'] }};
            const defaultLng = {{ $defaultCoords['lng'] }};
            const storeLat = {{ $store->latitude }};
            const storeLng = {{ $store->longitude }};

            // Haversine Distance Fallback Formula
            function computeDirectRoadDistance(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const straight = R * c;
                return Math.max(0.8, Math.round(straight * 1.25 * 100) / 100);
            }

            // Nearest Barangay Calculator
            function findNearestBarangay(lat, lng) {
                let closestName = 'Poblacion Barangay 1';
                let minDistance = Infinity;

                for (const [name, coords] of Object.entries(allBarangays)) {
                    const dLat = coords.lat - lat;
                    const dLng = coords.lng - lng;
                    const distSq = (dLat * dLat) + (dLng * dLng);
                    if (distSq < minDistance) {
                        minDistance = distSq;
                        closestName = name;
                    }
                }
                return closestName;
            }

            // Reverse Geocoding via Nominatim
            let geocodeTimeout = null;

            function reverseGeocodeAddress(lat, lng, callback) {
                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(async () => {
                    try {
                        const url =
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
                        const res = await fetch(url, {
                            headers: {
                                'Accept-Language': 'en'
                            }
                        });
                        const json = await res.json();
                        if (json && json.display_name) {
                            const addr = json.address || {};
                            const road = addr.road || addr.pedestrian || addr.suburb || addr
                                .neighbourhood || '';
                            const city = addr.city || 'Lipa City';
                            const fullAddr = road ? `${road}, ${city}` : json.display_name;
                            callback(fullAddr);
                        }
                    } catch (e) {
                        const nearest = findNearestBarangay(lat, lng);
                        callback(`Near ${nearest}, Lipa City`);
                    }
                }, 400);
            }

            // Initialize Leaflet Map centered in Lipa City
            const map = L.map('checkout-map').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Store Marker (Store Logo or Green Fallback Icon)
            @php
                $hasStoreLogo = !empty($store->logo);
                $storeLogoUrl = $store->logo_url;
            @endphp
            const storeIcon = L.divIcon({
                className: 'border-0 bg-transparent',
                html: {!! json_encode(
                    $hasStoreLogo 
                        ? '<div class="relative flex items-center justify-center"><div class="w-10 h-10 rounded-full bg-white shadow-xl border-2 border-emerald-600 p-0.5 overflow-hidden flex items-center justify-center"><img src="' . e($storeLogoUrl) . '" alt="' . e($store->store_name) . '" class="w-full h-full rounded-full object-cover" onerror="this.onerror=null; this.parentElement.outerHTML=\'<div class=\\\'w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white text-base\\\'><i class=\\\'fa-solid fa-store\\\'></i></div>\';"></div></div>'
                        : '<div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white text-base"><i class="fa-solid fa-store"></i></div>'
                ) !!},
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            L.marker([storeLat, storeLng], {
                    icon: storeIcon
                }).addTo(map)
                .bindPopup("<b>{{ $store->store_name }}</b><br>Pickup Store");

            // Customer Delivery Marker (Draggable Red Pin)
            const customerIcon = L.divIcon({
                html: '<div class="w-9 h-9 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-xl border-2 border-white animate-bounce"><i class="fa-solid fa-location-dot text-sm"></i></div>',
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });

            const customerMarker = L.marker([defaultLat, defaultLng], {
                draggable: true,
                icon: customerIcon
            }).addTo(map).bindPopup("<b>Your Delivery Pin</b><br>Drag to your exact location!").openPopup();

            // Route Polyline LayerGroup
            const routeLayerGroup = L.layerGroup().addTo(map);

            // Process location change with real-time recalculation
            async function updateLocationAndRoute(destLat, destLng, autoGeocode = true) {
                routeLayerGroup.clearLayers();

                const directDist = computeDirectRoadDistance(storeLat, storeLng, destLat, destLng);
                const nearestBarangay = findNearestBarangay(destLat, destLng);

                // 1. Instantly dispatch initial event to Alpine so UI recalculates with ZERO lag
                window.dispatchEvent(new CustomEvent('update-checkout-fare', {
                    detail: {
                        lat: destLat,
                        lng: destLng,
                        distance: directDist,
                        barangay: nearestBarangay,
                        address: null
                    }
                }));

                // 2. Fetch reverse geocoded street name if requested
                if (autoGeocode) {
                    reverseGeocodeAddress(destLat, destLng, function(formattedAddr) {
                        window.dispatchEvent(new CustomEvent('update-checkout-fare', {
                            detail: {
                                lat: destLat,
                                lng: destLng,
                                distance: directDist,
                                barangay: nearestBarangay,
                                address: formattedAddr
                            }
                        }));
                    });
                }

                // 3. Fetch exact OSRM road route geometry & road distance
                try {
                    const url =
                        `https://router.project-osrm.org/route/v1/driving/${storeLng},${storeLat};${destLng},${destLat}?overview=full&geometries=geojson`;
                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                        const coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                        const roadDistanceKm = parseFloat((data.routes[0].distance / 1000).toFixed(2));

                        const singleRoadLine = L.polyline(coords, {
                            color: '#16A34A',
                            weight: 5,
                            opacity: 0.95,
                            lineCap: 'round',
                            lineJoin: 'round'
                        });
                        routeLayerGroup.addLayer(singleRoadLine);

                        // Smoothly re-dispatch refined road distance to Alpine
                        window.dispatchEvent(new CustomEvent('update-checkout-fare', {
                            detail: {
                                lat: destLat,
                                lng: destLng,
                                distance: roadDistanceKm,
                                barangay: nearestBarangay,
                                address: null
                            }
                        }));
                    }
                } catch (e) {
                    console.warn('OSRM routing fallback used:', e);
                }
            }

            // Initial road route load
            updateLocationAndRoute(defaultLat, defaultLng, false);

            // On marker drag - continuous and dragend
            customerMarker.on('drag', function(e) {
                const pos = customerMarker.getLatLng();
                const directDist = computeDirectRoadDistance(storeLat, storeLng, pos.lat, pos.lng);
                window.dispatchEvent(new CustomEvent('update-checkout-fare', {
                    detail: {
                        lat: pos.lat,
                        lng: pos.lng,
                        distance: directDist,
                        barangay: findNearestBarangay(pos.lat, pos.lng),
                        address: null
                    }
                }));
            });

            customerMarker.on('dragend', function(e) {
                const pos = customerMarker.getLatLng();
                updateLocationAndRoute(pos.lat, pos.lng, true);
            });

            // On map click
            map.on('click', function(e) {
                customerMarker.setLatLng(e.latlng);
                updateLocationAndRoute(e.latlng.lat, e.latlng.lng, true);
            });

            // Auto GPS Location Handler
            window.useCurrentGpsLocation = function() {
                const btn = document.getElementById('btn-auto-gps');
                const originalHtml = btn ? btn.innerHTML : '';
                if (btn) btn.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin text-limey-400"></i> Locating...';

                if (!navigator.geolocation) {
                    alert("Geolocation is not supported by your browser.");
                    if (btn) btn.innerHTML = originalHtml;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;

                        customerMarker.setLatLng([userLat, userLng]);
                        map.flyTo([userLat, userLng], 15, {
                            animate: true,
                            duration: 1
                        });

                        updateLocationAndRoute(userLat, userLng, true);

                        if (btn) {
                            btn.innerHTML =
                                '<i class="fa-solid fa-circle-check text-emerald-400"></i> Located!';
                            setTimeout(() => {
                                btn.innerHTML = originalHtml;
                            }, 2500);
                        }
                    },
                    function(err) {
                        console.warn('Geolocation error:', err);
                        alert(
                            "Unable to detect GPS position. Please allow Location permissions in your browser.");
                        if (btn) btn.innerHTML = originalHtml;
                    }, {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 0
                    }
                );
            };
        });
    </script>
@endpush
