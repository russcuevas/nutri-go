@extends('layouts.app')

@section('title', 'How NutriGo Works | Lipa City Healthy Food & Nutrition Guide')

@section('content')
<div x-data="{
    activeTab: (new URLSearchParams(window.location.search).get('tab') || 'customer'),
    setTab(tab) {
        this.activeTab = tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
        window.scrollTo({ top: 320, behavior: 'smooth' });
    }
}">

    <!-- Header Banner -->
    <div class="bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white py-12 relative overflow-hidden border-b border-nutri-800">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-limey-400/10 rounded-full blur-[90px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-[90px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-limey-400/20 text-limey-300 border border-limey-400/30">
                    NutriGo System Guide & Documentation
                </span>
                <span class="text-xs text-nutri-300 font-medium">Lipa City 2026 Ecosystem</span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight text-white">
                See How NutriGo Works
            </h1>
            <p class="text-sm sm:text-base text-nutri-200 mt-3 max-w-3xl leading-relaxed">
                Piliin ang iyong role sa ibaba para sa kumpleto at malinis na gabay mula sa pag-order ng customer, pamamahala ng kusina ng tindahan, hanggang sa biyahe ng delivery rider.
            </p>

            <!-- 3 Role Tabs Navigation -->
            <div class="mt-8 inline-flex p-1.5 rounded-2xl bg-black/40 backdrop-blur-md border border-white/15 max-w-xl w-full">
                <div class="grid grid-cols-3 gap-1.5 w-full">
                    <!-- Tab 1: Customer -->
                    <button type="button" @click="setTab('customer')"
                        :class="activeTab === 'customer' ? 'bg-limey-400 text-nutri-950 font-black shadow-lg' : 'text-nutri-200 hover:text-white hover:bg-white/10 font-bold'"
                        class="py-2.5 sm:py-3 px-2 sm:px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer font-heading">
                        <i class="fa-solid fa-user text-xs sm:text-sm"></i>
                        <span class="truncate">For Customer</span>
                    </button>

                    <!-- Tab 2: Store Owner -->
                    <button type="button" @click="setTab('store')"
                        :class="activeTab === 'store' ? 'bg-limey-400 text-nutri-950 font-black shadow-lg' : 'text-nutri-200 hover:text-white hover:bg-white/10 font-bold'"
                        class="py-2.5 sm:py-3 px-2 sm:px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer font-heading">
                        <i class="fa-solid fa-store text-xs sm:text-sm"></i>
                        <span class="truncate">For Store Owner</span>
                    </button>

                    <!-- Tab 3: Rider -->
                    <button type="button" @click="setTab('rider')"
                        :class="activeTab === 'rider' ? 'bg-limey-400 text-nutri-950 font-black shadow-lg' : 'text-nutri-200 hover:text-white hover:bg-white/10 font-bold'"
                        class="py-2.5 sm:py-3 px-2 sm:px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer font-heading">
                        <i class="fa-solid fa-motorcycle text-xs sm:text-sm"></i>
                        <span class="truncate">For Rider</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: FOR CUSTOMER -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'customer'" x-cloak x-transition.opacity.duration.300ms class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Left Sticky Sidebar for Customer -->
            <aside class="w-full lg:w-64 shrink-0">
                <nav class="lg:sticky lg:top-28 space-y-6 bg-white lg:bg-transparent p-5 lg:p-0 rounded-3xl border lg:border-none border-gray-200/80 shadow-sm lg:shadow-none">
                    <div class="pb-3 border-b border-gray-200 lg:border-none">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-900">
                            Customer Guide
                        </span>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-limey-500"></span> Getting Started
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#cust-overview" class="block px-3 py-2 rounded-xl text-nutri-900 font-bold hover:bg-nutri-50 transition">
                                    Overview & Vision
                                </a>
                            </li>
                            <li>
                                <a href="#cust-account" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    1. Creating an Account
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Discover Foods
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#cust-explore" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    2. Exploring Lipa Stores
                                </a>
                            </li>
                            <li>
                                <a href="#cust-macros" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    3. Calorie & Macro Info
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Ordering & Payment
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#cust-cart" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    4. Cart & One-Store Policy
                                </a>
                            </li>
                            <li>
                                <a href="#cust-checkout" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    5. Checkout & COD Payment
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Map & Delivery
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#cust-tracking" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    6. Live Rider Map Tracking
                                </a>
                            </li>
                            <li>
                                <a href="#cust-vip" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    7. VIP Club & Free Deliveries
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>

            <!-- Main Content: Customer -->
            <article class="flex-1 max-w-4xl space-y-12">

                <!-- Overview -->
                <section id="cust-overview" class="scroll-mt-28 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        Healthy Eating Made Simple in Lipa City
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Ang <strong>NutriGo</strong> ay ang nangungunang delivery platform sa Lipa City na nakatuon sa malinis na nutrisyon, calorie-controlled diet, at certified healthy kitchens. Dito, bawat pagkain ay may malinaw na bilang ng calories at macros para sa iyong fitness goals.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Step 1: Customer Account -->
                <section id="cust-account" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-100 text-nutri-900">Step 1</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Account Setup</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        1. Paglikha ng Customer Account
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Mabilis at libre ang pag-sign up gamit ang iyong email at cellphone number. I-set ang iyong default Lipa City barangay para agad makita ang pinakamalapit na healthy kitchens sa iyo.
                    </p>
                    <div class="p-5 rounded-2xl bg-nutri-50/70 border border-nutri-200 text-xs text-nutri-900 space-y-2">
                        <p class="font-bold flex items-center gap-2"><i class="fa-solid fa-lightbulb text-amber-500"></i> Pro-Tip:</p>
                        <p>Mag-subscribe sa <a href="{{ route('users.subscriptions.index') }}" class="font-bold text-nutri-700 underline">NutriGo VIP Club</a> para sa <strong>100% Libreng Delivery (Free Delivery Perk)</strong> sa lahat ng order mo!</p>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Step 2: Explore -->
                <section id="cust-explore" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-900">Step 2</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Marketplace</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        2. Pag-explore ng mga Tindahan (Grid / List View)
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pumunta sa <a href="{{ route('users.explore') }}" class="font-bold text-nutri-600 underline">Healthy Foods & Stores</a>. Maaari mong i-filter ayon sa diet tulad ng <em>Fresh Salads & Bowls</em>, <em>High-Protein Gym Meals</em>, <em>Keto & Low-Carb</em>, o <em>Plant-Based Vegan</em>.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-1">
                            <span class="font-extrabold text-nutri-900 flex items-center gap-1.5"><i class="fa-solid fa-border-all text-nutri-600"></i> Grid View Mode</span>
                            <p class="text-gray-500">Magandang visual cards para sa madaling pagtingin sa larawan ng pagkain at presyo.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-1">
                            <span class="font-extrabold text-nutri-900 flex items-center gap-1.5"><i class="fa-solid fa-list text-nutri-600"></i> List View Mode</span>
                            <p class="text-gray-500">Horizontal na ayos na nagpapakita agad ng detalyadong Calories, Protein, Carbs, at Fats.</p>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Step 3: Macros -->
                <section id="cust-macros" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-900">Step 3</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Transparency</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        3. Calorie & Macro Breakdown Transparency
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Walang hulaan. Bawat menu item sa NutriGo ay may sertipikadong nutritional breakdown para manatili ka sa iyong daily calorie deficit o protein target.
                    </p>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                            <span class="text-[10px] font-bold text-amber-800 uppercase block">Calories</span>
                            <span class="font-black text-amber-950 text-sm">Exact kcal</span>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <span class="text-[10px] font-bold text-blue-800 uppercase block">Protein</span>
                            <span class="font-black text-blue-950 text-sm">grams (g)</span>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl border border-rose-200">
                            <span class="text-[10px] font-bold text-rose-800 uppercase block">Carbs</span>
                            <span class="font-black text-rose-950 text-sm">grams (g)</span>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                            <span class="text-[10px] font-bold text-emerald-800 uppercase block">Good Fats</span>
                            <span class="font-black text-emerald-950 text-sm">grams (g)</span>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Step 4: Cart -->
                <section id="cust-cart" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900">Step 4</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Order Policy</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        4. Adding to Cart & Single-Store Policy
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pindutin ang <strong>Add to Cart</strong> sa paborito mong healthy meal. Tandaan na mayroong <em>One-Store per Order Rule</em> upang masiguro na sariwa at mainit na darating ang pagkain nang walang delay o multiple pickups.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Step 5: Checkout & Payment -->
                <section id="cust-checkout" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900">Step 5</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Checkout</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        5. Checkout & Cash on Delivery (COD)
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        I-drop ang eksaktong pin ng iyong bahay o opisina sa integrated Leaflet map. Agad na kakwentahin ng system ang live distance, pamasahe sa delivery (₱40 first 1.5km + ₱10/km), at ETA.
                    </p>
                    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">₱</div>
                            <div>
                                <p class="font-black text-emerald-950 text-sm">Cash on Delivery (COD) - Active Payment</p>
                                <p class="text-emerald-700 text-[11px]">Magbayad nang direkta ng cash pagdating ng rider dala ang iyong pagkain.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Step 6: Tracking (No PIN) -->
                <section id="cust-tracking" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-purple-100 text-purple-900">Step 6</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Delivery</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        6. Live Rider Map Tracking (Real-Time GPS)
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pagka-dispatch ng kusina, makikita mo sa live map ang lokasyon ng naka-assign na delivery rider. <strong>Walang kailangang 4-digit PIN</strong> — aabangan mo lamang ang rider sa labas at iaabot ang pagkain nang mabilis at maginhawa.
                    </p>
                </section>

                <!-- Step 7: VIP -->
                <section id="cust-vip" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900">Step 7</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Exclusive Perks</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        7. NutriGo VIP Club & Free Deliveries
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Makakuha ng hanggang ₱50 libreng bawas sa delivery fee sa bawat order, tailored meal prep schedules, at eksklusibong recipe videos mula sa Lipa fitness creators.
                    </p>
                    <a href="{{ route('users.subscriptions.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-nutri-950 font-bold text-xs shadow-sm transition">
                        <i class="fa-solid fa-crown"></i> Tingnan ang VIP Plans
                    </a>
                </section>

            </article>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: FOR STORE OWNER -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'store'" x-cloak x-transition.opacity.duration.300ms class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Left Sticky Sidebar for Store Owner -->
            <aside class="w-full lg:w-64 shrink-0">
                <nav class="lg:sticky lg:top-28 space-y-6 bg-white lg:bg-transparent p-5 lg:p-0 rounded-3xl border lg:border-none border-gray-200/80 shadow-sm lg:shadow-none">
                    <div class="pb-3 border-b border-gray-200 lg:border-none">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">
                            Merchant Guide
                        </span>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Store Onboarding
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#store-overview" class="block px-3 py-2 rounded-xl text-nutri-900 font-bold hover:bg-nutri-50 transition">
                                    Merchant Overview
                                </a>
                            </li>
                            <li>
                                <a href="#store-register" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    1. Store Registration
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Menu & Kitchen
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#store-menu" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    2. Menu & Nutrition Setup
                                </a>
                            </li>
                            <li>
                                <a href="#store-status" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    3. Open/Close & Operating Hours
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Orders & Delivery
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#store-orders" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    4. Managing Live Kitchen Orders
                                </a>
                            </li>
                            <li>
                                <a href="#store-dispatch" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    5. Rider Dispatch & Handover
                                </a>
                            </li>
                            <li>
                                <a href="#store-payouts" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    6. Earnings & Remittance
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>

            <!-- Main Content: Store Owner -->
            <article class="flex-1 max-w-4xl space-y-12">

                <!-- Merchant Overview -->
                <section id="store-overview" class="scroll-mt-28 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        Maging Opisyal na Healthy Kitchen Partner sa Lipa
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Ikinokonekta ng NutriGo ang iyong kusina sa libu-libong health-conscious customers, gym goers, at professionals sa buong Lipa City na naghahanap ng calorie-conscious at malinis na pagkain araw-araw.
                    </p>
                    <div class="p-6 rounded-3xl bg-gradient-to-r from-blue-900 to-nutri-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-black text-base font-heading">Wala ka pa bang Store Account?</h4>
                            <p class="text-xs text-blue-200 mt-0.5">Mag-rehistro ng iyong tindahan sa loob lamang ng 3 minuto.</p>
                        </div>
                        <a href="{{ route('register') }}?tab=store" class="px-5 py-2.5 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-black text-xs shrink-0 transition font-heading">
                            Register Store Now <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 1 -->
                <section id="store-register" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 1</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Onboarding</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        1. Pag-rehistro ng Tindahan & Verification
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pumunta sa <a href="{{ route('register') }}?tab=store" class="font-bold text-nutri-600 underline">Store Registration Tab</a>. Ilagay ang pangalan ng tindahan, deskripsyon, exact map location sa Lipa City, contact number, at GCash details. Susuriin ng Super Admin ang iyong dokumento para sa agarang pag-apruba.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 2 -->
                <section id="store-menu" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 2</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Menu Management</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        2. Pagdaragdag ng Menu at Nutritional Values
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sa iyong <strong>Store Portal</strong>, madaling magdagdag ng mga pagkain. Para sa bawat putahe, ilagay ang:
                    </p>
                    <ul class="space-y-2 text-xs text-gray-600 list-disc list-inside">
                        <li><strong>High-Quality Food Photo:</strong> Masarap at totoong kuha ng lutong ulam o bowl.</li>
                        <li><strong>Dietary Category:</strong> Salads, High-Protein, Keto, Vegan, o Clean Meal Prep.</li>
                        <li><strong>Nutritional Values:</strong> Calories (kcal), Protein (g), Carbohydrates (g), at Fats (g).</li>
                        <li><strong>Price & Availability:</strong> Pwede mong i-toggle ang "Out of Stock" kapag naubos ang ingredients.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 3 -->
                <section id="store-status" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 3</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Store Hours</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        3. Open / Closed Status & Operating Hours
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        May 1-click status switch sa tuktok ng iyong Store Dashboard. Kapag <em>Closed</em> ang tindahan, hindi makakapag-checkout ang customers upang maiwasan ang unfulfilled orders. Pwede ring mag-upload ng iyong GCash QR code para sa mabilisang customer direct payment option.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 4 -->
                <section id="store-orders" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 4</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Kitchen Operations</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        4. Pamamahala ng Live Kitchen Orders
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Kapag pumasok ang order, maririnig mo ang notification alert sa dashboard:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200">
                            <span class="font-bold text-amber-900 block mb-1">1. Pending Store</span>
                            <p class="text-amber-800">I-review ang items at customer notes bago pindutin ang "Accept Order".</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200">
                            <span class="font-bold text-blue-900 block mb-1">2. Preparing Food</span>
                            <p class="text-blue-800">Lutuin at ibalot ang pagkain gamit ang eco-friendly food packaging.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200">
                            <span class="font-bold text-emerald-900 block mb-1">3. Ready for Pickup</span>
                            <p class="text-emerald-800">I-update ang status upang abisuhan ang naka-assign na delivery rider.</p>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 5 -->
                <section id="store-dispatch" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 5</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Rider Handover</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        5. Rider Dispatch & Food Handover
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Darating ang naka-assign na NutriGo rider sa iyong tindahan bitbit ang thermal bag. I-verify ang Order Reference ID (hal. <code>NTR-2026-XXXXX</code>) at iaabot ang pagkain para sa agarang pagbiyahe patungo sa customer.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Store Step 6 -->
                <section id="store-payouts" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-900">Step 6</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Financials</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        6. Store Earnings, COD Remittance & Analytics
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Lahat ng kinita mula sa food sales ay real-time na nakatala sa iyong sales ledger. Para sa COD orders, ang rider ang kukuha ng cash at magre-remit sa pamamagitan ng standard protocol ng platform.
                    </p>
                </section>

            </article>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: FOR RIDER -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'rider'" x-cloak x-transition.opacity.duration.300ms class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Left Sticky Sidebar for Rider -->
            <aside class="w-full lg:w-64 shrink-0">
                <nav class="lg:sticky lg:top-28 space-y-6 bg-white lg:bg-transparent p-5 lg:p-0 rounded-3xl border lg:border-none border-gray-200/80 shadow-sm lg:shadow-none">
                    <div class="pb-3 border-b border-gray-200 lg:border-none">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">
                            Rider Operations
                        </span>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-limey-500"></span> Joining Fleet
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#rider-overview" class="block px-3 py-2 rounded-xl text-nutri-900 font-bold hover:bg-nutri-50 transition">
                                    Rider Overview
                                </a>
                            </li>
                            <li>
                                <a href="#rider-apply" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    1. Requirements & Sign-up
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Duty & Dispatch
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#rider-duty" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    2. Going Online in Lipa
                                </a>
                            </li>
                            <li>
                                <a href="#rider-pickup" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    3. Navigating to Store & Pickup
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-nutri-900 font-extrabold text-[11px] tracking-wider uppercase flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Delivery & Handover
                        </p>
                        <ul class="mt-2.5 space-y-1 text-xs">
                            <li>
                                <a href="#rider-delivery" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    4. Map Pin Navigation & Handover
                                </a>
                            </li>
                            <li>
                                <a href="#rider-cod" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    5. Collecting COD Cash Payment
                                </a>
                            </li>
                            <li>
                                <a href="#rider-earnings" class="block px-3 py-2 rounded-xl text-gray-600 hover:text-nutri-700 hover:bg-gray-50 transition font-medium">
                                    6. Rider Delivery Fares & Tips
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>

            <!-- Main Content: Rider -->
            <article class="flex-1 max-w-4xl space-y-12">

                <!-- Rider Overview -->
                <section id="rider-overview" class="scroll-mt-28 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        Kumita Bilang NutriGo Delivery Rider sa Lipa City
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Maging parte ng dedikadong rider fleet sa Lipa! May maayos na compensation kada biyahe, automated GPS routing patungo sa mga kilalang barangay, at transparent delivery fare calculation (₱40 base + ₱10 kada kilometro).
                    </p>
                    <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-950 via-nutri-900 to-limey-950 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-black text-base font-heading">Gusto mo bang mag-apply bilang Rider?</h4>
                            <p class="text-xs text-limey-200 mt-0.5">Mag-submit ng requirements online at magsimulang kumita.</p>
                        </div>
                        <a href="{{ route('register') }}?tab=rider" class="px-5 py-2.5 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-black text-xs shrink-0 transition font-heading">
                            Apply as Rider <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 1 -->
                <section id="rider-apply" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 1</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Requirements</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        1. Mga Kinakailangan at Pagsusumite
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pumunta sa <a href="{{ route('register') }}?tab=rider" class="font-bold text-nutri-600 underline">Rider Application Portal</a>. Ihanda ang:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
                            <span class="font-bold text-nutri-900 block mb-1"><i class="fa-solid fa-id-card text-emerald-600 mr-1"></i> Driver's License</span>
                            <p class="text-gray-500">Valid Professional o Non-Professional LTO Driver's License.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
                            <span class="font-bold text-nutri-900 block mb-1"><i class="fa-solid fa-motorcycle text-limey-600 mr-1"></i> OR / CR</span>
                            <p class="text-gray-500">Updated LTO Certificate of Registration & Official Receipt ng motor.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-gray-200 shadow-sm">
                            <span class="font-bold text-nutri-900 block mb-1"><i class="fa-solid fa-mobile-screen text-blue-600 mr-1"></i> Smartphone</span>
                            <p class="text-gray-500">Android o iOS device na may GPS at mobile internet data.</p>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 2 -->
                <section id="rider-duty" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 2</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Rider Portal</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        2. Pag-online sa Rider Dashboard
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        I-open ang iyong <strong>Rider Dashboard</strong> at i-tap ang <em>"Go Active / Online"</em> toggle. Awtomatikong ibibigay sa pinakamalapit na online rider ang mga bagong luto at ready-to-deliver orders mula sa stores sa Lipa.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 3 -->
                <section id="rider-pickup" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 3</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Store Pickup</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        3. Pagbiyahe sa Tindahan at Pagkuha ng Pagkain
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sundin ang ruta sa Leaflet Map patungo sa store. Pagdating sa kusina, sabihin ang Order Number, i-check kung maayos ang takip at supot ng pagkain, at ilagay ito sa iyong thermal bag upang manatiling sariwa at mainit.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 4 (No PIN) -->
                <section id="rider-delivery" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 4</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Customer Delivery</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        4. Map Pin Navigation patungo sa Customer
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        I-click ang ruta patungo sa eksaktong barangay at street landmark ng customer. Real-time na makikita ng customer ang iyong pagdating sa kanilang mapa.
                    </p>
                    <div class="p-4 rounded-2xl bg-limey-50 border border-limey-200 text-xs text-nutri-950 font-medium">
                        <i class="fa-solid fa-check-circle text-limey-700 mr-1.5"></i>
                        <strong>Paalala:</strong> Hindi na kailangan ng anumang 4-digit PIN. Pagdating mo sa lokasyon, tawagan o i-message lamang ang customer upang lumabas at iabot nang magalang ang kanilang pagkain.
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 5 -->
                <section id="rider-cod" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 5</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Cash Handover</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        5. Pagkolekta ng Cash on Delivery (COD)
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Para sa COD orders, singilin ang eksaktong <strong>Grand Total</strong> na nakatala sa iyong Rider App. Magdala lagi ng barya o panukli para sa maayos at mabilis na transaksyon sa customer.
                    </p>
                </section>

                <hr class="border-gray-100">

                <!-- Rider Step 6 -->
                <section id="rider-earnings" class="scroll-mt-28 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-limey-200 text-nutri-950">Step 6</span>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Income</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        6. Rider Fares, Tips & Trip Completion
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pagkaabot ng pagkain at kabayaran, pindutin ang <strong>"Mark as Delivered"</strong> button. Agad na mapupunta ang delivery fee sa iyong kinikita para sa araw na iyon, at magiging available ka na muli para sa susunod na biyahe!
                    </p>
                </section>

            </article>
        </div>
    </div>

    <!-- Call to Action Footer Card -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14">
        <div class="p-8 rounded-3xl bg-gradient-to-r from-nutri-900 to-emerald-950 text-white flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
            <div>
                <h3 class="text-xl font-black font-heading">May mga katanungan pa tungkol sa NutriGo?</h3>
                <p class="text-xs text-nutri-200 mt-1">
                    Ang aming support team sa Lipa City ay handang tumulong sa customers, store partners, at riders.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('users.explore') }}" class="px-5 py-3 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-black text-xs shadow-md transition font-heading">
                    Explore Healthy Foods <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
                <a href="{{ route('register') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/20">
                    Join Ecosystem
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
