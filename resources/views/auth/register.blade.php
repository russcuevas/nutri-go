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
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="store@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="09171234567">
                </div>
            </div>

            <!-- Business Legal, Permits & Tax Information -->
            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-emerald-950 flex items-center gap-1.5 uppercase tracking-wider">
                        <i class="fa-solid fa-file-shield text-emerald-600"></i> Business Legal, Permits & Tax Information
                    </p>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100/70 px-2 py-0.5 rounded-md">Vetting Required</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                            Business Reg. No. *
                        </label>
                        <input type="text" name="business_registration_number" value="{{ old('business_registration_number') }}" required 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white outline-none" 
                               placeholder="DTI / SEC / CDA No.">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                            Tax ID No. (TIN) *
                        </label>
                        <input type="text" name="tax_identification_number" value="{{ old('tax_identification_number') }}" required 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white font-mono outline-none" 
                               placeholder="e.g. 123-456-789-000">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                            Establishment Date *
                        </label>
                        <input type="date" name="business_establishment_date" value="{{ old('business_establishment_date') }}" required max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                            Business / Mayor's Permit No.
                        </label>
                        <input type="text" name="business_permit_no" value="{{ old('business_permit_no') }}" 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white outline-none" 
                               placeholder="e.g. BP-LIPA-2026-XXXX">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                            Sanitary / Health Certificate No.
                        </label>
                        <input type="text" name="health_certificate" value="{{ old('health_certificate') }}" 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white outline-none" 
                               placeholder="e.g. HC-BATANGAS-XXXX / FDA">
                    </div>
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

            <input type="hidden" name="barangay" id="store_barangay_select" value="Poblacion Barangay 1">

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

                <!-- GCash QR upload with large preview -->
                <div x-data="{
                    previewUrl: null,
                    showZoom: false,
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

                    <!-- Large Interactive QR Preview Card -->
                    <div x-show="previewUrl" class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 bg-white rounded-2xl border-2 border-blue-200 shadow-sm">
                        <!-- Big QR Thumbnail with 'X' -->
                        <div class="relative w-36 h-36 sm:w-44 sm:h-44 rounded-2xl overflow-hidden border-2 border-blue-300 bg-gray-50 shrink-0 shadow-inner group cursor-pointer" @click="showZoom = true" title="Click to Zoom QR">
                            <img :src="previewUrl" alt="GCash QR Preview" class="w-full h-full object-contain p-2 bg-white">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Zoom
                            </div>
                            <button type="button" @click.stop="clearQr()" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition" title="Remove QR (X)">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- Info & Action Buttons -->
                        <div class="space-y-2.5 text-xs flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> GCash QR Ready
                                </span>
                            </div>
                            <p class="text-gray-600 text-xs leading-relaxed">
                                Ito ang malinaw na QR code na i-iiscan ng mga customers kapag nag-order at nag-GCash payment sila sa iyong tindahan.
                            </p>
                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                <button type="button" @click="$refs.regQrInput.click()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate"></i> Change QR Image
                                </button>
                                <button type="button" @click="clearQr()" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition border border-rose-200 flex items-center gap-1.5">
                                    <i class="fa-solid fa-trash-can"></i> Remove (X)
                                </button>
                                <button type="button" @click="showZoom = true" class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-expand"></i> View Full
                                </button>
                            </div>
                        </div>

                        <!-- Zoom Lightbox Modal -->
                        <div x-show="showZoom" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" @click.self="showZoom = false" @keydown.escape.window="showZoom = false">
                            <div class="bg-white p-6 rounded-3xl max-w-sm w-full text-center space-y-4 shadow-2xl relative">
                                <button type="button" @click="showZoom = false" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center font-bold">
                                    ✕
                                </button>
                                <h4 class="font-black text-gray-900 font-heading">GCash QR Preview</h4>
                                <div class="w-64 h-64 mx-auto rounded-2xl overflow-hidden border border-gray-200 p-2 bg-white flex items-center justify-center shadow-inner">
                                    <img :src="previewUrl" alt="GCash QR Zoom" class="max-w-full max-h-full object-contain">
                                </div>
                                <p class="text-xs text-gray-500">Customer scan preview</p>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Upload Dropzone -->
                    <div x-show="!previewUrl" @click="$refs.regQrInput.click()" class="p-6 rounded-2xl border-2 border-dashed border-blue-300 bg-white hover:bg-blue-50/50 text-center cursor-pointer transition space-y-2 group">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-lg group-hover:scale-110 transition">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-900">Click to upload Store GCash QR code</p>
                            <p class="text-[10px] text-gray-500">PNG, JPG up to 5MB (Malaking preview na madaling mabasa)</p>
                        </div>
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
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Driver's License / ID Photo (Optional)</label>
                    <input type="file" name="license_image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-lime-600 file:text-white hover:file:bg-lime-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Complete Home / Base Address in Lipa City</label>
                <input type="text" name="address_line" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none" placeholder="e.g. 123 J.P. Laurel Highway, Brgy. Sabang, Lipa City">
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

    // Initialize store map on demand / when tab active
    window.invalidateRegisterMaps = function() {
        if (!storeMap) initStoreMap();
        if (storeMap) storeMap.invalidateSize();
    };

    initStoreMap();
});
</script>
@endpush
