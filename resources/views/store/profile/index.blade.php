@extends('layouts.store')

@section('title', 'Store Profile & GCash Setup | ' . $store->store_name)
@section('header_title', 'Store Profile & GCash Settings')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="pb-4 border-b border-gray-100">
        <h3 class="text-xl font-black font-heading text-gray-900">Store Profile & GCash Details</h3>
        <p class="text-xs text-gray-500">Keep your Lipa City exact location pin, schedule, and direct GCash QR code up-to-date</p>
    </div>

    <form action="{{ route('store.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
        @csrf
        <input type="hidden" name="latitude" id="store_latitude" value="{{ old('latitude', $store->latitude ?? 13.9419) }}">
        <input type="hidden" name="longitude" id="store_longitude" value="{{ old('longitude', $store->longitude ?? 121.1631) }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Store Name *</label>
                <input type="text" name="store_name" required value="{{ old('store_name', $store->store_name) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Health Category *</label>
                <input type="text" name="health_category" required value="{{ old('health_category', $store->health_category) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-700 uppercase mb-1">Store Description</label>
            <textarea name="description" rows="2" class="w-full p-3 rounded-xl border border-gray-200 text-xs">{{ old('description', $store->description) }}</textarea>
        </div>

        <!-- Interactive Map Location Pinpoint for Store Profile -->
        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <label class="block text-xs font-black text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-map-location-dot text-emerald-600 text-sm"></i> Pinpoint Exact Store Location (Lipa City)
                    </label>
                    <p class="text-[11px] text-gray-500">Drag pin on map or click Auto Location (GPS) to update precise store coordinates</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.useStoreProfileGps()" id="btn-profile-gps"
                            class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-crosshairs text-limey-300"></i> Auto Location (GPS)
                    </button>
                    <span id="profile_coords_badge" class="font-mono font-bold text-[10px] text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200">
                        {{ number_format($store->latitude ?? 13.9419, 4) }}, {{ number_format($store->longitude ?? 121.1631, 4) }}
                    </span>
                </div>
            </div>

            <!-- Leaflet Map Container -->
            <div class="relative w-full h-60 rounded-xl overflow-hidden border border-gray-200 shadow-inner">
                <div id="store-profile-map" class="w-full h-full z-0"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">
                    Lipa City Barangay * <span class="text-emerald-600 font-normal">(Auto-detected)</span>
                </label>
                <select name="barangay" id="store_barangay_select" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs bg-white">
                    @foreach($barangays as $b)
                        <option value="{{ $b }}" {{ $store->barangay == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Contact Phone *</label>
                <input type="text" name="phone" required value="{{ old('phone', $store->phone) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-700 uppercase mb-1">Complete Address Line in Lipa *</label>
            <input type="text" name="address_line" id="store_address_line" required value="{{ old('address_line', $store->address_line) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Daily Opening Time</label>
                <input type="time" name="opening_time" required value="{{ old('opening_time', $store->opening_time) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Daily Closing Time</label>
                <input type="time" name="closing_time" required value="{{ old('closing_time', $store->closing_time) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <!-- Store GCash Direct Details -->
        <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-4">
            <h4 class="font-black uppercase tracking-wider text-blue-950">Store Direct GCash Payment Details</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash Account Name *</label>
                    <input type="text" name="gcash_name" required value="{{ old('gcash_name', $store->gcash_name) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash Account Number *</label>
                    <input type="text" name="gcash_number" required value="{{ old('gcash_number', $store->gcash_number) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Upload GCash QR Code Image</label>
                <input type="file" name="gcash_qr" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Store Logo</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-nutri-900 file:text-white">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Store Banner</label>
                <input type="file" name="banner" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-nutri-900 file:text-white">
            </div>
        </div>

        <div class="pt-4 border-t">
            <button type="submit" class="w-full py-3.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow transition">
                Update Store Profile
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allBarangays = @json($barangayCoords ?? []);
    const initialLat = {{ $store->latitude ?? 13.9419 }};
    const initialLng = {{ $store->longitude ?? 121.1631 }};

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

    const map = L.map('store-profile-map').setView([initialLat, initialLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const storeIcon = L.divIcon({
        html: '<div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white animate-bounce"><i class="fa-solid fa-store text-sm"></i></div>',
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 18]
    });

    const marker = L.marker([initialLat, initialLng], {
        draggable: true,
        icon: storeIcon
    }).addTo(map).bindPopup("<b>{{ $store->store_name }}</b><br>Drag pin to change exact location").openPopup();

    function updateLocation(lat, lng) {
        document.getElementById('store_latitude').value = lat.toFixed(7);
        document.getElementById('store_longitude').value = lng.toFixed(7);
        const badge = document.getElementById('profile_coords_badge');
        if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;

        const nearest = findNearestBarangay(lat, lng);
        const select = document.getElementById('store_barangay_select');
        if (select) select.value = nearest;
    }

    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        updateLocation(pos.lat, pos.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLocation(e.latlng.lat, e.latlng.lng);
    });

    const select = document.getElementById('store_barangay_select');
    if (select) {
        select.addEventListener('change', function() {
            const b = select.value;
            if (allBarangays[b]) {
                const coords = allBarangays[b];
                marker.setLatLng([coords.lat, coords.lng]);
                map.flyTo([coords.lat, coords.lng], 15);
                updateLocation(coords.lat, coords.lng);
            }
        });
    }

    window.useStoreProfileGps = function() {
        const btn = document.getElementById('btn-profile-gps');
        const orig = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Locating...';

        if (!navigator.geolocation) {
            alert("Geolocation not supported.");
            if (btn) btn.innerHTML = orig;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                marker.setLatLng([lat, lng]);
                map.flyTo([lat, lng], 16, { animate: true, duration: 1 });
                updateLocation(lat, lng);
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-circle-check text-limey-300"></i> Pinned!';
                    setTimeout(() => { btn.innerHTML = orig; }, 2500);
                }
            },
            function(err) {
                alert("Could not detect GPS position.");
                if (btn) btn.innerHTML = orig;
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
        );
    };
});
</script>
@endpush
