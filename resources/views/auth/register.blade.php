@extends('layouts.app')

@section('title', 'Join NutriGo | Customer, Healthy Store or Delivery Rider')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8" 
     x-data="{ 
         tab: '{{ request()->get('tab', 'customer') }}',
         init() {
             this.$watch('tab', value => {
                 setTimeout(() => {
                     window.dispatchEvent(new Event('resize'));
                     if (window.invalidateRegisterMaps) {
                         window.invalidateRegisterMaps();
                     }
                 }, 200);
             });
         }
     }">
    <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-card border border-nutri-100">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex p-3 rounded-2xl bg-nutri-50 border border-nutri-200 shadow-sm mb-3">
                <img src="{{ asset('images/nutrigo-logo.jpg') }}" alt="NutriGo" class="w-12 h-12 object-contain">
            </div>
            <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight">Join Nutri<span class="text-nutri-600">Go</span> Lipa</h2>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">Choose how you would like to participate in our healthy community</p>
        </div>

        <!-- Role Selection Tabs -->
        <div class="grid grid-cols-3 gap-2 p-1.5 rounded-2xl bg-gray-100 mb-8 text-xs font-bold">
            <button @click="tab = 'customer'" :class="tab === 'customer' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-user text-nutri-600"></i> Customer
            </button>
            <button @click="tab = 'store'" :class="tab === 'store' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-store text-emerald-600"></i> Healthy Store
            </button>
            <button @click="tab = 'rider'" :class="tab === 'rider' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-motorcycle text-limey-600"></i> Delivery Rider
            </button>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- TAB 1: CUSTOMER REGISTRATION -->
        <form x-show="tab === 'customer'" action="{{ route('register.customer') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. Juanita Santos">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="name@email.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Phone Number (GCash)</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="09171234567">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Min. 8 characters">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Confirm password">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading">
                Create Customer Account
            </button>
        </form>

        <!-- TAB 2: STORE REGISTRATION -->
        <form x-show="tab === 'store'" action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="latitude" id="store_latitude" value="13.9419">
            <input type="hidden" name="longitude" id="store_longitude" value="121.1631">

            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                <i class="fa-solid fa-leaf mr-1 text-emerald-600"></i>
                <strong>Healthy Vetting:</strong> All stores must provide healthy, calorie-conscious, organic, or nutrient-dense food. Super Admin reviews each application before opening.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Store / Restaurant Name</label>
                    <input type="text" name="store_name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. Batangas Green Deli">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Owner Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. Maria Teresa">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Business Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="store@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="09171234567">
                </div>
            </div>

            <!-- Interactive Map Location Pinpoint for Store -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-map-location-dot text-emerald-600 text-sm"></i> Pinpoint Exact Store Location in Lipa City
                        </label>
                        <p class="text-[11px] text-gray-500">Drag marker on map or click "Auto Location" to save precise coordinates</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="window.useStoreGpsLocation()" id="btn-store-gps"
                                class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-crosshairs text-limey-300"></i> Auto Location (GPS)
                        </button>
                        <span id="store_coords_badge" class="font-mono font-bold text-[10px] text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200">
                            13.9419, 121.1631
                        </span>
                    </div>
                </div>

                <!-- Store Leaflet Map Container -->
                <div class="relative w-full h-64 rounded-xl overflow-hidden border border-gray-200 shadow-inner">
                    <div id="store-register-map" class="w-full h-full z-0"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Lipa City Barangay <span class="text-emerald-600 font-normal">(Auto-detected)</span>
                    </label>
                    <select name="barangay" id="store_barangay_select" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
                        <option value="">Select Barangay in Lipa</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Healthy Cuisine Category</label>
                    <select name="health_category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
                        <option value="Organic & Salads">Organic & Salads</option>
                        <option value="High-Protein & Gym Meals">High-Protein & Gym Meals</option>
                        <option value="Keto & Low-Carb">Keto & Low-Carb</option>
                        <option value="100% Plant-Based & Vegan">100% Plant-Based & Vegan</option>
                        <option value="Cold-Pressed Juices & Detox">Cold-Pressed Juices & Detox</option>
                        <option value="Supplements & Wellness">Supplements & Wellness</option>
                        <option value="Diabetic & Low-Sodium">Diabetic & Low-Sodium</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Complete Store Address Line in Lipa</label>
                <input type="text" name="address_line" id="store_address_line" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. Unit 2B, Ayala Highway, Lipa City">
            </div>

            <!-- GCash Payment Receipt Details for Store -->
            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-4">
                <p class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-qrcode text-blue-600"></i> Store Direct GCash Payment Setup:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">GCash Account Name *</label>
                        <input type="text" name="gcash_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white" placeholder="e.g. Maria Teresa (Store Owner)">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">GCash Account Number *</label>
                        <input type="text" name="gcash_number" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white font-mono" placeholder="e.g. 09171234567">
                    </div>
                </div>

                <!-- GCash QR upload with preview -->
                <div x-data="{
                    previewUrl: null,
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    },
                    clearQr() {
                        this.previewUrl = null;
                        if (this.$refs.regQrInput) this.$refs.regQrInput.value = '';
                    }
                }" class="space-y-2">
                    <label class="block text-[11px] font-bold text-gray-700 uppercase">Upload GCash QR Code (Recommended)</label>
                    <input type="file" name="gcash_qr" x-ref="regQrInput" @change="handleFile($event)" accept="image/*" class="hidden">

                    <div x-show="previewUrl" class="flex items-center gap-3 p-3 bg-white rounded-xl border border-blue-200">
                        <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-blue-200 bg-gray-50 shrink-0">
                            <img :src="previewUrl" alt="GCash QR Preview" class="w-full h-full object-contain">
                            <button type="button" @click="clearQr()" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow hover:bg-rose-700">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="space-y-1 text-xs">
                            <p class="font-bold text-gray-800 text-[11px]">QR Preview Ready</p>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="$refs.regQrInput.click()" class="px-2 py-1 rounded bg-blue-600 text-white text-[10px] font-bold">
                                    Change
                                </button>
                                <button type="button" @click="clearQr()" class="px-2 py-1 rounded bg-rose-50 text-rose-600 text-[10px] font-bold border border-rose-200">
                                    Remove (X)
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="!previewUrl" @click="$refs.regQrInput.click()" class="p-4 rounded-xl border-2 border-dashed border-blue-300 bg-white hover:bg-blue-50/50 text-center cursor-pointer transition">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-lg mb-1"></i>
                        <p class="text-xs font-bold text-blue-900">Click to select GCash QR image</p>
                        <p class="text-[10px] text-gray-500">PNG, JPG up to 5MB</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Min. 8 characters">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Confirm password">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading">
                Submit Store for Healthy Food Vetting
            </button>
        </form>

        <!-- TAB 3: RIDER REGISTRATION -->
        <form x-show="tab === 'rider'" action="{{ route('register.rider') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="latitude" id="rider_latitude" value="13.9419">
            <input type="hidden" name="longitude" id="rider_longitude" value="121.1631">

            <div class="p-3.5 rounded-2xl bg-lime-50 border border-lime-200 text-xs text-lime-900">
                <i class="fa-solid fa-motorcycle mr-1 text-lime-600"></i>
                <strong>Rider Freedom:</strong> As a NutriGo Rider, you can operate flexibly anywhere across Lipa City and earn instant delivery fees directly into your in-app wallet!
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Rider Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. Juan Dela Cruz">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="rider@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Mobile Phone (GCash Payouts)</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="09251234567">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Vehicle Type</label>
                    <select name="vehicle_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
                        <option value="Motorcycle (Underbone/Scooter)">Motorcycle (Underbone/Scooter)</option>
                        <option value="Motorcycle (Manual/Big Bike)">Motorcycle (Manual)</option>
                        <option value="Electric Scooter / E-Bike">Electric Scooter / E-Bike</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Plate Number / MV File</label>
                    <input type="text" name="plate_number" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. 123-ABC">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Driver's License Number</label>
                    <input type="text" name="license_number" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. N02-23-123456">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Base Lipa Barangay <span class="text-lime-700 font-normal">(Auto-detected)</span>
                    </label>
                    <select name="barangay" id="rider_barangay_select" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
                        <option value="">Select your Home Barangay</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Interactive Map Location Pinpoint for Rider Home Base -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-map-pin text-lime-600 text-sm"></i> Rider Starting Location / Home Base
                        </label>
                        <p class="text-[11px] text-gray-500">Pin where you usually start or tap "Auto Location (GPS)"</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="window.useRiderGpsLocation()" id="btn-rider-gps"
                                class="px-3 py-1.5 rounded-xl bg-lime-700 hover:bg-lime-800 text-white text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-crosshairs text-limey-300"></i> Auto Location (GPS)
                        </button>
                        <span id="rider_coords_badge" class="font-mono font-bold text-[10px] text-lime-900 bg-lime-100 px-2.5 py-1 rounded-lg border border-lime-200">
                            13.9419, 121.1631
                        </span>
                    </div>
                </div>

                <!-- Rider Leaflet Map Container -->
                <div class="relative w-full h-56 rounded-xl overflow-hidden border border-gray-200 shadow-inner">
                    <div id="rider-register-map" class="w-full h-full z-0"></div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Driver's License / ID Photo (Optional)</label>
                <input type="file" name="license_image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-lime-600 file:text-white hover:file:bg-lime-700">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Min. 8 characters">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="Confirm password">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading">
                Submit Rider Application
            </button>
        </form>

        <!-- Login Redirect -->
        <p class="text-center text-xs text-gray-600 mt-8 pt-6 border-t border-gray-100">
            Already registered? 
            <a href="{{ route('login') }}" class="font-extrabold text-nutri-600 hover:text-nutri-700 ml-1">Sign In here</a>
        </p>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allBarangays = @json($barangayCoords ?? []);
    const defaultLat = 13.9419;
    const defaultLng = 121.1631;

    // Helper: Find Nearest Barangay from Lat/Lng
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

    // Lipa City Service Zone Polygon (Geofence Reference)
    const lipaServicePolygon = [
        [14.0000, 121.1300],
        [13.9980, 121.1750],
        [13.9920, 121.2150],
        [13.9650, 121.2280],
        [13.9250, 121.2150],
        [13.8850, 121.1920],
        [13.8900, 121.1650],
        [13.9180, 121.1350],
        [13.9550, 121.1380],
        [14.0000, 121.1300]
    ];

    // ==========================================
    // 1. STORE REGISTRATION MAP
    // ==========================================
    let storeMap = null;
    let storeMarker = null;

    function initStoreMap() {
        if (storeMap || !document.getElementById('store-register-map')) return;

        storeMap = L.map('store-register-map').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(storeMap);

        L.polygon(lipaServicePolygon, {
            color: '#10B981',
            weight: 2,
            dashArray: '5, 5',
            fillColor: '#22C55E',
            fillOpacity: 0.05
        }).addTo(storeMap);

        const storeIcon = L.divIcon({
            html: '<div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xl border-2 border-white animate-bounce"><i class="fa-solid fa-store text-sm"></i></div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        storeMarker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: storeIcon
        }).addTo(storeMap).bindPopup("<b>Your Store Location</b><br>Drag pin to exact restaurant position!").openPopup();

        function updateStoreLocation(lat, lng) {
            document.getElementById('store_latitude').value = lat.toFixed(7);
            document.getElementById('store_longitude').value = lng.toFixed(7);
            const badge = document.getElementById('store_coords_badge');
            if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;

            const nearest = findNearestBarangay(lat, lng);
            const select = document.getElementById('store_barangay_select');
            if (select) select.value = nearest;
        }

        storeMarker.on('dragend', function(e) {
            const pos = storeMarker.getLatLng();
            updateStoreLocation(pos.lat, pos.lng);
        });

        storeMap.on('click', function(e) {
            storeMarker.setLatLng(e.latlng);
            updateStoreLocation(e.latlng.lat, e.latlng.lng);
        });

        const storeSelect = document.getElementById('store_barangay_select');
        if (storeSelect) {
            storeSelect.addEventListener('change', function() {
                const b = storeSelect.value;
                if (allBarangays[b]) {
                    const coords = allBarangays[b];
                    storeMarker.setLatLng([coords.lat, coords.lng]);
                    storeMap.flyTo([coords.lat, coords.lng], 15);
                    updateStoreLocation(coords.lat, coords.lng);
                }
            });
        }
    }

    // Auto Location GPS for Store
    window.useStoreGpsLocation = function() {
        const btn = document.getElementById('btn-store-gps');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Locating...';

        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser.");
            if (btn) btn.innerHTML = origHtml;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                if (storeMarker && storeMap) {
                    storeMarker.setLatLng([lat, lng]);
                    storeMap.flyTo([lat, lng], 16, { animate: true, duration: 1 });
                    document.getElementById('store_latitude').value = lat.toFixed(7);
                    document.getElementById('store_longitude').value = lng.toFixed(7);
                    const badge = document.getElementById('store_coords_badge');
                    if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                    const nearest = findNearestBarangay(lat, lng);
                    const select = document.getElementById('store_barangay_select');
                    if (select) select.value = nearest;
                }
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-circle-check text-limey-300"></i> Pinned!';
                    setTimeout(() => { btn.innerHTML = origHtml; }, 2500);
                }
            },
            function(err) {
                alert("Could not detect GPS position. Please enable location permissions.");
                if (btn) btn.innerHTML = origHtml;
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
        );
    };

    // ==========================================
    // 2. RIDER REGISTRATION MAP
    // ==========================================
    let riderMap = null;
    let riderMarker = null;

    function initRiderMap() {
        if (riderMap || !document.getElementById('rider-register-map')) return;

        riderMap = L.map('rider-register-map').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(riderMap);

        L.polygon(lipaServicePolygon, {
            color: '#84CC16',
            weight: 2,
            dashArray: '5, 5',
            fillColor: '#A3E635',
            fillOpacity: 0.05
        }).addTo(riderMap);

        const riderIcon = L.divIcon({
            html: '<div class="w-9 h-9 rounded-full bg-lime-700 text-white flex items-center justify-center shadow-xl border-2 border-white animate-bounce"><i class="fa-solid fa-motorcycle text-sm"></i></div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        riderMarker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: riderIcon
        }).addTo(riderMap).bindPopup("<b>Rider Home Base</b><br>Drag pin to your starting location!").openPopup();

        function updateRiderLocation(lat, lng) {
            document.getElementById('rider_latitude').value = lat.toFixed(7);
            document.getElementById('rider_longitude').value = lng.toFixed(7);
            const badge = document.getElementById('rider_coords_badge');
            if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;

            const nearest = findNearestBarangay(lat, lng);
            const select = document.getElementById('rider_barangay_select');
            if (select) select.value = nearest;
        }

        riderMarker.on('dragend', function(e) {
            const pos = riderMarker.getLatLng();
            updateRiderLocation(pos.lat, pos.lng);
        });

        riderMap.on('click', function(e) {
            riderMarker.setLatLng(e.latlng);
            updateRiderLocation(e.latlng.lat, e.latlng.lng);
        });

        const riderSelect = document.getElementById('rider_barangay_select');
        if (riderSelect) {
            riderSelect.addEventListener('change', function() {
                const b = riderSelect.value;
                if (allBarangays[b]) {
                    const coords = allBarangays[b];
                    riderMarker.setLatLng([coords.lat, coords.lng]);
                    riderMap.flyTo([coords.lat, coords.lng], 15);
                    updateRiderLocation(coords.lat, coords.lng);
                }
            });
        }
    }

    // Auto Location GPS for Rider
    window.useRiderGpsLocation = function() {
        const btn = document.getElementById('btn-rider-gps');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Locating...';

        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser.");
            if (btn) btn.innerHTML = origHtml;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                if (riderMarker && riderMap) {
                    riderMarker.setLatLng([lat, lng]);
                    riderMap.flyTo([lat, lng], 16, { animate: true, duration: 1 });
                    document.getElementById('rider_latitude').value = lat.toFixed(7);
                    document.getElementById('rider_longitude').value = lng.toFixed(7);
                    const badge = document.getElementById('rider_coords_badge');
                    if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                    const nearest = findNearestBarangay(lat, lng);
                    const select = document.getElementById('rider_barangay_select');
                    if (select) select.value = nearest;
                }
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-circle-check text-limey-300"></i> Pinned!';
                    setTimeout(() => { btn.innerHTML = origHtml; }, 2500);
                }
            },
            function(err) {
                alert("Could not detect GPS position. Please enable location permissions.");
                if (btn) btn.innerHTML = origHtml;
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
        );
    };

    // Initialize maps on demand / when tab active
    window.invalidateRegisterMaps = function() {
        if (!storeMap) initStoreMap();
        if (!riderMap) initRiderMap();
        if (storeMap) storeMap.invalidateSize();
        if (riderMap) riderMap.invalidateSize();
    };

    initStoreMap();
    initRiderMap();
});
</script>
@endpush
