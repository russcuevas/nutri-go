@extends('layouts.app')

@section('title', 'Live Map Tracking #' . $order->order_number . ' | NutriGo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    status: '{{ $order->status }}',
    statusLabel: '{{ $order->status_label }}',
    statusBadgeClass: '{{ $order->status_badge_class }}',
    orderNumber: '{{ $order->order_number }}',
    showCancelConfirm: false,
    trackings: @js($initialTrackings),
    rider: @js($initialRider),
    poll() {
        if (['delivered', 'cancelled', 'declined_by_store'].includes(this.status)) {
            return;
        }
        fetch(`{{ route('orders.status.poll', $order->order_number) }}`)
            .then(r => {
                if (r.status === 404) {
                    window.location.href = '{{ route('users.orders.index') }}';
                    return;
                }
                return r.json();
            })
            .then(data => {
                if (!data) return;

                // Auto-refresh page immediately when rider marks order as delivered
                if (this.status !== 'delivered' && data.status === 'delivered') {
                    this.status = 'delivered';
                    this.statusLabel = data.status_label;
                    window.location.reload();
                    return;
                }

                this.status = data.status;
                this.statusLabel = data.status_label;
                this.statusBadgeClass = data.status_badge_class;
                if (data.trackings && data.trackings.length > 0) {
                    this.trackings = data.trackings;
                }
                if (data.rider) {
                    this.rider = data.rider;
                }

                if (data.is_rider_delivering && data.rider) {
                    if (window.updateRiderPosition) {
                        window.updateRiderPosition(data.rider.lat, data.rider.lng, data.rider.name);
                    }
                } else {
                    if (window.removeRiderMarker) {
                        window.removeRiderMarker();
                    }
                }
            })
            .catch(err => console.log('Tracking poll error/ended.'));
    }
}" x-init="setInterval(() => poll(), 2500)">

    <!-- Top Status Banner -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="text-xl font-black text-gray-900 font-heading">Order #{{ $order->order_number }}</span>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold border" :class="statusBadgeClass" x-text="statusLabel">
                    {{ $order->status_label }}
                </span>
            </div>
            <div class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                <span>Store: <strong class="text-gray-800">{{ $order->store->store_name }}</strong></span>
                <br>
                <span>Address: <strong class="text-gray-800">{{ $order->delivery_address }}</strong></span>
                @if($order->delivery_landmark)
                    <br>
                    <span class="text-gray-600">Landmark: <span class="font-medium text-gray-700">({{ $order->delivery_landmark }})</span></span>
                @endif
                @if($order->delivery_notes)
                    <br>
                    <span class="text-gray-500 italic">Notes: "{{ $order->delivery_notes }}"</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- 1-Click Cancel Button only when still pending store acceptance -->
            @if($order->status === 'pending_store')
                <template x-if="status === 'pending_store'">
                    <button @click="showCancelConfirm = true" type="button" class="px-3.5 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-extrabold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-trash-can text-rose-500"></i> Cancel Order
                    </button>
                </template>
            @endif

            <div class="px-4 py-2 rounded-2xl bg-nutri-50 border border-nutri-200 text-right">
                <span class="text-[10px] text-gray-500 font-semibold uppercase">Estimated Arrival</span>
                <div class="text-sm font-black text-nutri-800 font-heading">
                    ~{{ $order->estimated_delivery_time_mins }} mins ({{ $order->distance_km }} km)
                </div>
            </div>
        </div>
    </div>

    @if($order->status === 'pending_store')
    <!-- Notice Banner for Unaccepted / Pending Store Orders -->
    <div x-show="status === 'pending_store'" x-cloak class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/90 p-4 rounded-3xl mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-hourglass-half text-sm"></i>
            </div>
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-950 font-heading">Waiting for Restaurant Acceptance</h4>
                <p class="text-[11px] text-amber-800 mt-0.5">Maaari mo pang i-cancel at tuluyang burahin ang order na ito kung nais mong mag-order sa iba bago tanggapin ng restaurant.</p>
            </div>
        </div>
        <button @click="showCancelConfirm = true" type="button" class="shrink-0 px-4 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-md shadow-rose-600/20 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-ban"></i> I-cancel & I-delete ang Order
        </button>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div x-show="showCancelConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @keydown.escape.window="showCancelConfirm = false">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-gray-100 text-center space-y-4" @click.outside="showCancelConfirm = false">
            <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-2xl shadow-inner border border-rose-100">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 font-heading">I-cancel ang Order #{{ $order->order_number }}?</h3>
            <p class="text-xs text-gray-600 leading-relaxed">
                Dahil hindi pa ito natatanggap ng <b>{{ $order->store->store_name }}</b>, ang order na ito ay tuluyang <b>mabubura at ma-cacancel</b> sa system. Hindi na ito maibabalik.
            </p>
            <div class="flex items-center gap-3 pt-2">
                <button @click="showCancelConfirm = false" type="button" class="flex-1 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs transition">
                    Huwag I-cancel
                </button>
                <form action="{{ route('users.orders.cancel', $order->order_number) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-md shadow-rose-600/30 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-trash-can"></i> Oo, I-delete Na
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

