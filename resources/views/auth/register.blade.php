@extends('layouts.app')

@section('title', 'Join NutriGo | Customer, Healthy Store or Delivery Rider')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="{
        tab: '{{ request()->get('tab', 'customer') }}',
        termsModalOpen: false,
        termsRole: '{{ request()->get('tab', 'customer') }}',
        agreed: {
            customer: false,
            store: false,
            rider: false
        },
        switchTab(newTab) {
            this.tab = newTab;
            this.openTerms(newTab);
        },
        openTerms(role) {
            this.termsRole = role;
            this.termsModalOpen = true;
        },
        acceptTerms() {
            if (this.termsRole) {
                this.agreed[this.termsRole] = true;
            }
            this.termsModalOpen = false;
        },
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
                <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight">Join Nutri<span
                        class="text-nutri-600">Go</span> Lipa</h2>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">Choose how you would like to
                    participate in our healthy community</p>
            </div>

            <!-- Role Selection Tabs -->
            <div class="grid grid-cols-3 gap-2 p-1.5 rounded-2xl bg-gray-100 mb-8 text-xs font-bold">
                <button type="button" @click="switchTab('customer')"
                    :class="tab === 'customer' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-user text-nutri-600"></i> Customer
                </button>
                <button type="button" @click="switchTab('store')"
                    :class="tab === 'store' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-store text-emerald-600"></i> Healthy Store
                </button>
                <button type="button" @click="switchTab('rider')"
                    :class="tab === 'rider' ? 'bg-white text-nutri-950 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-motorcycle text-limey-600"></i> Delivery Rider
                </button>
            </div>

            @if ($errors->any())
                <div
                    class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- TAB 1: CUSTOMER REGISTRATION -->
            <form x-show="tab === 'customer'" action="{{ route('register.customer') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                        placeholder="e.g. Juanita Santos">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email
                            Address</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="name@email.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Phone Number
                            (GCash)</label>
                        <input type="tel" name="phone" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="09171234567">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Confirm password">
                    </div>
                </div>

                <!-- Customer Terms & Conditions Agreement -->
                <div class="p-4 rounded-2xl bg-nutri-50/70 border border-nutri-200 space-y-2">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="customer_terms" x-model="agreed.customer" required
                            class="mt-1 w-4 h-4 text-nutri-600 rounded border-gray-300 focus:ring-nutri-500 cursor-pointer">
                        <label for="customer_terms" class="text-xs text-gray-700 leading-snug cursor-pointer">
                            I have read, understood, and agree to the
                            <button type="button" @click="openTerms('customer')"
                                class="font-bold text-nutri-700 underline hover:text-nutri-900 cursor-pointer">
                                NutriGo Customer Terms and Conditions
                            </button>.
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading">
                    Create Customer Account
                </button>
            </form>

            <!-- TAB 2: STORE REGISTRATION -->
            <form x-show="tab === 'store'" action="{{ route('register.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="latitude" id="store_latitude" value="13.9419">
                <input type="hidden" name="longitude" id="store_longitude" value="121.1631">

                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                    <i class="fa-solid fa-leaf mr-1 text-emerald-600"></i>
                    <strong>Healthy Vetting:</strong> All stores must provide healthy, calorie-conscious, organic, or
                    nutrient-dense food. Super Admin reviews each application before opening.
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Store /
                            Restaurant Name</label>
                        <input type="text" name="store_name" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="e.g. Batangas Green Deli">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Owner Full
                            Name</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="e.g. Maria Teresa">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Business
                            Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="store@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Contact
                            Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="09171234567">
                    </div>
                </div>

                <!-- Business Legal, Permits & Tax Information -->
                <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-emerald-950 flex items-center gap-1.5 uppercase tracking-wider">
                            <i class="fa-solid fa-file-shield text-emerald-600"></i> Business Legal, Permits & Tax
                            Information
                        </p>
                        <span
                            class="text-[10px] text-emerald-700 font-bold bg-emerald-100/70 px-2 py-0.5 rounded-md">Vetting
                            Required</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                                Business Reg. No. *
                            </label>
                            <input type="text" name="business_registration_number"
                                value="{{ old('business_registration_number') }}" required
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white outline-none"
                                placeholder="DTI / SEC / CDA No.">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                                Tax ID No. (TIN) *
                            </label>
                            <input type="text" name="tax_identification_number"
                                value="{{ old('tax_identification_number') }}" required
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-xs bg-white font-mono outline-none"
                                placeholder="e.g. 123-456-789-000">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">
                                Establishment Date *
                            </label>
                            <input type="date" name="business_establishment_date"
                                value="{{ old('business_establishment_date') }}" required max="{{ date('Y-m-d') }}"
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
                            <label
                                class="block text-xs font-black text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-map-location-dot text-emerald-600 text-sm"></i> Pinpoint Exact Store
                                Location in Lipa City
                            </label>
                            <p class="text-[11px] text-gray-500">Drag marker on map or click "Auto Location" to save
                                precise coordinates</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="window.useStoreGpsLocation()" id="btn-store-gps"
                                class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-crosshairs text-limey-300"></i> Auto Location (GPS)
                            </button>
                            <span id="store_coords_badge"
                                class="font-mono font-bold text-[10px] text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200">
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
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Healthy Cuisine
                        Category</label>
                    <select name="health_category" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
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
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Complete Store
                        Address Line in Lipa</label>
                    <input type="text" name="address_line" id="store_address_line" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                        placeholder="e.g. Unit 2B, Ayala Highway, Lipa City">
                </div>

                <!-- GCash Payment Receipt Details for Store -->
                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-4">
                    <p class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode text-blue-600"></i> Store Direct GCash Payment Setup:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">GCash Account Name
                                *</label>
                            <input type="text" name="gcash_name" required
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white"
                                placeholder="e.g. Maria Teresa (Store Owner)">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">GCash Account Number
                                *</label>
                            <input type="text" name="gcash_number" required
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white font-mono"
                                placeholder="e.g. 09171234567">
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
                        <label class="block text-[11px] font-bold text-gray-700 uppercase">Upload GCash QR Code
                            (Recommended)</label>
                        <input type="file" name="gcash_qr" x-ref="regQrInput" @change="handleFile($event)"
                            accept="image/*" class="hidden">

                        <!-- Large Interactive QR Preview Card -->
                        <div x-show="previewUrl"
                            class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 bg-white rounded-2xl border-2 border-blue-200 shadow-sm">
                            <!-- Big QR Thumbnail with 'X' -->
                            <div class="relative w-36 h-36 sm:w-44 sm:h-44 rounded-2xl overflow-hidden border-2 border-blue-300 bg-gray-50 shrink-0 shadow-inner group cursor-pointer"
                                @click="showZoom = true" title="Click to Zoom QR">
                                <img :src="previewUrl" alt="GCash QR Preview"
                                    class="w-full h-full object-contain p-2 bg-white">
                                <div
                                    class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i> Zoom
                                </div>
                                <button type="button" @click.stop="clearQr()"
                                    class="absolute top-2 right-2 w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition"
                                    title="Remove QR (X)">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <!-- Info & Action Buttons -->
                            <div class="space-y-2.5 text-xs flex-1">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i> GCash QR Ready
                                    </span>
                                </div>
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    Ito ang malinaw na QR code na i-iiscan ng mga customers kapag nag-order at nag-GCash
                                    payment sila sa iyong tindahan.
                                </p>
                                <div class="flex flex-wrap items-center gap-2 pt-1">
                                    <button type="button" @click="$refs.regQrInput.click()"
                                        class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                        <i class="fa-solid fa-arrows-rotate"></i> Change QR Image
                                    </button>
                                    <button type="button" @click="clearQr()"
                                        class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition border border-rose-200 flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can"></i> Remove (X)
                                    </button>
                                    <button type="button" @click="showZoom = true"
                                        class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1">
                                        <i class="fa-solid fa-expand"></i> View Full
                                    </button>
                                </div>
                            </div>

                            <!-- Zoom Lightbox Modal -->
                            <div x-show="showZoom" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
                                @click.self="showZoom = false" @keydown.escape.window="showZoom = false">
                                <div
                                    class="bg-white p-6 rounded-3xl max-w-sm w-full text-center space-y-4 shadow-2xl relative">
                                    <button type="button" @click="showZoom = false"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center font-bold">
                                        ✕
                                    </button>
                                    <h4 class="font-black text-gray-900 font-heading">GCash QR Preview</h4>
                                    <div
                                        class="w-64 h-64 mx-auto rounded-2xl overflow-hidden border border-gray-200 p-2 bg-white flex items-center justify-center shadow-inner">
                                        <img :src="previewUrl" alt="GCash QR Zoom"
                                            class="max-w-full max-h-full object-contain">
                                    </div>
                                    <p class="text-xs text-gray-500">Customer scan preview</p>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Upload Dropzone -->
                        <div x-show="!previewUrl" @click="$refs.regQrInput.click()"
                            class="p-6 rounded-2xl border-2 border-dashed border-blue-300 bg-white hover:bg-blue-50/50 text-center cursor-pointer transition space-y-2 group">
                            <div
                                class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-900">Click to upload Store GCash QR code</p>
                                <p class="text-[10px] text-gray-500">PNG, JPG up to 5MB (Malaking preview na madaling
                                    mabasa)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Confirm password">
                    </div>
                </div>

                <!-- Store Terms & Conditions Agreement -->
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-2">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="store_terms" x-model="agreed.store" required
                            class="mt-1 w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                        <label for="store_terms" class="text-xs text-emerald-950 leading-snug cursor-pointer">
                            I have read, understood, and agree to NutriGo's
                            <button type="button" @click="openTerms('store')"
                                class="font-bold text-emerald-800 underline hover:text-emerald-950 cursor-pointer">
                                Partner Store Terms and Conditions
                            </button>. I understand that only food products approved under NutriGo's nutritional standards
                            may be listed, and that remittances are scheduled for the 15th and 30th of each month.
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading">
                    Submit Store for Healthy Food Vetting
                </button>
            </form>

            <!-- TAB 3: RIDER REGISTRATION -->
            <form x-show="tab === 'rider'" action="{{ route('register.rider') }}" method="POST"
                enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="latitude" id="rider_latitude" value="13.9419">
                <input type="hidden" name="longitude" id="rider_longitude" value="121.1631">

                <div class="p-3.5 rounded-2xl bg-lime-50 border border-lime-200 text-xs text-lime-900">
                    <i class="fa-solid fa-motorcycle mr-1 text-lime-600"></i>
                    <strong>Rider Freedom:</strong> As a NutriGo Rider, you can operate flexibly anywhere across Lipa City
                    and earn instant delivery fees directly into your in-app wallet!
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Rider Full Name</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                        placeholder="e.g. Juan Dela Cruz">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="rider@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Mobile Phone (GCash Payouts)</label>
                        <input type="tel" name="phone" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="09251234567">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Vehicle Type</label>
                        <select name="vehicle_type" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white">
                            <option value="Motorcycle (Underbone/Scooter)">Motorcycle (Underbone/Scooter)</option>
                            <option value="Motorcycle (Manual/Big Bike)">Motorcycle (Manual)</option>
                            <option value="Electric Scooter / E-Bike">Electric Scooter / E-Bike</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Plate Number / MV File</label>
                        <input type="text" name="plate_number" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="e.g. 123-ABC">
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- RIDER IDENTITY VERIFICATION: STEP 1 (FACE) & STEP 2 (ID)   -->
                <!-- ======================================================== -->
                <div class="space-y-4"
                     x-data="{
                         // Step 1: Face Verification State
                         faceVerified: false,
                         cameraActive: false,
                         facePreview: null,
                         faceData: null,
                         stream: null,
                         cameraError: null,

                         async startCamera() {
                             this.cameraError = null;
                             this.cameraActive = true;
                             try {
                                 this.stream = await navigator.mediaDevices.getUserMedia({ 
                                     video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } 
                                 });
                                 this.$nextTick(() => {
                                     if (this.$refs.videoEl) {
                                         this.$refs.videoEl.srcObject = this.stream;
                                         this.$refs.videoEl.play();
                                     }
                                 });
                             } catch (err) {
                                 console.error('Camera access error:', err);
                                 this.cameraError = 'Hindi mabuksan ang camera. Mangyaring mag-upload na lamang ng selfie photo gamit ang button sa ibaba.';
                                 this.cameraActive = false;
                             }
                         },

                         captureSelfie() {
                             if (!this.$refs.videoEl) return;
                             const video = this.$refs.videoEl;
                             const canvas = document.createElement('canvas');
                             canvas.width = video.videoWidth || 640;
                             canvas.height = video.videoHeight || 480;
                             const ctx = canvas.getContext('2d');
                             ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                             const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                             this.facePreview = dataUrl;
                             this.faceData = dataUrl;
                             this.faceVerified = true;
                             this.stopCamera();
                         },

                         handleFaceFile(e) {
                             const file = e.target.files[0];
                             if (file) {
                                 const reader = new FileReader();
                                 reader.onload = (event) => {
                                     this.facePreview = event.target.result;
                                     this.faceData = event.target.result;
                                     this.faceVerified = true;
                                     this.stopCamera();
                                 };
                                 reader.readAsDataURL(file);
                             }
                         },

                         retakeFace() {
                             this.facePreview = null;
                             this.faceData = null;
                             this.faceVerified = false;
                             this.stopCamera();
                             if (this.$refs.faceFileInput) this.$refs.faceFileInput.value = '';
                         },

                         stopCamera() {
                             this.cameraActive = false;
                             if (this.stream) {
                                 this.stream.getTracks().forEach(t => t.stop());
                                 this.stream = null;
                             }
                         },

                         // Step 2: License ID State
                         previewFront: null,
                         previewBack: null,
                         zoomModal: null,

                         handleFront(e) {
                             const file = e.target.files[0];
                             if (file) {
                                 this.previewFront = URL.createObjectURL(file);
                             }
                         },
                         clearFront() {
                             this.previewFront = null;
                             if (this.$refs.frontInput) this.$refs.frontInput.value = '';
                         },
                         handleBack(e) {
                             const file = e.target.files[0];
                             if (file) {
                                 this.previewBack = URL.createObjectURL(file);
                             }
                         },
                         clearBack() {
                             this.previewBack = null;
                             if (this.$refs.backInput) this.$refs.backInput.value = '';
                         }
                     }">

                    <!-- Hidden Inputs for Face Verification Data -->
                    <input type="hidden" name="face_image_data" :value="faceData">
                    <input type="file" name="face_image" x-ref="faceFileInput" @change="handleFaceFile($event)" accept="image/*" capture="user" class="hidden">

                    <!-- ============================================== -->
                    <!-- CARD 1: STEP 1 - RIDER FACE VERIFICATION       -->
                    <!-- ============================================== -->
                    <div class="p-5 rounded-2xl border transition-all"
                         :class="faceVerified ? 'bg-emerald-50/50 border-emerald-300' : 'bg-gray-50 border-gray-200'">
                        
                        <!-- Header -->
                        <div class="flex items-center justify-between gap-2 pb-2 border-b"
                             :class="faceVerified ? 'border-emerald-200' : 'border-gray-200'">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5"
                                    :class="faceVerified ? 'text-emerald-950' : 'text-gray-900'">
                                    <i class="fa-solid fa-camera" :class="faceVerified ? 'text-emerald-600' : 'text-nutri-600'"></i>
                                    Step 1: Rider Face Verification (Live Selfie)
                                </h4>
                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Kailangan munang mag-selfie bago ma-unlock ang pag-upload ng Driver's License ID.
                                </p>
                            </div>
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg border shrink-0"
                                  :class="faceVerified ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200'">
                                <span x-text="faceVerified ? '✓ Step 1 Complete' : 'Required Bago Mag-ID'"></span>
                            </span>
                        </div>

                        <!-- 1.A: Camera Active State (Live Video Stream) -->
                        <div x-show="cameraActive && !faceVerified" class="mt-4 p-4 bg-gray-900 rounded-2xl text-center space-y-3">
                            <div class="relative max-w-sm mx-auto h-64 rounded-2xl overflow-hidden bg-black border-2 border-limey-400 shadow-inner flex items-center justify-center">
                                <video x-ref="videoEl" autoplay playsinline muted class="w-full h-full object-cover"></video>
                                <!-- Face Oval Outline Guide -->
                                <div class="absolute inset-4 rounded-[50%] border-2 border-dashed border-white/60 pointer-events-none flex items-center justify-center">
                                    <span class="text-[10px] text-white/80 font-bold bg-black/50 px-2 py-0.5 rounded-full">Iposisyon ang Mukha sa Loob</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2 pt-1">
                                <button type="button" @click="captureSelfie()" class="px-5 py-2.5 rounded-xl bg-limey-500 hover:bg-limey-600 text-nutri-950 font-black text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-camera"></i> Kunan ng Litrato (Capture Selfie)
                                </button>
                                <button type="button" @click="stopCamera()" class="px-3.5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition cursor-pointer">
                                    Kanselahin
                                </button>
                            </div>
                        </div>

                        <!-- 1.B: Camera Inactive & Not Yet Verified State -->
                        <div x-show="!cameraActive && !faceVerified" class="mt-4 space-y-3">
                            <!-- Camera Error Alert if any -->
                            <div x-show="cameraError" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                                <span x-text="cameraError"></span>
                            </div>

                            <div class="p-5 rounded-2xl border-2 border-dashed border-nutri-300 bg-white text-center space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-nutri-50 text-nutri-700 flex items-center justify-center mx-auto text-2xl shadow-2xs">
                                    <i class="fa-solid fa-face-smile"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-gray-900">Mag-Selfie para sa Face Verification</h5>
                                    <p class="text-[11px] text-gray-500 mt-0.5">I-verify ang iyong mukha bago mag-upload ng Driver's License ID.</p>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-2 pt-1">
                                    <button type="button" @click="startCamera()" class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-camera"></i> Buksan ang Camera (Live Selfie)
                                    </button>
                                    <button type="button" @click="$refs.faceFileInput.click()" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-image"></i> O Mag-upload ng Selfie Photo
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 1.C: Face Verification Completed State -->
                        <div x-show="faceVerified" class="mt-3 flex flex-col sm:flex-row items-center justify-between gap-4 p-3 bg-white rounded-2xl border border-emerald-200 shadow-2xs">
                            <div class="flex items-center gap-3.5">
                                <div class="relative w-16 h-16 rounded-2xl overflow-hidden border-2 border-emerald-500 bg-gray-100 shrink-0 shadow-sm">
                                    <img :src="facePreview" alt="Face Verification Selfie" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-emerald-600 text-white text-[8px] font-black text-center py-0.5 uppercase tracking-wider">Verified</span>
                                </div>
                                <div class="space-y-0.5">
                                    <div class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Mukha Na-verify
                                    </div>
                                    <h5 class="text-xs font-bold text-gray-900">Step 1 Kumpleto: Handa na ang iyong Selfie</h5>
                                    <p class="text-[11px] text-gray-500">Naka-unlock na ang Step 2 para sa Driver's License ID.</p>
                                </div>
                            </div>
                            <button type="button" @click="retakeFace()" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-arrows-rotate"></i> Kunan Muli (Retake)
                            </button>
                        </div>

                    </div>

                    <!-- ============================================== -->
                    <!-- CARD 2: STEP 2 - DRIVER'S LICENSE ID           -->
                    <!-- ============================================== -->
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-4 relative overflow-hidden transition-all"
                         :class="{ 'opacity-60': !faceVerified }">

                        <!-- Lock Overlay if Face Not Verified Yet -->
                        <div x-show="!faceVerified" class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2.5">
                            <i class="fa-solid fa-lock text-amber-600 text-base shrink-0"></i>
                            <div>
                                <strong>Step 2 Naka-Lock:</strong> Kumpletuhin muna ang <b>Step 1 (Face Verification)</b> sa itaas para ma-unlock ang pag-upload ng Driver's License ID.
                            </div>
                        </div>

                        <!-- Unlocked Banner -->
                        <div x-show="faceVerified" class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center justify-between">
                            <span class="flex items-center gap-1.5 font-bold">
                                <i class="fa-solid fa-lock-open text-emerald-600"></i> Na-unlock na ang Step 2: Driver's License ID Upload
                            </span>
                            <span class="text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-extrabold">Ready to Upload</span>
                        </div>

                        <!-- Section Header -->
                        <div class="flex items-center justify-between gap-2 pb-2 border-b border-gray-200">
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-id-card text-nutri-600"></i> Step 2: Driver's License ID (Harap at Likod)
                                </h4>
                                <p class="text-[11px] text-gray-500 mt-0.5">I-upload ang malinaw na litrato ng harap at likod ng lisensya para masuri ng Super Admin.</p>
                            </div>
                            <span class="text-[10px] text-nutri-800 font-extrabold bg-nutri-100 px-2.5 py-1 rounded-lg border border-nutri-200 shrink-0">
                                Front & Back Required
                            </span>
                        </div>

                        <!-- License Number Input -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Driver's License Number *
                            </label>
                            <input type="text" name="license_number" required :disabled="!faceVerified"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none bg-white font-mono disabled:bg-gray-100 disabled:cursor-not-allowed"
                                placeholder="e.g. N02-23-123456">
                        </div>

                        <!-- Dual Upload Grid: Front & Back -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- 1. FRONT ID -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">
                                    Harap ng Lisensya (Front ID) *
                                </label>
                                <input type="file" name="license_image" x-ref="frontInput" @change="handleFront($event)" accept="image/*" class="hidden" :disabled="!faceVerified">

                                <!-- Empty State / Dropzone -->
                                <div x-show="!previewFront" @click="faceVerified ? $refs.frontInput.click() : null"
                                    :class="faceVerified ? 'cursor-pointer hover:border-nutri-500 hover:bg-nutri-50/40' : 'cursor-not-allowed opacity-75'"
                                    class="p-5 rounded-2xl border-2 border-dashed border-gray-300 bg-white text-center transition space-y-2 group">
                                    <div class="w-12 h-12 rounded-2xl bg-nutri-50 text-nutri-700 flex items-center justify-center mx-auto text-xl group-hover:scale-105 transition shadow-2xs">
                                        <i class="fa-solid fa-id-card"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 group-hover:text-nutri-700 transition">I-upload ang Harap (Front)</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG up to 5MB</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gray-100 group-hover:bg-nutri-600 text-gray-700 group-hover:text-white text-xs font-bold transition shadow-2xs">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Pumili ng Larawan
                                    </span>
                                </div>

                                <!-- Preview Card -->
                                <div x-show="previewFront" class="p-3 bg-white rounded-2xl border-2 border-nutri-500 shadow-sm space-y-2.5">
                                    <div class="relative w-full h-40 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group cursor-pointer" @click="zoomModal = 'front'" title="Pindutin para palakihin">
                                        <img :src="previewFront" alt="Front License Preview" class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i> View Full Image
                                        </div>
                                        <button type="button" @click.stop="clearFront()" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition" title="Alisin ang Larawan">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between pt-0.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                            <i class="fa-solid fa-circle-check"></i> Harap Ready
                                        </span>
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" @click="$refs.frontInput.click()" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1">
                                                <i class="fa-solid fa-arrows-rotate"></i> Palitan
                                            </button>
                                            <button type="button" @click="zoomModal = 'front'" class="px-3 py-1.5 rounded-xl bg-nutri-50 hover:bg-nutri-100 text-nutri-700 text-xs font-bold transition flex items-center gap-1">
                                                <i class="fa-solid fa-expand"></i> Zoom
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. BACK ID -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">
                                    Likod ng Lisensya (Back ID) *
                                </label>
                                <input type="file" name="license_back_image" x-ref="backInput" @change="handleBack($event)" accept="image/*" class="hidden" :disabled="!faceVerified">

                                <!-- Empty State / Dropzone -->
                                <div x-show="!previewBack" @click="faceVerified ? $refs.backInput.click() : null"
                                    :class="faceVerified ? 'cursor-pointer hover:border-nutri-500 hover:bg-nutri-50/40' : 'cursor-not-allowed opacity-75'"
                                    class="p-5 rounded-2xl border-2 border-dashed border-gray-300 bg-white text-center transition space-y-2 group">
                                    <div class="w-12 h-12 rounded-2xl bg-nutri-50 text-nutri-700 flex items-center justify-center mx-auto text-xl group-hover:scale-105 transition shadow-2xs">
                                        <i class="fa-solid fa-id-card-clip"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 group-hover:text-nutri-700 transition">I-upload ang Likod (Back)</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG up to 5MB</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gray-100 group-hover:bg-nutri-600 text-gray-700 group-hover:text-white text-xs font-bold transition shadow-2xs">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Pumili ng Larawan
                                    </span>
                                </div>

                                <!-- Preview Card -->
                                <div x-show="previewBack" class="p-3 bg-white rounded-2xl border-2 border-nutri-500 shadow-sm space-y-2.5">
                                    <div class="relative w-full h-40 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group cursor-pointer" @click="zoomModal = 'back'" title="Pindutin para palakihin">
                                        <img :src="previewBack" alt="Back License Preview" class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i> View Full Image
                                        </div>
                                        <button type="button" @click.stop="clearBack()" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition" title="Alisin ang Larawan">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between pt-0.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                            <i class="fa-solid fa-circle-check"></i> Likod Ready
                                        </span>
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" @click="$refs.backInput.click()" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1">
                                                <i class="fa-solid fa-arrows-rotate"></i> Palitan
                                            </button>
                                            <button type="button" @click="zoomModal = 'back'" class="px-3 py-1.5 rounded-xl bg-nutri-50 hover:bg-nutri-100 text-nutri-700 text-xs font-bold transition flex items-center gap-1">
                                                <i class="fa-solid fa-expand"></i> Zoom
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Zoom Lightbox Modal for ID Cards -->
                        <div x-show="zoomModal !== null" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm"
                            @click.self="zoomModal = null" @keydown.escape.window="zoomModal = null">
                            <div class="bg-white p-6 rounded-3xl max-w-lg w-full text-center space-y-4 shadow-2xl relative">
                                <button type="button" @click="zoomModal = null"
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center font-bold">
                                    ✕
                                </button>
                                <h4 class="font-black text-gray-900 font-heading text-sm sm:text-base flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-id-card text-nutri-600"></i>
                                    <span x-text="zoomModal === 'front' ? 'Harap ng Driver\'s License (Front ID)' : 'Likod ng Driver\'s License (Back ID)'"></span>
                                </h4>
                                <div class="w-full h-80 rounded-2xl overflow-hidden border border-gray-200 p-2 bg-gray-50 flex items-center justify-center shadow-inner">
                                    <img :src="zoomModal === 'front' ? previewFront : previewBack" alt="License ID Zoom"
                                        class="max-w-full max-h-full object-contain rounded-lg">
                                </div>
                                <p class="text-xs text-gray-500">Suriin kung malinaw at madaling mabasa ang mga impormasyon sa ID.</p>
                            </div>
                        </div>

                    </div>
                    <!-- END of Step 2 -->

                </div>
                <!-- END of Rider Verification -->

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Complete Home / Base Address in Lipa City</label>
                    <input type="text" name="address_line" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                        placeholder="e.g. 123 J.P. Laurel Highway, Brgy. Sabang, Lipa City">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-nutri-500 text-sm outline-none"
                            placeholder="Confirm password">
                    </div>
                </div>

                <!-- Rider Terms & Conditions Agreement -->
                <div class="p-4 rounded-2xl bg-nutri-50/70 border border-nutri-200 space-y-2">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="rider_terms" x-model="agreed.rider" required
                            class="mt-1 w-4 h-4 text-nutri-600 rounded border-gray-300 focus:ring-nutri-500 cursor-pointer">
                        <label for="rider_terms" class="text-xs text-gray-800 leading-snug cursor-pointer">
                            I have read, understood, and agree to the
                            <button type="button" @click="openTerms('rider')"
                                class="font-bold text-nutri-700 underline hover:text-nutri-900 cursor-pointer">
                                Delivery Rider Terms and Conditions
                            </button>. I understand the requirements on food handling, road safety, customer privacy, and
                            the twice-monthly remittance schedule.
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-extrabold text-sm shadow-md transition font-heading cursor-pointer">
                    Submit Rider Application
                </button>
            </form>

            <!-- Login Redirect -->
            <p class="text-center text-xs text-gray-600 mt-8 pt-6 border-t border-gray-100">
                Already registered?
                <a href="{{ route('login') }}" class="font-extrabold text-nutri-600 hover:text-nutri-700 ml-1">Sign In
                    here</a>
            </p>

        </div>

        <!-- ======================================================== -->
        <!-- TERMS AND CONDITIONS MODAL (DYNAMIC PER TAB / ROLE)      -->
        <!-- ======================================================== -->
        <div x-show="termsModalOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] bg-nutri-950/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6"
            @keydown.escape.window="termsModalOpen = false" role="dialog" aria-modal="true">

            <!-- Modal Dialog Box -->
            <div @click.away="termsModalOpen = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-3"
                class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">

                <!-- Modal Header: Customer -->
                <div x-show="termsRole === 'customer'"
                    class="px-6 py-5 bg-gradient-to-r from-nutri-900 via-nutri-800 to-nutri-950 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-nutri-500/20 border border-nutri-400/30 text-limey-300 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-black uppercase tracking-wider text-limey-300 bg-white/10 px-2 py-0.5 rounded-full">Customer
                                    Agreement</span>
                                <span class="text-[11px] text-nutri-200">NutriGo Lipa</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-black font-heading text-white mt-0.5">Terms and Conditions
                                for Customer</h3>
                        </div>
                    </div>
                    <button type="button" @click="termsModalOpen = false"
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Header: Store -->
                <div x-show="termsRole === 'store'"
                    class="px-6 py-5 bg-gradient-to-r from-emerald-950 via-emerald-900 to-teal-950 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-black uppercase tracking-wider text-emerald-300 bg-white/10 px-2 py-0.5 rounded-full">Store
                                    Partner Agreement</span>
                                <span class="text-[11px] text-emerald-200">Healthy Food Vetting</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-black font-heading text-white mt-0.5">Terms and Condition
                                for Store</h3>
                        </div>
                    </div>
                    <button type="button" @click="termsModalOpen = false"
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Header: Rider -->
                <div x-show="termsRole === 'rider'"
                    class="px-6 py-5 bg-gradient-to-r from-stone-900 via-stone-800 to-lime-950 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-lime-500/20 border border-lime-400/30 text-lime-300 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-black uppercase tracking-wider text-lime-300 bg-white/10 px-2 py-0.5 rounded-full">Delivery
                                    Partner Agreement</span>
                                <span class="text-[11px] text-stone-300">Fleet Standards</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-black font-heading text-white mt-0.5">Terms and Conditions
                                for Delivery Riders</h3>
                        </div>
                    </div>
                    <button type="button" @click="termsModalOpen = false"
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed max-h-[60vh]">

                    <!-- 1. STORE TERMS CONTENT -->
                    <div x-show="termsRole === 'store'" class="space-y-4">
                        <div
                            class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-emerald-600 text-base shrink-0"></i>
                            <span>Pakisuri nang maigi ang mga patakaran bago sumali bilang NutriGo Partner Store sa Lipa
                                City.</span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">1</span>
                                    Nutritional Standards
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Only food products that pass NutriGo's nutritional screening may be approved and listed
                                    on the platform. Our assessment is guided by the World Health Organization's
                                    healthy-diet recommendations, including balance, adequacy, moderation, and diversity.
                                    Products are evaluated based on relevant factors such as ingredients, nutritional
                                    information, sugar, sodium, fats, fiber, and food preparation methods.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">2</span>
                                    Food Information and Approval
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Partners must submit accurate information about their products, including ingredients,
                                    portion sizes, prices, preparation methods, and available nutritional information.
                                    NutriGo may reject products that do not meet its criteria or request additional
                                    information for assessment.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">3</span>
                                    Food Quality and Safety
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Partner establishments are responsible for food preparation, handling, packaging,
                                    storage, and compliance with applicable food safety and sanitation requirements. NutriGo
                                    operates as an online marketplace and does not directly prepare the food offered by its
                                    partners.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">4</span>
                                    Product Monitoring
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may review approved products and suspend or remove listings that no longer meet
                                    its standards, contain inaccurate information, or raise legitimate food quality or
                                    safety concerns.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">5</span>
                                    Remittance Schedule
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Partner remittances are scheduled for the 15th and 30th of every month. Payments are
                                    subject to transaction verification, reconciliation, applicable commissions and fees,
                                    refunds, and other agreed adjustments. If a scheduled date falls on a weekend, public
                                    holiday, or banking non-business day, processing may occur on the next applicable
                                    business day.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">6</span>
                                    Partner Responsibilities
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Partners must maintain accurate product listings, update NutriGo about changes in
                                    ingredients or recipes, fulfill confirmed orders, and comply with applicable laws and
                                    NutriGo's partner policies.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">7</span>
                                    Suspension or Termination
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may suspend or terminate a partnership for violations of these terms, false
                                    information, or the listing of unapproved products. Outstanding eligible payments will
                                    be handled according to the agreed payment arrangements and applicable laws.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center shrink-0">8</span>
                                    Changes to These Terms
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may update these terms when its policies, screening criteria, or applicable
                                    legal requirements change. Partners will be notified of material changes and asked to
                                    accept them where required.
                                </p>
                            </div>
                        </div>

                        <!-- Store Acceptance Note -->
                        <div
                            class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200 text-xs text-emerald-950 space-y-1.5 mt-2">
                            <p
                                class="font-extrabold uppercase tracking-wider text-emerald-900 text-[11px] flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i> Please read all carefully before
                                accepting.
                            </p>
                            <p class="leading-relaxed">
                                I have read, understood, and agree to NutriGo's Partner Terms and Conditions. I understand
                                that only food products approved under NutriGo's nutritional standards may be listed, and
                                that remittances are scheduled for the 15th and 30th of each month.
                            </p>
                        </div>
                    </div>

                    <!-- 2. RIDER TERMS CONTENT -->
                    <div x-show="termsRole === 'rider'" class="space-y-4">
                        <div
                            class="p-3.5 rounded-2xl bg-lime-50 border border-lime-200 text-xs text-lime-950 flex items-center gap-2.5">
                            <i class="fa-solid fa-motorcycle text-lime-600 text-base shrink-0"></i>
                            <span><strong>Riders Terms and Condition:</strong> Please review the following requirements
                                before registering the NutriGo Rider.</span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">1</span>
                                    Rider Eligibility and Registration
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must provide accurate personal and contact information and submit the documents
                                    required by NutriGo and applicable Philippine laws. These may include a valid
                                    government-issued ID, a valid driver's license where required, vehicle registration and
                                    other required vehicle documents, and proof of eligibility to perform delivery services.
                                    Riders must keep their submitted information and documents updated.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">2</span>
                                    Delivery Responsibilities
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must accept and fulfill delivery assignments responsibly, follow the designated
                                    delivery instructions, and collect orders from approved partner establishments. Riders
                                    must deliver orders to the correct customer and promptly inform NutriGo of delays,
                                    failed deliveries, or other problems.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">3</span>
                                    Food Handling and Order Integrity
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must handle all food orders carefully and maintain cleanliness during transport.
                                    Food packaging must remain properly secured, and riders must not open, tamper with,
                                    consume, or intentionally damage customer orders. Any damaged, spilled, missing, or
                                    incorrectly released order must be reported promptly to NutriGo.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">4</span>
                                    Customer Service and Professional Conduct
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must communicate respectfully with customers, partner establishments, and NutriGo
                                    representatives. Harassment, discrimination, threats, fraud, unauthorized disclosure of
                                    customer information, and other inappropriate conduct are prohibited. Customer contact
                                    information may only be used for legitimate delivery-related purposes.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">5</span>
                                    Road Safety and Vehicle Maintenance
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must comply with traffic laws, use appropriate safety equipment such as a helmet
                                    when riding a motorcycle, and maintain their vehicles in safe operating condition.
                                    Riders must never deliver while impaired by alcohol or drugs, use a phone in a manner
                                    that compromises road safety, or engage in reckless driving.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">6</span>
                                    Delivery Fees and Remittance
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Rider compensation, delivery fees, incentives, and any applicable deductions will follow
                                    the payment arrangement communicated and agreed upon by NutriGo and the rider. Where the
                                    rider payment arrangement uses NutriGo's twice-monthly schedule, remittances will be
                                    processed on the 15th and 30th of each month, subject to verification and
                                    reconciliation. If a scheduled date falls on a weekend, public holiday, or banking
                                    non-business day, processing may occur on the next applicable business day. Any
                                    applicable deductions must be disclosed under the agreed arrangement and handled in
                                    accordance with applicable laws.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">7</span>
                                    Cash Handling and Order Payments
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    If a rider is authorized to collect cash or handle customer payments, the rider must
                                    record and remit the correct amount according to NutriGo's procedures. Collected funds
                                    must not be used for personal purposes. Discrepancies, payment disputes, or suspected
                                    fraud must be reported immediately.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">8</span>
                                    Customer Privacy and Confidentiality
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must protect customers' names, addresses, phone numbers, order details, and other
                                    personal information. Such information must not be copied, shared, posted online, or
                                    used for purposes unrelated to fulfilling the delivery, except as permitted or required
                                    by law.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">9</span>
                                    Cancellations, Delays, and Emergencies
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must notify NutriGo promptly when a delivery cannot be completed because of an
                                    emergency, accident, vehicle breakdown, incorrect address, unavailable customer, or
                                    other legitimate issue. Riders must follow the platform's instructions for
                                    cancellations, returns, refunds, and undelivered orders.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">10</span>
                                    Prohibited Activities
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must not falsify delivery records, mark orders as delivered when they have not
                                    been delivered, misuse customer payments, accept unauthorized payments, tamper with
                                    orders, or use another person's account without authorization. Repeated negligence or
                                    serious violations may result in suspension or termination of platform access, subject
                                    to applicable law and any applicable review process.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">11</span>
                                    Accidents, Losses, and Incident Reporting
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Riders must promptly report accidents, injuries, theft, damaged orders, and other
                                    significant incidents. Riders must cooperate with reasonable incident investigations.
                                    Responsibility for losses, insurance coverage, and any deductions will be determined
                                    under the applicable agreement and law; this section does not automatically make the
                                    rider liable for every loss.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">12</span>
                                    Suspension or Termination
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may restrict or terminate a rider's access for fraud, serious safety violations,
                                    repeated failure to fulfill assignments, misuse of customer information, or other
                                    material breaches of these terms. Where appropriate, the rider may be notified and given
                                    an opportunity to respond. Any final payment owed will be handled in accordance with the
                                    applicable agreement and law.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-lime-100 text-lime-800 text-xs font-black flex items-center justify-center shrink-0">13</span>
                                    Amendments to These Terms
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may update these Terms and Conditions to reflect operational changes or legal
                                    requirements. Riders will be informed of material changes and asked to accept them where
                                    required. Changes will be handled in accordance with applicable law and the rider's
                                    agreement.
                                </p>
                            </div>
                        </div>

                        <!-- Rider Acceptance Note -->
                        <div
                            class="p-4 rounded-2xl bg-lime-50/90 border border-lime-200 text-xs text-lime-950 space-y-1.5 mt-2">
                            <p
                                class="font-extrabold uppercase tracking-wider text-lime-900 text-[11px] flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-lime-600"></i> Please read all carefully before
                                accepting.
                            </p>
                            <p class="leading-relaxed">
                                I have read, understood, and agree to NutriGo's Partner Terms and Conditions. I understand
                                that only food products approved under NutriGo's nutritional standards may be listed, and
                                that remittances are scheduled for the 15th and 30th of each month.
                            </p>
                        </div>
                    </div>

                    <!-- 3. CUSTOMER TERMS CONTENT -->
                    <div x-show="termsRole === 'customer'" class="space-y-4">
                        <div
                            class="p-3.5 rounded-2xl bg-nutri-50 border border-nutri-200 text-xs text-nutri-950 flex items-center gap-2.5">
                            <i class="fa-solid fa-user-check text-nutri-600 text-base shrink-0"></i>
                            <span><strong>For NutriGo Customers:</strong> Welcome to NutriGo Lipa! Please review our
                                customer terms and guidelines below.</span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">1</span>
                                    Acceptance of Terms
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    By accessing or using NutriGo, customers agree to comply with these Terms and Conditions
                                    and the applicable platform policies. Customers who do not agree with these terms should
                                    not proceed with using the platform.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">2</span>
                                    About NutriGo
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo is a proposed web-based healthy food marketplace that connects customers with
                                    partner food establishments offering meal options that meet NutriGo's nutritional
                                    screening criteria. NutriGo facilitates online food discovery and ordering but does not
                                    directly prepare or cook the food sold by partner establishments.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">3</span>
                                    Healthy Food Standards
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo accepts food products that pass its nutritional screening process, guided by the
                                    World Health Organization's recommendations on healthy diets. These include
                                    considerations of nutritional balance, adequacy, moderation, and diversity. However,
                                    NutriGo's screening does not mean that every listed meal is suitable for every
                                    individual's dietary needs or medical conditions.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">4</span>
                                    Customer Account and Information
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Customers must provide accurate information when registering and placing orders,
                                    including their name, contact details, delivery address, and other necessary
                                    information. Customers are responsible for maintaining the confidentiality of their
                                    account credentials and notifying NutriGo of suspected unauthorized account access.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">5</span>
                                    Orders and Order Confirmation
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Customers may browse available meals, review product information, select items, and
                                    place orders through NutriGo. Orders are subject to availability and confirmation by the
                                    partner establishment. Customers should review their selections, quantities, prices,
                                    delivery address, and order details before confirming a purchase.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">6</span>
                                    Prices and Payments
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Prices displayed on the platform will be those provided by the partner establishments
                                    and may be subject to applicable delivery fees, service charges, or other disclosed
                                    costs. Customers must pay using the payment methods made available by NutriGo. Any
                                    applicable additional fees must be disclosed before the order is finalized.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">7</span>
                                    Delivery and Order Receipt
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Delivery times are estimates and may be affected by traffic, weather, order volume,
                                    establishment preparation times, or other circumstances. Customers should provide a
                                    complete and accurate delivery address and remain reasonably available to receive their
                                    orders. NutriGo will coordinate with the partner establishment and rider regarding
                                    reported delivery issues.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">8</span>
                                    Cancellations, Refunds, and Complaints
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Order cancellation requests, refunds, and replacements will be handled according to
                                    NutriGo's applicable cancellation and refund policy, the order's status, and applicable
                                    consumer protection laws. Customers should report missing, incorrect, damaged, or
                                    potentially unsafe food orders promptly through the available support channels. Nothing
                                    in these terms removes any rights customers have under applicable law.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">9</span>
                                    Food Quality and Allergies
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Partner establishments are responsible for preparing and handling their food products.
                                    Customers should review available ingredient and allergen information before ordering
                                    and contact the establishment when they need clarification. NutriGo may display
                                    information supplied by its partners, but customers should not assume that meals are
                                    free from allergens or cross-contact unless this has been reliably confirmed.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">10</span>
                                    Proper Use of the Platform
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Customers must use NutriGo lawfully and respectfully. Fraudulent orders, abusive
                                    behavior, payment manipulation, false complaints, attempts to disrupt the platform, and
                                    misuse of another person's account are prohibited. NutriGo may investigate suspected
                                    violations and take proportionate action where appropriate.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">11</span>
                                    Privacy and Personal Information
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo will handle customer personal information in accordance with its Privacy Policy
                                    and applicable Philippine data protection laws. Necessary order information may be
                                    shared with relevant partner establishments, delivery riders, and payment providers to
                                    process transactions, fulfill deliveries, and provide support. Personal information will
                                    not be used or disclosed beyond applicable lawful purposes without an appropriate legal
                                    basis.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">12</span>
                                    Platform Availability
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo aims to maintain a reliable platform but does not guarantee uninterrupted access
                                    at all times. Maintenance, technical issues, internet disruptions, or other
                                    circumstances may temporarily affect service availability.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">13</span>
                                    Changes to These Terms
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    NutriGo may update these Terms and Conditions when its services, policies, or legal
                                    requirements change. Customers will be informed of material changes where appropriate,
                                    and any acceptance required by law will be obtained.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">14</span>
                                    Contact and Customer Support
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    Customers may contact NutriGo through its designated support channels to ask questions,
                                    report order concerns, or request assistance. Relevant contact information and support
                                    availability will be provided through the platform.
                                </p>
                            </div>

                            <div class="rounded-2xl p-4 bg-gray-50/80 border border-gray-100">
                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg bg-nutri-100 text-nutri-800 text-xs font-black flex items-center justify-center shrink-0">15</span>
                                    Applicable Laws
                                </h4>
                                <p class="text-gray-600 text-xs mt-1.5 leading-relaxed pl-8">
                                    These Terms and Conditions are subject to applicable laws and regulations of the
                                    Republic of the Philippines, including relevant consumer protection and data privacy
                                    requirements.
                                </p>
                            </div>
                        </div>

                        <!-- Customer Acceptance Note -->
                        <div
                            class="p-4 rounded-2xl bg-nutri-50/90 border border-nutri-200 text-xs text-nutri-950 space-y-1.5 mt-2">
                            <p
                                class="font-extrabold uppercase tracking-wider text-nutri-900 text-[11px] flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-nutri-600"></i> Please read all carefully before
                                accepting.
                            </p>
                            <p class="leading-relaxed">
                                I have read, understood, and agree to NutriGo's Customer Terms and Conditions and platform
                                policies.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div
                    class="p-4 sm:p-5 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                    <span class="text-[11px] text-gray-500 font-medium text-center sm:text-left">
                        By clicking Agree, you accept our platform policies for your selected role.
                    </span>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button" @click="termsModalOpen = false"
                            class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold transition cursor-pointer">
                            Close
                        </button>
                        <button type="button" @click="acceptTerms()"
                            :class="{
                                'bg-nutri-900 hover:bg-nutri-800 text-white': termsRole === 'customer',
                                'bg-emerald-800 hover:bg-emerald-900 text-white': termsRole === 'store',
                                'bg-stone-900 hover:bg-stone-800 text-limey-400': termsRole === 'rider'
                            }"
                            class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl text-xs font-extrabold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> I Agree & Accept Terms
                        </button>
                    </div>
                </div>

            </div>
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
                }).addTo(storeMap).bindPopup(
                    "<b>Your Store Location</b><br>Drag pin to exact restaurant position!").openPopup();

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
                            storeMap.flyTo([lat, lng], 16, {
                                animate: true,
                                duration: 1
                            });
                            document.getElementById('store_latitude').value = lat.toFixed(7);
                            document.getElementById('store_longitude').value = lng.toFixed(7);
                            const badge = document.getElementById('store_coords_badge');
                            if (badge) badge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                            const nearest = findNearestBarangay(lat, lng);
                            const select = document.getElementById('store_barangay_select');
                            if (select) select.value = nearest;
                        }
                        if (btn) {
                            btn.innerHTML =
                                '<i class="fa-solid fa-circle-check text-limey-300"></i> Pinned!';
                            setTimeout(() => {
                                btn.innerHTML = origHtml;
                            }, 2500);
                        }
                    },
                    function(err) {
                        alert("Could not detect GPS position. Please enable location permissions.");
                        if (btn) btn.innerHTML = origHtml;
                    }, {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 0
                    }
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
