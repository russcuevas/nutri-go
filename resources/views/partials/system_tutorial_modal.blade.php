<!-- Interactive System Tutorial Modal ("See How It Works") -->
<div x-data="{
        isOpen: false,
        currentStep: 1,
        totalSteps: 5,
        next() {
            if (this.currentStep < this.totalSteps) this.currentStep++;
        },
        prev() {
            if (this.currentStep > 1) this.currentStep--;
        },
        goTo(step) {
            this.currentStep = step;
        },
        close() {
            this.isOpen = false;
        }
     }"
     @open-system-tutorial.window="isOpen = true; currentStep = 1"
     x-show="isOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] overflow-y-auto bg-nutri-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
     @keydown.escape.window="close()">

    <div @click.away="close()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-white/30 overflow-hidden flex flex-col">

        <!-- Top Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-nutri-950 via-nutri-900 to-emerald-950 text-white flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-36 h-36 bg-limey-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-limey-400 to-emerald-500 text-nutri-950 flex items-center justify-center font-black text-lg shadow">
                    🌱
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-limey-300 bg-white/10 px-2 py-0.5 rounded-full">
                            System Guide
                        </span>
                        <span class="text-xs text-nutri-200 font-semibold" x-text="'Hakbang ' + currentStep + ' ng ' + totalSteps"></span>
                    </div>
                    <h3 class="text-base sm:text-lg font-black font-heading text-white mt-0.5">
                        Paano Gamitin ang NutriGo Lipa
                    </h3>
                </div>
            </div>

            <button @click="close()" class="relative z-10 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition hover:scale-105 active:scale-95">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Step Indicator Progress Bar -->
        <div class="h-1.5 bg-gray-100 w-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-limey-400 to-emerald-500 transition-all duration-300"
                 :style="'width: ' + (currentStep / totalSteps * 100) + '%'"></div>
        </div>

        <!-- Dynamic Step Content -->
        <div class="p-6 sm:p-8 min-h-[340px] flex flex-col justify-between">
            
            <!-- Step 1: Account Creation & Login -->
            <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-nutri-50 text-nutri-700 text-2xl flex items-center justify-center shrink-0">
                        👤
                    </span>
                    <div>
                        <span class="text-[11px] font-black text-nutri-600 uppercase tracking-wider">Hakbang 1: Paggawa ng Account</span>
                        <h4 class="text-xl font-black text-gray-900 font-heading">Pumili ng Tamang Account Role</h4>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    Maaari kang mag-sign up gamit ang iyong email at password. Pumili mula sa 3 uri ng account:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                    <div class="p-3.5 rounded-xl bg-nutri-50 border border-nutri-100">
                        <p class="text-xs font-bold text-nutri-900">🛒 Customer</p>
                        <p class="text-[11px] text-gray-500 mt-1">Umorder ng calorie-counted food at sumali sa VIP prep.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-100">
                        <p class="text-xs font-bold text-emerald-900">🏪 Store Partner</p>
                        <p class="text-[11px] text-gray-500 mt-1">Magbenta ng healthy dishes at health supplements sa Lipa.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-limey-50 border border-limey-200">
                        <p class="text-xs font-bold text-nutri-900">🛵 Rider</p>
                        <p class="text-[11px] text-gray-500 mt-1">Mag-deliver gamit ang smart GPS trip routing at PIN security.</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Discovering Healthy Meals & Filters -->
            <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 text-2xl flex items-center justify-center shrink-0">
                        🥗
                    </span>
                    <div>
                        <span class="text-[11px] font-black text-emerald-600 uppercase tracking-wider">Hakbang 2: Pagpili ng Pagkain</span>
                        <h4 class="text-xl font-black text-gray-900 font-heading">Explore at Calorie Transparency</h4>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    Gamitin ang mga smart filters para mahanap ang swak sa iyong fitness routine:
                </p>
                <div class="space-y-2 text-xs text-gray-700">
                    <div class="flex items-center gap-2 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="font-bold text-emerald-600">📍 Stores Near Me:</span>
                        <span>Auto-sort ayon sa layo ng tindahan sa Lipa City.</span>
                    </div>
                    <div class="flex items-center gap-2 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="font-bold text-rose-600">🔥 Calorie Range:</span>
                        <span>Pumili ng Under 300 kcal (Detox), 300-500 kcal, o 500+ kcal (Gym/High Energy).</span>
                    </div>
                    <div class="flex items-center gap-2 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="font-bold text-nutri-600">▦ Grid / List View:</span>
                        <span>Madaling magpalit ng view style sa isang pindot lamang.</span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Cart & Checkout -->
            <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 text-2xl flex items-center justify-center shrink-0">
                        🛒
                    </span>
                    <div>
                        <span class="text-[11px] font-black text-amber-600 uppercase tracking-wider">Hakbang 3: Pag-order</span>
                        <h4 class="text-xl font-black text-gray-900 font-heading">Cart at Special Notes</h4>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    I-click ang <strong>Add to Cart</strong>. Bawat order ay nakapaloob sa isang tindahan para manatiling mainit at sariwa ang pagkain pagdating sa iyo.
                </p>
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 space-y-1">
                    <p class="font-bold"><i class="fa-solid fa-bell mr-1"></i> Paalala sa Multi-Store:</p>
                    <p>Kung nais mong umorder sa ibang tindahan, kukumpirmahin muna ng cart bago ito palitan.</p>
                </div>
            </div>

            <!-- Step 4: Payment Methods -->
            <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 text-2xl flex items-center justify-center shrink-0">
                        💳
                    </span>
                    <div>
                        <span class="text-[11px] font-black text-blue-600 uppercase tracking-wider">Hakbang 4: Pagbabayad</span>
                        <h4 class="text-xl font-black text-gray-900 font-heading">GCash, Maya QR Ph, at COD</h4>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    Pumili ng nais na paraan ng pagbabayad pagdating sa Checkout:
                </p>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-800 font-bold border border-blue-100">
                        <div class="text-lg mb-1">📱</div>
                        GCash E-Wallet
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-800 font-bold border border-emerald-100">
                        <div class="text-lg mb-1">💚</div>
                        Maya / QR Ph
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-800 font-bold border border-amber-100">
                        <div class="text-lg mb-1">💵</div>
                        Cash on Delivery
                    </div>
                </div>
                <p class="text-[11px] text-gray-500">Ang mga VIP Club members ay awtomatikong may libreng delivery fee sa Lipa City.</p>
            </div>

            <!-- Step 5: Live Rider Map Tracking -->
            <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 text-2xl flex items-center justify-center shrink-0">
                        🗺️
                    </span>
                    <div>
                        <span class="text-[11px] font-black text-blue-600 uppercase tracking-wider">Hakbang 5: Pagdating ng Pagkain</span>
                        <h4 class="text-xl font-black text-gray-900 font-heading">Live Map Tracking ng Assigned Rider</h4>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    Aabangan mo lang ang iyong order sa mapa! Makikita mo ang real-time na biyahe ng iyong assigned rider patungo sa iyong lokasyon sa Lipa.
                </p>
                <div class="p-4 rounded-2xl bg-gray-900 text-white flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-limey-400">Live Delivery Status:</span>
                        <p class="text-xs text-gray-300">Rider on the way • Lipa City Map</p>
                    </div>
                    <div class="px-3 py-1.5 rounded-xl bg-limey-400/20 border border-limey-400/30 text-limey-300 font-bold text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-motorcycle text-limey-400"></i> No PIN Needed
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    Walang PIN na kailangan—diretso nang iaabot ng rider ang sariwang pagkain pagdating sa iyong address.
                </p>
            </div>

            <!-- Navigation Controls & Dots -->
            <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between">
                <!-- Dots -->
                <div class="flex items-center gap-1.5">
                    <template x-for="step in totalSteps" :key="step">
                        <button type="button" @click="goTo(step)"
                                class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                :class="currentStep === step ? 'w-6 bg-nutri-900' : 'bg-gray-200 hover:bg-gray-300'"></button>
                    </template>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-2">
                    <button type="button" x-show="currentStep > 1" @click="prev()" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Bumalik
                    </button>

                    <button type="button" x-show="currentStep < totalSteps" @click="next()" class="px-5 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        Susunod <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>

                    <div x-show="currentStep === totalSteps" class="flex items-center gap-2">
                        <a href="{{ route('docs.guide') }}" class="px-3.5 py-2 rounded-xl border border-nutri-300 text-nutri-800 text-xs font-bold hover:bg-nutri-50 transition">
                            Full Docs
                        </a>
                        <button type="button" @click="close()" class="px-5 py-2.5 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 text-xs font-black transition shadow-sm font-heading">
                            Mag-explore Na! <i class="fa-solid fa-check ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