@php
    $isRiderActive = $order->rider_id && in_array($order->status, ['rider_assigned', 'rider_picked_up', 'on_the_way']);
@endphp

        <!-- Left: Live Interactive Map with Moving Rider -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-satellite-dish text-emerald-600 animate-pulse"></i> Live Order Tracking
                    </h3>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border"
                        :class="{
                            'text-amber-700 bg-amber-50 border-amber-200': status === 'pending_store',
                            'text-blue-700 bg-blue-50 border-blue-200': status === 'store_accepted_preparing',
                            'text-indigo-700 bg-indigo-50 border-indigo-200': status === 'ready_for_pickup',
                            'text-emerald-700 bg-emerald-50 border-emerald-200': ['rider_assigned', 'rider_picked_up', 'on_the_way'].includes(status),
                            'text-gray-700 bg-gray-50 border-gray-200': ['delivered', 'cancelled', 'declined_by_store'].includes(status)
                        }"
                        x-text="['rider_assigned', 'rider_picked_up', 'on_the_way'].includes(status) ? '● Live Rider On The Way' : (status === 'pending_store' ? '● Awaiting Store Acceptance' : (status === 'store_accepted_preparing' ? '● Kitchen Preparing Order' : (status === 'ready_for_pickup' ? '● Waiting for Rider Pickup' : '● ' + statusLabel)))">
                        ● Real-Time Lipa Route
                    </span>
                </div>

                <!-- Leaflet Live Map -->
                <div id="live-tracking-map" class="w-full h-96 rounded-2xl border border-gray-200 shadow-inner z-0"></div>

                <!-- Map Legend -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 text-xs font-semibold text-gray-600 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-3.5 h-3.5 rounded-full bg-emerald-600 shrink-0"></span>
                        <span class="truncate">Store ({{ $order->store->store_name }})</span>
                    </div>
                    <div x-show="['rider_assigned', 'rider_picked_up', 'on_the_way'].includes(status)" x-cloak class="flex items-center gap-2 min-w-0">
                        <span class="w-3.5 h-3.5 rounded-full bg-lime-500 shadow-glow shrink-0 animate-pulse"></span>
                        <span class="truncate">Active Rider (GPS)</span>
                    </div>
                    <div class="flex items-start gap-2 min-w-0 sm:col-span-2 md:col-span-1">
                        <span class="w-3.5 h-3.5 rounded-full bg-rose-500 shrink-0 mt-0.5"></span>
                        <div class="min-w-0 flex-1">
                            <span class="text-gray-500">Your Address:</span>
                            <span class="font-bold text-gray-800 break-words block sm:inline">{{ $order->delivery_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Submission Form (When Delivered) -->
            @if($order->status === 'delivered')
                <div class="bg-white p-6 rounded-3xl border border-emerald-200 shadow-card space-y-4">
                    <div class="flex items-center gap-2 text-emerald-800">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <h3 class="text-lg font-black font-heading">Order Delivered! How was your healthy food?</h3>
                    </div>

                    @if($order->review)
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                            <p class="font-bold">Thank you! You rated Store {{ $order->review->store_rating }}/5 and Rider {{ $order->review->rider_rating }}/5.</p>
                            <p class="italic">"{{ $order->review->store_comment }}"</p>
                        </div>
                    @else
                        <form action="{{ route('users.orders.review.submit', $order->order_number) }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-gray-700 uppercase mb-1">Store Food Rating</label>
                                    <select name="store_rating" required class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white font-bold">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5) Excellent & Fresh</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5) Very Good</option>
                                        <option value="3">⭐⭐⭐ (3/5) Average</option>
                                        <option value="2">⭐⭐ (2/5) Below Expectation</option>
                                        <option value="1">⭐ (1/5) Poor</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-gray-700 uppercase mb-1">Delivery Rider Rating</label>
                                    <select name="rider_rating" required class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white font-bold">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5) Fast & Courteous</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5) Good</option>
                                        <option value="3">⭐⭐⭐ (3/5) Average</option>
                                        <option value="2">⭐⭐ (2/5) Slow</option>
                                        <option value="1">⭐ (1/5) Poor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-gray-700 uppercase mb-1">Health Satisfaction</label>
                                    <select name="health_satisfaction" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white">
                                        <option value="Fresh & Macro-Accurate">Fresh & Macro-Accurate</option>
                                        <option value="Super Delicious & Guilt-Free">Super Delicious & Guilt-Free</option>
                                        <option value="Great Post-Workout Fuel">Great Post-Workout Fuel</option>
                                        <option value="Helped My Calorie Goal">Helped My Calorie Goal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-gray-700 uppercase mb-1">Feedback Comments</label>
                                    <input type="text" name="store_comment" placeholder="Tell us about the taste and freshness..." class="w-full px-3 py-2 rounded-xl border border-gray-200">
                                </div>
                            </div>

                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 text-white font-bold text-xs shadow hover:bg-nutri-800 transition">
                                Submit Health Feedback
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>

        <!-- Right: Progress Timeline & Order Details -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Step Status Timeline -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-900 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-nutri-600"></i> Delivery Journey
                    </span>
                    <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                    </span>
                </h3>

                <div class="space-y-4 border-l-2 border-nutri-200 ml-3 pl-4 text-xs">
                    <template x-for="(track, idx) in trackings" :key="idx">
                        <div class="relative">
                            <span class="absolute -left-[23px] top-0 w-3 h-3 rounded-full bg-nutri-600 border-2 border-white"></span>
                            <p class="font-bold text-gray-900" x-text="track.title"></p>
                            <p class="text-[11px] text-gray-500 mt-0.5" x-text="track.description"></p>
                            <span class="text-[10px] text-gray-400 font-semibold" x-text="track.time"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Assigned Rider Card (Dynamic Real-Time update when assigned/on the way/delivered) -->
            <template x-if="rider">
                <div class="bg-white p-6 rounded-3xl border border-lime-200 shadow-card space-y-3">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-lime-800 block">Your NutriRider</span>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-lime-100 text-lime-800 flex items-center justify-center text-lg font-black shadow-xs shrink-0 mt-0.5">
                                🛵
                            </div>
                            <div class="text-xs text-gray-600 space-y-1 pl-1 leading-relaxed">
                                <div>
                                    <span class="text-gray-500 font-semibold">Rider name:</span>
                                    <span class="font-extrabold text-gray-900 text-sm ml-1" x-text="rider.name"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-semibold">Motorcycle:</span>
                                    <span class="font-bold text-gray-800 ml-1" x-text="rider.vehicle"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-semibold">Plate number:</span>
                                    <span class="font-mono font-black text-gray-900 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200 ml-1 inline-block" x-text="rider.plate"></span>
                                </div>
                            </div>
                        </div>
                        <a :href="'tel:' + rider.phone" class="p-2.5 rounded-xl bg-nutri-50 text-nutri-700 hover:bg-nutri-100 border border-nutri-200 transition shrink-0" title="Call Rider">
                            <i class="fa-solid fa-phone"></i>
                        </a>
                    </div>
                </div>
            </template>

            <!-- Order Items & Receipt Details -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-3 text-xs">
                <h3 class="font-extrabold uppercase tracking-widest text-gray-900 pb-2 border-b border-gray-100">
                    Order Summary
                </h3>

                <div class="space-y-1.5">
                    @foreach($order->items as $item)
                        <div class="flex justify-between">
                            <span class="text-gray-700">{{ $item->quantity }}x {{ $item->product_name }}</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2 border-t border-gray-100 space-y-1 text-gray-600">
                    <div class="flex justify-between">
                        <span>Delivery Fee ({{ $order->distance_km }} km):</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Platform Fee:</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($order->platform_fee, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-amber-600 font-bold">
                            <span>VIP Discount:</span>
                            <span>-₱{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-200 text-sm font-black text-gray-900 font-heading">
                        <span>Total:</span>
                        <span class="text-nutri-800">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Payment Status Pill -->
                <div class="pt-2">
                    <div class="p-2.5 rounded-xl {{ $order->payment_method === 'gcash' ? 'bg-blue-50 text-blue-900 border-blue-200' : 'bg-emerald-50 text-emerald-900 border-emerald-200' }} border flex items-center justify-between text-[11px] font-bold">
                        <span>Payment: {{ strtoupper($order->payment_method) }}</span>
                        <span class="uppercase font-extrabold">{{ $order->payment_status }}</span>
                    </div>
                </div>

                <!-- Delivery Address Details -->
                <div class="pt-3 border-t border-gray-100 space-y-1 text-xs">
                    <span class="font-bold text-gray-900 block text-[11px] uppercase tracking-wider">Delivery Destination:</span>
                    <p class="font-bold text-gray-800">{{ $order->delivery_address }}</p>
                    @if($order->delivery_landmark)
                        <p class="text-[11px] text-gray-600"><span class="font-semibold">Landmark / House No.:</span> {{ $order->delivery_landmark }}</p>
                    @endif
                    @if($order->delivery_notes)
                        <p class="text-[11px] text-gray-500 italic"><span class="font-semibold not-italic">Notes:</span> "{{ $order->delivery_notes }}"</p>
                    @endif
                </div>
            </div>

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
        const isRiderDeliveringInitially = {{ ($order->rider_id && in_array($order->status, ['rider_picked_up', 'on_the_way'])) ? 'true' : 'false' }};

        const map = L.map('live-tracking-map').fitBounds([
            [storeLat, storeLng],
            [destLat, destLng]
        ], { padding: [45, 45], maxZoom: 15 });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 1. Store Pin (Always Visible - Logo or Fallback Store Icon)
        @php
            $hasStoreLogo = !empty($order->store->logo);
            $storeLogoUrl = $order->store->logo_url;
        @endphp
        const storeIcon = L.divIcon({
            className: 'border-0 bg-transparent',
            html: {!! json_encode(
                $hasStoreLogo 
                    ? '<div class="relative flex items-center justify-center"><div class="w-10 h-10 rounded-full bg-white shadow-xl border-2 border-emerald-600 p-0.5 overflow-hidden flex items-center justify-center"><img src="' . e($storeLogoUrl) . '" alt="' . e($order->store->store_name) . '" class="w-full h-full rounded-full object-cover" onerror="this.onerror=null; this.parentElement.outerHTML=\'<div class=\\\'w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white text-base\\\'><i class=\\\'fa-solid fa-store\\\'></i></div>\';"></div></div>'
                    : '<div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white text-base"><i class="fa-solid fa-store"></i></div>'
            ) !!},
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map)
            .bindPopup("<b>{{ addslashes($order->store->store_name) }}</b><br>Healthy Store");

        // 2. Customer Destination Pin (Always Visible)
        const destIcon = L.divIcon({
            html: '<div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-lg border-2 border-white"><i class="fa-solid fa-location-dot text-xs"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        L.marker([destLat, destLng], { icon: destIcon }).addTo(map)
            .bindPopup("<b>Your Address</b><br>{{ $order->delivery_address }}");

        // 3. Real-Time Rider Marker
        const riderIcon = L.divIcon({
            html: '<div class="relative"><div class="absolute -inset-1 rounded-full bg-limey-400 opacity-75 animate-ping"></div><div class="relative w-9 h-9 rounded-full bg-limey-500 text-nutri-950 flex items-center justify-center shadow-xl border-2 border-white font-black"><i class="fa-solid fa-motorcycle text-sm"></i></div></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        window.riderMarker = null;
        let currentRiderLat = {{ $order->rider?->current_latitude ?? $order->store->latitude }};
        let currentRiderLng = {{ $order->rider?->current_longitude ?? $order->store->longitude }};

        const routeLayerGroup = L.layerGroup().addTo(map);

        // Draw Road Route function (from start point to destination)
        async function drawRoadRoute(startLng, startLat, endLng, endLat) {
            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${endLng},${endLat}?overview=full&geometries=geojson`;
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    routeLayerGroup.clearLayers();
                    const roadPathCoordinates = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                    
                    const singleRoadLine = L.polyline(roadPathCoordinates, {
                        color: '#16A34A',
                        weight: 5,
                        opacity: 0.95,
                        lineCap: 'round',
                        lineJoin: 'round'
                    });
                    routeLayerGroup.addLayer(singleRoadLine);

                    // If rider is delivering, fit to rider + destination, else store + destination
                    map.fitBounds(singleRoadLine.getBounds(), { padding: [40, 40] });
                }
            } catch (err) {
                console.warn('OSRM road route error:', err);
            }
        }

        let lastRouteRiderLat = null;
        let lastRouteRiderLng = null;

        // Live Rider Position and Road Update
        window.updateRiderPosition = function(lat, lng, riderName) {
            if (!lat || !lng) return;
            currentRiderLat = lat;
            currentRiderLng = lng;

            const isFirst = !window.riderMarker;
            if (!window.riderMarker) {
                window.riderMarker = L.marker([lat, lng], { icon: riderIcon }).addTo(map)
                    .bindPopup(`<b>${riderName || 'NutriRider'}</b><br>Live GPS Location`);
            } else {
                window.riderMarker.setLatLng([lat, lng]);
            }

            const moved = isFirst || !lastRouteRiderLat || Math.abs(lat - lastRouteRiderLat) > 0.0001 || Math.abs(lng - lastRouteRiderLng) > 0.0001;
            if (moved) {
                lastRouteRiderLat = lat;
                lastRouteRiderLng = lng;
                drawRoadRoute(lng, lat, destLng, destLat);
            }
        };

        window.removeRiderMarker = function() {
            if (window.riderMarker) {
                map.removeLayer(window.riderMarker);
                window.riderMarker = null;
            }
            lastRouteRiderLat = null;
            lastRouteRiderLng = null;
            // Reset road line from store to customer
            drawRoadRoute(storeLng, storeLat, destLng, destLat);
        };

        // Initialize on page load:
        if (isRiderDeliveringInitially && {{ $order->rider ? 'true' : 'false' }}) {
            window.updateRiderPosition(currentRiderLat, currentRiderLng, '{{ $order->rider?->user?->name ?? 'NutriRider' }}');
        } else {
            // Draw planned road route from Store to Customer
            drawRoadRoute(storeLng, storeLat, destLng, destLat);
        }
    });
</script>
@endpush
