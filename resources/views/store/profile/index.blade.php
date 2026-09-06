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
            <div class="flex items-center justify-between">
                <h4 class="font-black uppercase tracking-wider text-blue-950 flex items-center gap-2">
                    <i class="fa-solid fa-qrcode text-blue-600"></i> Store Direct GCash Payment Details
                </h4>
                <span class="text-[10px] text-blue-700 bg-blue-100 font-bold px-2 py-0.5 rounded-full">For Customer Orders</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash Account Name *</label>
                    <input type="text" name="gcash_name" required value="{{ old('gcash_name', $store->gcash_name) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs bg-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash Account Number *</label>
                    <input type="text" name="gcash_number" required value="{{ old('gcash_number', $store->gcash_number) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs bg-white font-mono outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Interactive GCash QR Image Uploader with Instant Preview & Remove 'X' -->
            <div x-data="{
                previewUrl: '{{ $store->gcash_qr_url }}',
                initialUrl: '{{ $store->gcash_qr_url }}',
                isRemoved: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                        this.isRemoved = false;
                    }
                },
                removeQr() {
                    this.previewUrl = null;
                    this.isRemoved = true;
                    if (this.$refs.qrFileInput) {
                        this.$refs.qrFileInput.value = '';
                    }
                },
                triggerUpload() {
                    this.$refs.qrFileInput.click();
                }
            }" class="space-y-2 pt-2 border-t border-blue-100">
                <label class="block font-bold text-gray-700 uppercase">GCash QR Code Image</label>
                <input type="hidden" name="remove_gcash_qr" :value="isRemoved ? 1 : 0">
                <input type="file" name="gcash_qr" x-ref="qrFileInput" @change="handleFile($event)" accept="image/*" class="hidden">

                <!-- If Preview / QR Exists -->
                <div x-show="previewUrl" class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-2xl bg-white border border-blue-200 shadow-sm">
                    <div class="relative w-32 h-32 rounded-xl overflow-hidden border-2 border-blue-300 bg-gray-50 shrink-0 shadow-inner group">
                        <img :src="previewUrl" alt="GCash QR Preview" class="w-full h-full object-contain p-1">
                        <!-- 'X' Overlay button on image -->
                        <button type="button" @click="removeQr()" 
                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition"
                                title="Remove QR Image (X)">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-extrabold text-[10px]">
                                <i class="fa-solid fa-circle-check mr-1"></i> QR Ready
                            </span>
                            <span class="text-[11px] text-gray-500">Directly visible to customers upon GCash checkout</span>
                        </div>
                        <p class="text-gray-600">You can replace or remove this QR code image anytime.</p>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="triggerUpload()" 
                                    class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-arrows-rotate"></i> Change / Replace QR
                            </button>
                            <button type="button" @click="removeQr()" 
                                    class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200">
                                <i class="fa-solid fa-trash-can"></i> Remove (X)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- If No QR / Cleared -->
                <div x-show="!previewUrl" 
                     @click="triggerUpload()"
                     class="p-6 rounded-2xl border-2 border-dashed border-blue-300 bg-white/80 hover:bg-white transition text-center cursor-pointer space-y-2 group">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-lg group-hover:scale-110 transition">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900">Click to upload Store GCash QR Code</p>
                        <p class="text-[11px] text-gray-500">PNG, JPG, JPEG up to 5MB (Saved directly to public folder)</p>
                    </div>
                    <button type="button" class="px-3.5 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold shadow-sm inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode"></i> Select GCash QR Image
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Store Logo Upload with Preview & X -->
            <div x-data="{
                previewUrl: '{{ $store->logo_url }}',
                isRemoved: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                        this.isRemoved = false;
                    }
                },
                removeLogo() {
                    this.previewUrl = null;
                    this.isRemoved = true;
                    if (this.$refs.logoInput) this.$refs.logoInput.value = '';
                },
                triggerUpload() {
                    this.$refs.logoInput.click();
                }
            }" class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <label class="block font-bold text-gray-700 uppercase">Store Logo</label>
                <input type="hidden" name="remove_logo" :value="isRemoved ? 1 : 0">
                <input type="file" name="logo" x-ref="logoInput" @change="handleFile($event)" accept="image/*" class="hidden">

                <div x-show="previewUrl" class="flex items-center gap-3">
                    <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-gray-300 bg-white shrink-0 shadow-sm">
                        <img :src="previewUrl" alt="Logo Preview" class="w-full h-full object-cover">
                        <button type="button" @click="removeLogo()" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow hover:bg-rose-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="space-y-1">
                        <button type="button" @click="triggerUpload()" class="px-2.5 py-1 rounded-lg bg-nutri-900 text-white text-[11px] font-bold hover:bg-nutri-800 transition">
                            Change Logo
                        </button>
                        <button type="button" @click="removeLogo()" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-[11px] font-bold border border-rose-200 hover:bg-rose-100 transition block">
                            Remove (X)
                        </button>
                    </div>
                </div>

                <div x-show="!previewUrl" @click="triggerUpload()" class="p-4 rounded-xl border-2 border-dashed border-gray-300 bg-white hover:bg-gray-50 text-center cursor-pointer">
                    <i class="fa-solid fa-image text-gray-400 text-lg mb-1"></i>
                    <p class="text-[11px] font-bold text-gray-700">Upload Logo</p>
                </div>
            </div>

            <!-- Store Banner Upload with Preview & X -->
            <div x-data="{
                previewUrl: '{{ $store->banner_url }}',
                isRemoved: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                        this.isRemoved = false;
                    }
                },
                removeBanner() {
                    this.previewUrl = null;
                    this.isRemoved = true;
                    if (this.$refs.bannerInput) this.$refs.bannerInput.value = '';
                },
                triggerUpload() {
                    this.$refs.bannerInput.click();
                }
            }" class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <label class="block font-bold text-gray-700 uppercase">Store Banner</label>
                <input type="hidden" name="remove_banner" :value="isRemoved ? 1 : 0">
                <input type="file" name="banner" x-ref="bannerInput" @change="handleFile($event)" accept="image/*" class="hidden">

                <div x-show="previewUrl" class="space-y-2">
                    <div class="relative w-full h-20 rounded-xl overflow-hidden border border-gray-300 bg-white shadow-sm">
                        <img :src="previewUrl" alt="Banner Preview" class="w-full h-full object-cover">
                        <button type="button" @click="removeBanner()" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow hover:bg-rose-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="triggerUpload()" class="px-2.5 py-1 rounded-lg bg-nutri-900 text-white text-[11px] font-bold hover:bg-nutri-800 transition">
                            Change Banner
                        </button>
                        <button type="button" @click="removeBanner()" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-[11px] font-bold border border-rose-200 hover:bg-rose-100 transition">
                            Remove (X)
                        </button>
                    </div>
                </div>

                <div x-show="!previewUrl" @click="triggerUpload()" class="p-4 rounded-xl border-2 border-dashed border-gray-300 bg-white hover:bg-gray-50 text-center cursor-pointer">
                    <i class="fa-solid fa-panorama text-gray-400 text-lg mb-1"></i>
                    <p class="text-[11px] font-bold text-gray-700">Upload Banner</p>
                </div>
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
