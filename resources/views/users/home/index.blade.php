@extends('layouts.app')

@section('title', 'NutriGo | Lipa City Healthy Food & Nutrition Delivery')

@section('content')
    <!-- Custom Styles for Socia-inspired Rich Animations & Floating Effects -->
    <style>
        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-14px) rotate(2deg);
            }
        }

        @keyframes float-reverse {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(14px) rotate(-2deg);
            }
        }

        @keyframes float-badge {

            0%,
            100% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-10px) scale(1.04);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.4;
                transform: scale(1);
            }

            50% {
                opacity: 0.75;
                transform: scale(1.12);
            }
        }

        @keyframes morph-blob-1 {

            0%,
            100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                transform: rotate(0deg) scale(1);
            }

            50% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
                transform: rotate(180deg) scale(1.08);
            }
        }

        @keyframes morph-blob-2 {

            0%,
            100% {
                border-radius: 40% 60% 60% 40% / 40% 40% 60% 60%;
                transform: rotate(0deg);
            }

            50% {
                border-radius: 60% 30% 40% 70% / 50% 70% 30% 50%;
                transform: rotate(-180deg) scale(1.1);
            }
        }

        @keyframes marquee-slide {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes shimmer-sweep {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(200%);
            }
        }

        @keyframes ripple-wave {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }

            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        .animate-float-slow {
            animation: float-slow 5s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: float-reverse 6s ease-in-out infinite;
        }

        .animate-float-badge {
            animation: float-badge 4s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .animate-blob-1 {
            animation: morph-blob-1 12s ease-in-out infinite;
        }

        @keyframes marquee-scroll {
            0% {
                transform: translate3d(0, 0, 0);
            }

            100% {
                transform: translate3d(-100%, 0, 0);
            }
        }

        .marquee-container {
            display: flex;
            overflow: hidden;
            user-select: none;
            width: 100%;
            contain: content;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
            pointer-events: none;
        }

        .marquee-track {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            gap: 2rem;
            padding-right: 2rem;
            animation: marquee-scroll 25s linear infinite;
            will-change: transform;
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .glass-card-hover {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card-hover:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(163, 230, 53, 0.5);
            box-shadow: 0 20px 40px -15px rgba(163, 230, 53, 0.25);
        }

        .shimmer-btn {
            position: relative;
            overflow: hidden;
        }

        .shimmer-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.45), transparent);
            transform: skewX(-25deg);
            animation: shimmer-sweep 3s infinite;
        }

        /* 3D Tilt container */
        .tilt-card-container {
            perspective: 1000px;
            transform-style: preserve-3d;
        }

        .tilt-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform-style: preserve-3d;
            will-change: transform;
        }

        .tilt-card-inner {
            transform: translateZ(30px);
        }

        /* Scroll Reveal Animation Utility */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-on-scroll.is-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* Swiper Customer Reviews Carousel Styles */
        .swiper-pagination-reviews .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #cbd5e1;
            opacity: 0.6;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 9999px;
            margin: 0 4px !important;
        }

        .swiper-pagination-reviews .swiper-pagination-bullet-active {
            width: 28px;
            background: #16a34a;
            opacity: 1;
            border-radius: 9999px;
        }

        .customer-reviews-swiper .swiper-slide {
            height: auto;
            display: flex;
        }
    </style>

    <div x-data="{ showAllStoresModal: false, storeSearch: '', selectedCategory: 'all' }">

        <!-- Hero Section with Interactive Canvas Splash & Fluid Blobs -->
        <section id="hero-interactive"
            class="relative overflow-hidden bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white pt-20 pb-24 lg:pt-28 lg:pb-32 select-none">

            <!-- Interactive Background Particle Splash Canvas -->
            <canvas id="hero-splash-canvas"
                class="absolute inset-0 w-full h-full pointer-events-auto opacity-75 z-0"></canvas>

            <!-- Animated Morphing Fluid Blobs (Socia Splash Style) -->
            <div
                class="absolute -top-32 -right-32 w-[550px] h-[550px] bg-gradient-to-br from-limey-400/25 via-emerald-400/15 to-transparent blur-[80px] pointer-events-none animate-blob-1">
            </div>
            <div class="absolute top-1/3 -left-36 w-[500px] h-[500px] bg-gradient-to-tr from-emerald-500/30 via-teal-400/20 to-transparent blur-[90px] pointer-events-none animate-blob-2"
                style="animation-delay: -4s;"></div>
            <div class="absolute -bottom-24 right-1/4 w-[450px] h-[450px] bg-gradient-to-r from-teal-400/20 to-limey-400/15 blur-[70px] pointer-events-none animate-blob-1"
                style="animation-delay: -8s;"></div>

            <!-- Mouse Spotlight Glow -->
            <div id="hero-spotlight"
                class="absolute w-[600px] h-[600px] rounded-full bg-radial from-limey-400/15 via-emerald-500/5 to-transparent blur-[100px] pointer-events-none transition-all duration-150 ease-out -translate-x-1/2 -translate-y-1/2 hidden md:block z-0">
            </div>

            <!-- Floating Interactive Decorative Splash Chips -->
            <div
                class="absolute top-12 left-8 hidden xl:flex items-center gap-2.5 px-4 py-2 rounded-full glass-card glass-card-hover text-xs font-black text-limey-300 animate-float-slow shadow-xl pointer-events-none z-10">
                <span class="text-base">🥗</span> 100% Lipa Farm Fresh
            </div>
            <div
                class="absolute top-28 right-10 hidden xl:flex items-center gap-2.5 px-4 py-2 rounded-full glass-card glass-card-hover text-xs font-black text-emerald-300 animate-float-reverse shadow-xl pointer-events-none z-10">
                <span class="text-base">🔥</span> Real Macro Count
            </div>
            <div
                class="absolute bottom-16 left-12 hidden xl:flex items-center gap-2.5 px-4 py-2 rounded-full glass-card glass-card-hover text-xs font-black text-teal-200 animate-float-badge shadow-xl pointer-events-none z-10">
                <span class="text-base">🛵</span> ₱40 Fast Point-to-Point
            </div>
            <div class="absolute top-1/2 right-4 hidden 2xl:flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-card text-xs font-black text-amber-300 animate-float-slow shadow-lg pointer-events-none z-10"
                style="animation-delay: -2s;">
                <span class="text-sm">🥑</span> 0g Refined Sugar
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                    <!-- Hero Left: Text & CTA -->
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <!-- Location Badge with pulsating beacon -->
                        <div
                            class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-xs font-black text-limey-300 backdrop-blur-md shadow-inner hover:scale-105 transition-transform duration-300 cursor-default">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-limey-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-limey-500"></span>
                            </span>
                            <i class="fa-solid fa-location-dot text-limey-400"></i> Lipa City, Batangas
                        </div>

                        <!-- Main Heading with vibrant animated gradient typography -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight font-heading leading-[1.08]">
                            Healthy Food, <br class="hidden sm:inline">
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-limey-400 via-emerald-300 to-teal-200 drop-shadow-sm">Anytime,
                                Anywhere.</span>
                        </h1>

                        <!-- Subtitle -->
                        <p
                            class="text-base sm:text-lg text-nutri-100 max-w-2xl leading-relaxed mx-auto lg:mx-0 font-normal">
                            Discover verified organic restaurants, high-protein gym bowls, keto meals, certified
                            supplements,
                            and creator-led cooking vlogs delivered fast to your doorstep across Lipa.
                        </p>

                        <!-- Search & Quick Finder -->
                        <form action="{{ route('users.explore') }}" method="GET"
                            class="p-2 sm:p-2.5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-xl shadow-2xl flex flex-col sm:flex-row gap-2 max-w-xl mx-auto lg:mx-0 transition-all duration-300 hover:border-limey-400/50 hover:shadow-limey-400/10">
                            <div
                                class="flex-1 flex items-center pl-3.5 pr-2 py-2.5 rounded-xl bg-white text-gray-900 shadow-inner">
                                <i class="fa-solid fa-magnifying-glass text-nutri-600 mr-2.5 text-sm"></i>
                                <input type="text" name="q"
                                    placeholder="Search salads, keto, calories, protein, stores..."
                                    class="w-full text-xs sm:text-sm outline-none bg-transparent font-medium">
                            </div>
                            <button type="submit"
                                class="shimmer-btn px-6 py-3 rounded-xl bg-limey-400 hover:bg-limey-300 text-nutri-950 font-black text-sm shadow-lg transition-all duration-300 font-heading shrink-0 flex items-center justify-center gap-2 hover:scale-[1.03] active:scale-95">
                                <i class="fa-solid fa-magnifying-glass"></i> Search Lipa Food
                            </button>
                        </form>

                        <!-- Quick Guide & Interactive Tour Action Buttons -->
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-1">
                            <a href="{{ route('docs.guide') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold transition hover:scale-105 backdrop-blur-md">
                                <i class="fa-solid fa-circle-question text-limey-400"></i> See How It Works (Docs)
                            </a>
                            <button type="button" @click="$dispatch('open-system-tutorial')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-limey-400/20 hover:bg-limey-400/30 border border-limey-400/30 text-limey-300 text-xs font-bold transition hover:scale-105 backdrop-blur-md">
                                <i class="fa-solid fa-play text-limey-400"></i> Quick Interactive Tour
                            </button>
                        </div>

                        <!-- Key Metrics & Perks with Animated Count-Up -->
                        <div
                            class="pt-4 grid grid-cols-3 gap-4 border-t border-white/15 max-w-lg mx-auto lg:mx-0 text-left">
                            <div class="hover:scale-105 transition-transform duration-300">
                                <div class="text-2xl sm:text-3xl font-black text-limey-400 font-heading" data-counter="100"
                                    data-suffix="%">100%</div>
                                <div class="text-[11px] text-nutri-200 font-medium">Calorie & Macro Counted</div>
                            </div>
                            <div class="hover:scale-105 transition-transform duration-300">
                                <div class="text-2xl sm:text-3xl font-black text-white font-heading" data-counter="72"
                                    data-suffix="">72</div>
                                <div class="text-[11px] text-nutri-200 font-medium">Lipa Barangays Covered</div>
                            </div>
                            <div class="hover:scale-105 transition-transform duration-300">
                                <div class="text-2xl sm:text-3xl font-black text-emerald-300 font-heading" data-counter="40"
                                    data-prefix="₱">₱40</div>
                                <div class="text-[11px] text-nutri-200 font-medium">Fair Base Delivery Fare</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Right: Interactive 3D Tilt Showcase Card -->
                    <div id="hero-tilt-wrapper" class="lg:col-span-5 relative tilt-card-container py-4">
                        <div id="hero-3d-card"
                            class="relative mx-auto max-w-md tilt-card cursor-pointer will-change-transform">
                            <!-- Ambient Glow Behind Main Card -->
                            <div
                                class="absolute -inset-3 bg-gradient-to-r from-limey-400/30 via-emerald-500/30 to-teal-400/30 rounded-3xl blur-2xl opacity-80 group-hover:opacity-100 transition duration-1000 animate-pulse-glow pointer-events-none">
                            </div>

                            <!-- Main Hero Image Showcase Card -->
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-white/25 bg-nutri-950/60 backdrop-blur-2xl group transition-all duration-500 pointer-events-none">
                                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&q=80"
                                    alt="NutriGo Fresh Salad"
                                    class="w-full h-96 object-cover group-hover:scale-105 transition duration-700 pointer-events-none">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-nutri-950/95 via-nutri-950/45 to-transparent pointer-events-none">
                                </div>

                                <!-- Floating Glass Nutrition Tags -->
                                <div
                                    class="absolute bottom-6 left-6 right-6 space-y-2.5 tilt-card-inner pointer-events-none">
                                    <span
                                        class="px-3.5 py-1.5 rounded-full bg-limey-400 text-nutri-950 text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5 shadow-lg">
                                        <i class="fa-solid fa-heart text-rose-600 animate-pulse"></i> Healthy Choice of the
                                        Day
                                    </span>
                                    <h3 class="text-xl sm:text-2xl font-black text-white font-heading leading-tight">Avocado
                                        Quinoa Power Salad</h3>
                                    <div class="flex items-center gap-2 text-xs text-nutri-200 pt-1 flex-wrap">
                                        <span
                                            class="px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md font-black text-white border border-white/10 shadow-sm">🔥
                                            380 kcal</span>
                                        <span
                                            class="px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md font-black text-white border border-white/10 shadow-sm">💪
                                            14.5g P</span>
                                        <span
                                            class="px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md font-black text-white border border-white/10 shadow-sm">🥑
                                            Healthy Fats</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Dynamic Badge 1: Live GPS Dispatch (Top Left) -->
                            <div
                                class="absolute -top-6 -left-6 p-4 rounded-2xl bg-white/95 text-gray-900 shadow-2xl border border-emerald-100 backdrop-blur-md hidden sm:flex items-center gap-3 animate-float-badge z-20 pointer-events-none">
                                <div
                                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-inner shrink-0">
                                    🛵
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-900 leading-tight">Live GPS Order Dispatch
                                    </p>
                                    <p class="text-[10px] text-emerald-600 font-extrabold flex items-center gap-1.5 mt-0.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Real-Time
                                        Lipa
                                        Route
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Continuous Dynamic Feature Ticker Ribbon (Socia-inspired) -->
        <div
            class="marquee-container bg-gradient-to-r from-emerald-600 via-nutri-700 to-emerald-600 text-white py-3 border-y border-emerald-500 shadow-md">
            <div class="marquee-track text-xs font-black uppercase tracking-widest whitespace-nowrap">
                <span class="flex items-center gap-2"><span>🥗</span> 100% Organic & Macro Counted</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🛵</span> ₱40 Fair Lipa Base Delivery</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🔥</span> Live Calorie & Diet Filter</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🥑</span> Keto, Vegan & Gym Bowls</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>👑</span> VIP Exclusive Creator Vlogs</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>📍</span> 72 Barangays Covered Across Lipa</span>
                <span class="text-limey-300">•</span>
            </div>
            <div class="marquee-track text-xs font-black uppercase tracking-widest whitespace-nowrap" aria-hidden="true">
                <span class="flex items-center gap-2"><span>🥗</span> 100% Organic & Macro Counted</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🛵</span> ₱40 Fair Lipa Base Delivery</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🔥</span> Live Calorie & Diet Filter</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>🥑</span> Keto, Vegan & Gym Bowls</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>👑</span> VIP Exclusive Creator Vlogs</span>
                <span class="text-limey-300">•</span>
                <span class="flex items-center gap-2"><span>📍</span> 72 Barangays Covered Across Lipa</span>
                <span class="text-limey-300">•</span>
            </div>
        </div>

        <!-- Interactive Calorie & Macro Target Estimator Widget -->
        <section class="py-12 bg-white border-b border-gray-100 reveal-on-scroll" x-data="{
            goal: 'weight_loss',
            activity: 'moderate',
            calories: 1850,
            protein: 140,
            recalculate() {
                if (this.goal === 'weight_loss') {
                    this.calories = 1650;
                    this.protein = 135;
                } else if (this.goal === 'muscle_gain') {
                    this.calories = 2400;
                    this.protein = 175;
                } else {
                    this.calories = 2000;
                    this.protein = 145;
                }
            }
        }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-nutri-50 via-white to-lime-50 border border-nutri-200 shadow-card">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5 space-y-3">
                            <span
                                class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-nutri-100 text-nutri-800">
                                <i class="fa-solid fa-calculator mr-1"></i> Interactive NutriEstimator
                            </span>
                            <h3 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                                Find meals that fit your exact daily health goals
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                                Select your wellness target to immediately filter food that matches your calorie budget in
                                Lipa
                                City.
                            </p>

                            <!-- Goal Selector -->
                            <div class="grid grid-cols-3 gap-2 pt-2">
                                <button @click="goal = 'weight_loss'; recalculate()"
                                    :class="goal === 'weight_loss' ? 'bg-nutri-800 text-white shadow' :
                                        'bg-white text-gray-700 border border-gray-200'"
                                    class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                                    🥗 Weight Loss
                                </button>
                                <button @click="goal = 'muscle_gain'; recalculate()"
                                    :class="goal === 'muscle_gain' ? 'bg-nutri-800 text-white shadow' :
                                        'bg-white text-gray-700 border border-gray-200'"
                                    class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                                    🍗 Build Muscle
                                </button>
                                <button @click="goal = 'maintenance'; recalculate()"
                                    :class="goal === 'maintenance' ? 'bg-nutri-800 text-white shadow' :
                                        'bg-white text-gray-700 border border-gray-200'"
                                    class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                                    🥑 Stay Fit
                                </button>
                            </div>
                        </div>

                        <!-- Calculator Result Card -->
                        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-nutri-200 shadow-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Suggested
                                            Per
                                            Meal Target</span>
                                        <div class="text-3xl font-black text-nutri-900 font-heading">
                                            <span x-text="Math.round(calories / 3)"></span> <span
                                                class="text-sm font-semibold text-nutri-600">kcal / meal</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs font-bold text-gray-700">
                                        <div><span class="text-rose-600">💪</span> <span
                                                x-text="Math.round(protein / 3)"></span>g Protein</div>
                                        <div><span class="text-amber-500">🥑</span> Low-Carb Option</div>
                                        <div><span class="text-emerald-600">🌱</span> High-Fiber</div>
                                    </div>
                                </div>

                                <div class="text-center sm:text-right">
                                    <a :href="'{{ route('users.explore') }}?calorie_range=' + (goal === 'weight_loss' ?
                                        'under300' :
                                        (goal === 'muscle_gain' ? 'over500' : '300to500'))"
                                        class="shimmer-btn inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-extrabold text-sm shadow-md transition font-heading">
                                        View Recommended Lipa Meals <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Healthy Food Categories -->
        <section class="py-16 bg-gray-50 reveal-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-nutri-600">Curated
                            Categories</span>
                        <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-1">What are you craving
                            today?</h2>
                    </div>
                    <a href="{{ route('users.explore') }}"
                        class="mt-4 md:mt-0 text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1">
                        Explore all items <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach ($categories as $cat)
                        <a href="{{ route('users.explore', ['category' => $cat->slug]) }}"
                            class="p-5 rounded-2xl bg-white border border-gray-100 hover:border-limey-400 hover:shadow-lg transition-all duration-300 text-center group hover:-translate-y-1">
                            <div class="text-4xl mb-3 group-hover:scale-125 transition-transform duration-300">
                                {{ $cat->icon }}
                            </div>
                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-nutri-600 transition font-heading">
                                {{ $cat->name }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Featured Healthy Choices (With Badges & Calories) -->
        <section class="py-16 bg-white reveal-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <span
                            class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                            <i class="fa-solid fa-award mr-1"></i> Lipa Verified
                        </span>
                        <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-2">Top Healthy Choice
                            Meals
                        </h2>
                    </div>
                    <a href="{{ route('users.explore') }}"
                        class="mt-4 md:mt-0 text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1">
                        Browse Full Menu <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Layer 1: Single Row Muna (First 3 items) --}}
                    @foreach ($healthyChoices->take(3) as $item)
                        <div
                            class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1.5">
                            <div>
                                <!-- Image Container -->
                                <div class="relative h-48 overflow-hidden bg-gray-100">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                        <span
                                            class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold shadow">
                                            <i class="fa-solid fa-shield-heart mr-1"></i> Healthy Choice
                                        </span>
                                        @if ($item->is_supplement)
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-purple-600 text-white text-[10px] font-extrabold shadow">
                                                💊 Supplement
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        class="absolute bottom-3 right-3 px-2.5 py-1 rounded-xl bg-gray-900/90 text-white text-xs font-black backdrop-blur-md shadow-md flex items-center gap-1">
                                        🔥 {{ $item->calories }} kcal
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div
                                        class="text-[11px] font-extrabold text-nutri-600 uppercase tracking-wider mb-1 leading-tight">
                                        {{ $item->store->store_name }}
                                        <br>
                                        <span
                                            class="text-[10px] text-gray-400 font-normal normal-case block truncate mt-0.5"><i
                                                class="fa-solid fa-location-dot text-[9px] text-gray-400 mr-0.5"></i>{{ $item->store->address_line ?? $item->store->barangay }}</span>
                                    </div>
                                    <h3
                                        class="text-lg font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                        {{ $item->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>

                                    <!-- Macros Breakdown -->
                                    <div
                                        class="mt-4 pt-3 border-t border-gray-100 grid grid-cols-3 gap-2 text-center text-xs">
                                        <div class="p-1.5 rounded-lg bg-rose-50 text-rose-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Protein</div>
                                            {{ $item->protein_g }}g
                                        </div>
                                        <div class="p-1.5 rounded-lg bg-amber-50 text-amber-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Carbs</div>
                                            {{ $item->carbs_g }}g
                                        </div>
                                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Fats</div>
                                            {{ $item->fat_g }}g
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price & Add to Cart -->
                            <div class="p-6 pt-0 flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xl font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                                    @if ($item->original_price)
                                        <span
                                            class="text-xs text-gray-400 line-through ml-1">₱{{ number_format($item->original_price, 2) }}</span>
                                    @endif
                                </div>
                                @if ($item->store && $item->store->is_open)
                                    <form action="{{ route('users.cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <button type="submit"
                                            class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm hover:shadow transition flex items-center gap-1.5 active:scale-95">
                                            <i class="fa-solid fa-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled
                                        class="px-3.5 py-2.5 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed flex items-center gap-1.5"
                                        title="Store is currently closed">
                                        <i class="fa-solid fa-lock text-[10px]"></i> Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- [SECOND LAYER COMMENTED OUT - SINGLE ROW MUNA]
                    @foreach ($healthyChoices->skip(3) as $item)
                        <div
                            class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1.5">
                            <div>
                                <div class="relative h-48 overflow-hidden bg-gray-100">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                        <span
                                            class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold shadow">
                                            <i class="fa-solid fa-shield-heart mr-1"></i> Healthy Choice
                                        </span>
                                        @if ($item->is_supplement)
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-purple-600 text-white text-[10px] font-extrabold shadow">
                                                💊 Supplement
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        class="absolute bottom-3 right-3 px-2.5 py-1 rounded-xl bg-gray-900/90 text-white text-xs font-black backdrop-blur-md shadow-md flex items-center gap-1">
                                        🔥 {{ $item->calories }} kcal
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div
                                        class="text-[11px] font-extrabold text-nutri-600 uppercase tracking-wider mb-1 leading-tight">
                                        {{ $item->store->store_name }}
                                        <br>
                                        <span
                                            class="text-[10px] text-gray-400 font-normal normal-case block truncate mt-0.5"><i
                                                class="fa-solid fa-location-dot text-[9px] text-gray-400 mr-0.5"></i>{{ $item->store->address_line ?? $item->store->barangay }}</span>
                                    </div>
                                    <h3
                                        class="text-lg font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                        {{ $item->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>

                                    <div
                                        class="mt-4 pt-3 border-t border-gray-100 grid grid-cols-3 gap-2 text-center text-xs">
                                        <div class="p-1.5 rounded-lg bg-rose-50 text-rose-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Protein</div>
                                            {{ $item->protein_g }}g
                                        </div>
                                        <div class="p-1.5 rounded-lg bg-amber-50 text-amber-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Carbs</div>
                                            {{ $item->carbs_g }}g
                                        </div>
                                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold">
                                            <div class="text-[10px] uppercase text-gray-500 font-semibold">Fats</div>
                                            {{ $item->fat_g }}g
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-0 flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xl font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                                    @if ($item->original_price)
                                        <span
                                            class="text-xs text-gray-400 line-through ml-1">₱{{ number_format($item->original_price, 2) }}</span>
                                    @endif
                                </div>
                                @if ($item->store && $item->store->is_open)
                                    <form action="{{ route('users.cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <button type="submit"
                                            class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm hover:shadow transition flex items-center gap-1.5 active:scale-95">
                                            <i class="fa-solid fa-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled
                                        class="px-3.5 py-2.5 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed flex items-center gap-1.5"
                                        title="Store is currently closed">
                                        <i class="fa-solid fa-lock text-[10px]"></i> Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    --}}
                </div>
            </div>
        </section>

        <!-- Content Creator Cooking Vlogs Reel -->
        <section class="py-16 bg-gradient-to-b from-gray-50 to-white reveal-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <span
                            class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800">
                            <i class="fa-solid fa-video mr-1"></i> Partner Health Vloggers
                        </span>
                        <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-2">Cook Healthy with
                            Lipa
                            Food Creators</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Watch 10-minute cooking videos and order exact
                            ingredients directly from partner stores!</p>
                    </div>
                    <a href="{{ route('creators.index') }}"
                        class="mt-4 md:mt-0 text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        View All Cooking Vlogs <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredRecipes as $vlog)
                        <div
                            class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card flex flex-col justify-between group">
                            <div>
                                <!-- Thumbnail -->
                                <div class="relative h-52 overflow-hidden bg-gray-900">
                                    <img src="{{ $vlog->thumbnail_url }}" alt="{{ $vlog->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <a href="{{ route('creators.show', $vlog->slug) }}"
                                            class="w-14 h-14 rounded-full bg-rose-600 text-white flex items-center justify-center text-xl shadow-lg hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-play ml-1"></i>
                                        </a>
                                    </div>
                                    <div
                                        class="absolute bottom-3 left-3 px-2.5 py-1 rounded-xl bg-black/70 text-white text-[10px] font-bold backdrop-blur-md">
                                        ⏱️ {{ $vlog->prep_time_mins }} mins prep • 🔥 {{ $vlog->calories }} kcal
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-6">
                                    <div class="flex items-center gap-2 mb-2">
                                        <img src="{{ $vlog->creator->avatar_url }}" alt="{{ $vlog->creator->name }}"
                                            class="w-6 h-6 rounded-full object-cover">
                                        <span
                                            class="text-xs font-bold text-gray-700">{{ $vlog->creator->channel_name }}</span>
                                    </div>
                                    <h3
                                        class="text-base font-black text-gray-900 font-heading group-hover:text-rose-600 transition">
                                        <a href="{{ route('creators.show', $vlog->slug) }}">{{ $vlog->title }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $vlog->description }}</p>
                                </div>
                            </div>

                            <!-- 1-Click Order Ingredients Button -->
                            <div class="p-6 pt-0">
                                <form action="{{ route('creators.order.ingredients', $vlog->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-cart-plus"></i> Order Ingredients from
                                        {{ $vlog->store->store_name ?? 'Lipa Store' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Verified Healthy Stores in Lipa -->
        <section class="py-16 bg-white reveal-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-nutri-50 text-nutri-800 border border-nutri-200 inline-flex items-center gap-1.5 mb-2 shadow-xs">
                            <i class="fa-solid fa-shield-heart text-nutri-600"></i> Local Partnerships
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 font-heading tracking-tight">
                            Verified Healthy Stores in Lipa City
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Every partner store is strictly vetted for nutritional transparency and quality clean
                            ingredients.
                        </p>
                    </div>
                    <button @click="showAllStoresModal = true"
                        class="mt-4 md:mt-0 px-6 py-3 rounded-2xl bg-gradient-to-r from-nutri-900 to-emerald-900 hover:from-nutri-800 hover:to-emerald-800 text-white text-xs font-black shadow-lg transition flex items-center gap-2 hover:scale-[1.03] active:scale-95 font-heading">
                        <i class="fa-solid fa-store text-limey-400"></i> View All Stores
                        ({{ count($allStores ?? $featuredStores) }}) <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredStores as $store)
                        <a href="{{ route('users.stores.show', $store->slug) }}"
                            class="p-6 rounded-3xl bg-white border border-gray-200/80 hover:border-emerald-400 shadow-card hover:shadow-2xl transition-all duration-300 group text-center flex flex-col items-center hover:-translate-y-1.5">
                            <div
                                class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-50 p-1 border border-gray-100 shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                                <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}"
                                    class="w-full h-full object-cover rounded-xl">
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-nutri-50 text-nutri-800 border border-nutri-200">
                                    {{ $store->health_category }}
                                </span>
                                <span
                                    class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $store->is_open ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $store->is_open ? '● Open' : 'Closed' }}
                                </span>
                            </div>
                            <h3
                                class="text-base font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                {{ $store->store_name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                <i
                                    class="fa-solid fa-location-dot text-gray-400 text-[10px] mr-1"></i>{{ $store->address_line ?? 'Brgy. ' . $store->barangay . ', Lipa' }}
                            </p>
                            <div
                                class="flex items-center gap-1.5 text-xs font-bold text-amber-500 mt-3 pt-2 border-t border-gray-100 w-full justify-center">
                                <i class="fa-solid fa-star text-[11px]"></i> {{ number_format($store->rating, 2) }}
                                <span class="text-gray-400 font-medium text-[11px]">({{ $store->total_reviews }})</span>
                                <span class="text-gray-300">•</span>
                                <span
                                    class="text-emerald-600 font-extrabold text-[11px]">{{ $store->products_count ?? 0 }}
                                    items</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- View More Button at Bottom of Grid -->
                <div class="text-center mt-12">
                    <button @click="showAllStoresModal = true"
                        class="shimmer-btn inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl bg-nutri-900 hover:bg-nutri-800 text-white font-black text-sm shadow-xl transition-all duration-300 hover:scale-105 font-heading">
                        <i class="fa-solid fa-store text-limey-400"></i> Browse All
                        {{ count($allStores ?? $featuredStores) }} Partner Stores in Lipa <i
                            class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Customer Reviews & Testimonials Section (Swiper Showcase & Inline Review Form) -->
        <section class="py-20 bg-gradient-to-b from-gray-50 via-nutri-50/20 to-white relative overflow-hidden reveal-on-scroll border-t border-gray-100">
            <!-- Decorative Background Elements -->
            <div class="absolute top-1/3 left-0 -translate-y-1/2 w-80 h-80 bg-limey-400/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute top-2/3 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-[110px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Section Header with Swiper Navigation Controls -->
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-amber-100 text-amber-900 border border-amber-200/80 inline-flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-star text-amber-500"></i> Customer Voices & Ratings
                            </span>
                            <span class="text-xs text-gray-400 font-bold hidden sm:inline">•</span>
                            <span class="text-xs text-emerald-700 font-bold hidden sm:inline flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Verified Lipa Foodies
                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 font-heading tracking-tight">
                            Loved by Health-Conscious Foodies in Lipa City
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2 leading-relaxed">
                            Swipe through authentic reviews from locals ordering daily healthy meal preps, keto specials, and clean nutrition across Lipa.
                        </p>
                    </div>

                    <!-- Right Side: Rating Summary Box & Swiper Navigation Arrows -->
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl border border-gray-200/80 shadow-card">
                            <div class="text-3xl font-black text-gray-900 font-heading leading-none">
                                {{ number_format($avgCustomerRating, 1) }}
                            </div>
                            <div>
                                <div class="flex text-amber-400 text-xs">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= round($avgCustomerRating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-[11px] text-gray-500 font-bold mt-0.5">
                                    Based on {{ $totalActiveReviews }} {{ Str::plural('review', $totalActiveReviews) }}
                                </p>
                            </div>
                        </div>

                        <!-- Swiper Navigation Arrows -->
                        <div class="flex items-center gap-2">
                            <button type="button" aria-label="Previous Reviews"
                                class="reviews-swiper-prev w-11 h-11 rounded-2xl bg-white hover:bg-nutri-900 hover:text-white text-gray-700 border border-gray-200 shadow-sm flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" aria-label="Next Reviews"
                                class="reviews-swiper-next w-11 h-11 rounded-2xl bg-white hover:bg-nutri-900 hover:text-white text-gray-700 border border-gray-200 shadow-sm flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Swiper Reviews Slider -->
                @if ($customerReviews->count() > 0)
                    <div class="relative">
                        <div class="swiper customer-reviews-swiper !pb-14 !pt-2">
                            <div class="swiper-wrapper">
                                @foreach ($customerReviews as $review)
                                    <div class="swiper-slide">
                                        <div class="w-full bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 hover:border-emerald-400 shadow-card hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 relative overflow-hidden">
                                            <div>
                                                <!-- Top Row: Star Ratings & Clean Rating Badge -->
                                                <div class="flex items-center justify-between gap-3 mb-4 relative z-10">
                                                    <div class="flex items-center gap-1 text-amber-400 text-sm">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-900 border border-amber-200/80 shadow-2xs shrink-0">
                                                        <i class="fa-solid fa-star text-amber-500 text-[11px]"></i> {{ $review->rating }}.0
                                                    </span>
                                                </div>

                                                <!-- Review Message -->
                                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed font-medium mb-6 relative z-10">
                                                    "{{ $review->message }}"
                                                </p>
                                            </div>

                                            <!-- Reviewer Profile Footer -->
                                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <img src="{{ $review->avatar }}" alt="{{ $review->name }}"
                                                        class="w-11 h-11 rounded-2xl object-cover bg-emerald-100 border border-gray-200 shadow-2xs shrink-0">
                                                    <div class="min-w-0">
                                                        <h4 class="text-xs sm:text-sm font-black text-gray-900 truncate font-heading group-hover:text-emerald-700 transition">
                                                            {{ $review->name }}
                                                        </h4>
                                                        <p class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Verified Lipa Customer
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] text-gray-400 font-semibold shrink-0">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Swiper Pagination Dots -->
                            <div class="swiper-pagination swiper-pagination-reviews !bottom-2"></div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-10 text-center border border-gray-200 max-w-lg mx-auto space-y-3 mb-10">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl mx-auto">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h4 class="text-base font-black text-gray-800 font-heading">Be the First to Review!</h4>
                        <p class="text-xs text-gray-500">
                            Have you ordered healthy food with NutriGo? Share your review below to be featured on our homepage!
                        </p>
                    </div>
                @endif

                <!-- Embedded Inline Customer Review Submission Form (NO MODAL) -->
                <div class="mt-8 bg-gradient-to-br from-nutri-950 via-nutri-900 to-emerald-950 text-white rounded-[2.5rem] p-6 sm:p-10 lg:p-12 border border-white/10 shadow-2xl relative overflow-hidden">
                    <!-- Ambient decorative light glow -->
                    <div class="absolute -top-24 -right-24 w-80 h-80 bg-limey-400/15 rounded-full blur-[90px] pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-500/15 rounded-full blur-[90px] pointer-events-none"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                        <!-- Left Column: Invite & Instructions -->
                        <div class="lg:col-span-5 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-limey-300 border border-white/15 text-[10px] font-extrabold uppercase tracking-widest backdrop-blur-md">
                                <i class="fa-solid fa-pen-nib text-limey-400"></i> Share Your Voice
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-black font-heading leading-tight text-white tracking-tight">
                                Leave a Customer Review
                            </h3>
                            <p class="text-xs sm:text-sm text-nutri-200 leading-relaxed">
                                Tell the Lipa City health community about your meal freshness, store preparation, or rider delivery experience.
                            </p>

                            <div class="space-y-2.5 pt-2 text-xs text-nutri-100 font-medium">
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10">
                                    <span class="w-8 h-8 rounded-xl bg-limey-400/20 text-limey-400 flex items-center justify-center font-bold text-sm shrink-0">🥗</span>
                                    <span>Rate taste, freshness, and calorie accuracy</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10">
                                    <span class="w-8 h-8 rounded-xl bg-limey-400/20 text-limey-400 flex items-center justify-center font-bold text-sm shrink-0">🚴</span>
                                    <span>Rate rider delivery speed and food handling</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10">
                                    <span class="w-8 h-8 rounded-xl bg-limey-400/20 text-limey-400 flex items-center justify-center font-bold text-sm shrink-0">⭐</span>
                                    <span>Active reviews are featured live in the carousel</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Inline Form Card -->
                        <div class="lg:col-span-7 bg-white text-gray-900 rounded-3xl p-6 sm:p-8 shadow-2xl border border-gray-100"
                            x-data="{ inlineRating: 5 }">
                            <h4 class="text-lg font-black font-heading text-gray-900 mb-1">Submit Your Review</h4>
                            <p class="text-xs text-gray-500 mb-4">Directly submit your feedback below without any popup.</p>

                            <form action="{{ route('customer.reviews.submit') }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-black text-gray-800 mb-1 font-heading uppercase tracking-wider">
                                        Your Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" name="name" required
                                            value="{{ auth()->user()->name ?? '' }}"
                                            placeholder="e.g. Maria Clara"
                                            class="w-full pl-9 pr-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500 focus:ring-4 focus:ring-nutri-500/10 shadow-inner transition">
                                    </div>
                                </div>

                                <!-- Email & Contact -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div>
                                        <label class="block text-xs font-black text-gray-800 mb-1 font-heading uppercase tracking-wider">
                                            Email Address <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="email" name="email" required
                                                value="{{ auth()->user()->email ?? '' }}"
                                                placeholder="yourname@gmail.com"
                                                class="w-full pl-9 pr-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500 focus:ring-4 focus:ring-nutri-500/10 shadow-inner transition">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-gray-800 mb-1 font-heading uppercase tracking-wider">
                                            Contact Number
                                        </label>
                                        <div class="relative">
                                            <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="text" name="contact"
                                                value="{{ auth()->user()->phone ?? '' }}"
                                                placeholder="0917XXXXXXX"
                                                class="w-full pl-9 pr-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500 focus:ring-4 focus:ring-nutri-500/10 shadow-inner transition">
                                        </div>
                                    </div>
                                </div>

                                <!-- Interactive 5-Star Rating Selector -->
                                <div>
                                    <label class="block text-xs font-black text-gray-800 mb-1.5 font-heading uppercase tracking-wider">
                                        Rate Your Satisfaction <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="hidden" name="rating" :value="inlineRating">

                                    <div class="p-3 bg-amber-50/60 rounded-2xl border border-amber-200/80 flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center gap-1.5">
                                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                                <button type="button" @click="inlineRating = star"
                                                    class="text-3xl transition-transform hover:scale-125 focus:outline-none cursor-pointer"
                                                    :class="star <= inlineRating ? 'text-amber-400' : 'text-gray-300'">
                                                    <i class="fa-solid fa-star"></i>
                                                </button>
                                            </template>
                                        </div>

                                        <span class="px-3 py-1 rounded-xl text-xs font-black"
                                            :class="{
                                                'bg-rose-100 text-rose-800': inlineRating === 1,
                                                'bg-orange-100 text-orange-800': inlineRating === 2,
                                                'bg-amber-100 text-amber-800': inlineRating === 3,
                                                'bg-lime-100 text-lime-800': inlineRating === 4,
                                                'bg-emerald-100 text-emerald-800': inlineRating === 5
                                            }">
                                            <span x-show="inlineRating === 1">⭐ 1 Star - Poor</span>
                                            <span x-show="inlineRating === 2">⭐⭐ 2 Stars - Fair</span>
                                            <span x-show="inlineRating === 3">⭐⭐⭐ 3 Stars - Good</span>
                                            <span x-show="inlineRating === 4">⭐⭐⭐⭐ 4 Stars - Very Good</span>
                                            <span x-show="inlineRating === 5">⭐⭐⭐⭐⭐ 5 Stars - Outstanding!</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Review Message -->
                                <div>
                                    <label class="block text-xs font-black text-gray-800 mb-1 font-heading uppercase tracking-wider">
                                        Your Review & Experience <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea name="message" rows="3" required minlength="5" maxlength="1500"
                                        placeholder="Share how NutriGo helped your healthy eating habits, food quality, delivery speed, or favorite meals..."
                                        class="w-full p-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500 focus:ring-4 focus:ring-nutri-500/10 shadow-inner transition"></textarea>
                                </div>

                                <!-- Notice & Submit -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                                    <div class="text-[11px] text-gray-500 flex items-center gap-1.5">
                                        <i class="fa-solid fa-shield-check text-emerald-600 text-xs shrink-0"></i>
                                        <span>Reviews are verified by management before appearing in the carousel</span>
                                    </div>

                                    <button type="submit"
                                        class="shimmer-btn px-7 py-3.5 rounded-2xl bg-nutri-900 hover:bg-emerald-600 text-white font-black text-xs transition-all duration-300 shadow-lg flex items-center justify-center gap-2 hover:scale-105 font-heading shrink-0 cursor-pointer">
                                        <i class="fa-solid fa-paper-plane text-limey-400"></i> Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIP Subscription Banner -->
        <section
            class="py-16 bg-gradient-to-r from-nutri-900 via-nutri-800 to-nutri-950 text-white relative overflow-hidden reveal-on-scroll">
            <!-- Ambient glowing orbs for VIP banner -->
            <div
                class="absolute -top-16 -right-16 w-80 h-80 bg-amber-400/20 rounded-full blur-[80px] pointer-events-none animate-pulse-glow">
            </div>
            <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-limey-400/20 rounded-full blur-[80px] pointer-events-none animate-pulse-glow"
                style="animation-delay: 2s;"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-4">
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-amber-400 text-nutri-950 shadow-md inline-flex items-center gap-1.5">
                            👑 NutriGo VIP Club
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-black font-heading tracking-tight">
                            Upgrade to VIP for Exclusive Meal Preps & Free Deliveries
                        </h2>
                        <p class="text-xs sm:text-sm text-nutri-200 max-w-2xl leading-relaxed">
                            Unlock exclusive vlogger masterclasses, personal nutritionist macro plans, 15% discount on
                            partner
                            supplements, and free delivery perks across Lipa City.
                        </p>
                    </div>
                    <div class="lg:col-span-4 text-center lg:text-right">
                        <a href="{{ route('users.subscriptions.index') }}"
                            class="shimmer-btn inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-amber-400 hover:bg-amber-300 text-nutri-950 font-black text-sm shadow-xl transition font-heading transform hover:scale-105">
                            Explore VIP Plans <i class="fa-solid fa-crown"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- PREMIUM ALL STORES MODAL DIALOG (Root level z-[9999]) -->
        <div x-show="showAllStoresModal" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] overflow-y-auto bg-nutri-950/80 backdrop-blur-xl flex items-center justify-center p-3 sm:p-6 md:p-8"
            @keydown.escape.window="showAllStoresModal = false">

            <div @click.away="showAllStoresModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-6"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-6"
                class="relative w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl border border-white/40 overflow-hidden flex flex-col max-h-[90vh]">

                <!-- Modal Header (Studio Dark Luxury) -->
                <div
                    class="px-6 py-6 sm:px-8 bg-gradient-to-r from-nutri-950 via-nutri-900 to-emerald-950 text-white flex items-center justify-between shrink-0 relative overflow-hidden border-b border-white/10">
                    <!-- Ambient glow in header -->
                    <div
                        class="absolute -top-10 -right-10 w-48 h-48 bg-limey-400/20 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="relative z-10 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-limey-400 to-emerald-400 text-nutri-950 flex items-center justify-center text-2xl shadow-lg shrink-0">
                            🏪
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-white/15 text-limey-300 text-[10px] font-black uppercase tracking-wider backdrop-blur-xs">
                                    Verified Healthy Partners
                                </span>
                                <span class="text-xs text-nutri-200 font-bold">
                                    {{ count($allStores ?? $featuredStores) }} Stores Available
                                </span>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black font-heading leading-tight mt-1 text-white">
                                All Healthy Kitchens in Lipa City
                            </h3>
                        </div>
                    </div>

                    <button @click="showAllStoresModal = false"
                        class="relative z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition hover:rotate-90 hover:scale-110 active:scale-95 shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Search & Quick Category Filters -->
                <div class="p-4 sm:p-6 bg-gray-50/80 border-b border-gray-100 space-y-3.5 shrink-0">
                    <!-- Search Bar -->
                    <div class="relative">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-nutri-600 text-sm"></i>
                        <input type="text" x-model="storeSearch"
                            placeholder="Search by store name, health diet (e.g. Keto, Organic, Vegan), or Lipa barangay..."
                            class="w-full pl-11 pr-10 py-3.5 rounded-2xl bg-white border border-gray-200 text-xs sm:text-sm font-medium focus:outline-none focus:border-nutri-500 focus:ring-4 focus:ring-nutri-500/10 shadow-inner transition">
                        <button x-show="storeSearch" @click="storeSearch = ''"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                            <i class="fa-solid fa-circle-xmark text-sm"></i>
                        </button>
                    </div>

                    <!-- Category Quick Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs font-bold no-scrollbar">
                        <button @click="selectedCategory = 'all'"
                            :class="selectedCategory === 'all' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            ✨ All Stores
                        </button>
                        <button @click="selectedCategory = 'organic'"
                            :class="selectedCategory === 'organic' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            🥗 Organic & Salads
                        </button>
                        <button @click="selectedCategory = 'keto'"
                            :class="selectedCategory === 'keto' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            🥑 Keto & Low-Carb
                        </button>
                        <button @click="selectedCategory = 'protein'"
                            :class="selectedCategory === 'protein' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            💪 High-Protein & Gym
                        </button>
                        <button @click="selectedCategory = 'plant'"
                            :class="selectedCategory === 'plant' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            🌱 Plant-Based & Vegan
                        </button>
                        <button @click="selectedCategory = 'supplement'"
                            :class="selectedCategory === 'supplement' ? 'bg-nutri-900 text-white shadow-sm' :
                                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                            class="px-3.5 py-1.5 rounded-xl whitespace-nowrap transition">
                            💊 Supplements
                        </button>
                    </div>
                </div>

                <!-- Modal Stores Grid Content (Scrollable) -->
                <div class="p-4 sm:p-6 md:p-8 overflow-y-auto flex-1 bg-gray-50/40">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($allStores ?? $featuredStores as $store)
                            <div x-show="(!storeSearch || '{{ strtolower($store->store_name . ' ' . $store->health_category . ' ' . ($store->barangay ?? '') . ' ' . ($store->address_line ?? '')) }}'.includes(storeSearch.toLowerCase())) && (selectedCategory === 'all' || '{{ strtolower($store->health_category) }}'.includes(selectedCategory))"
                                class="p-5 rounded-3xl bg-white border border-gray-200/80 hover:border-emerald-400 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                                <div>
                                    <!-- Top Info Header -->
                                    <div class="flex items-start gap-3.5 mb-3.5">
                                        <div
                                            class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 p-1 border border-gray-100 shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                                            <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}"
                                                class="w-full h-full object-cover rounded-xl">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    {{ $store->health_category }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $store->is_open ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                    {{ $store->is_open ? '● Open' : 'Closed' }}
                                                </span>
                                            </div>
                                            <h4
                                                class="text-sm sm:text-base font-black text-gray-900 truncate font-heading group-hover:text-nutri-600 transition">
                                                {{ $store->store_name }}
                                            </h4>
                                            <div class="flex items-center gap-1.5 text-xs font-bold text-amber-500 mt-0.5">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                {{ number_format($store->rating, 2) }}
                                                <span
                                                    class="text-gray-400 font-medium text-[10px]">({{ $store->total_reviews ?? 0 }}
                                                    reviews)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Address -->
                                    <p
                                        class="text-xs text-gray-500 flex items-start gap-1.5 mt-2 line-clamp-2 bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                        <i class="fa-solid fa-location-dot text-emerald-600 text-xs mt-0.5 shrink-0"></i>
                                        <span
                                            class="leading-tight">{{ $store->address_line ?? 'Brgy. ' . $store->barangay . ', Lipa City' }}</span>
                                    </p>
                                </div>

                                <!-- Footer CTA & Menu Count -->
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-extrabold text-gray-500 flex items-center gap-1">
                                        <span>🥗</span> {{ $store->products_count ?? 0 }} items
                                    </span>
                                    <a href="{{ route('users.stores.show', $store->slug) }}"
                                        class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-emerald-600 text-white font-extrabold text-xs transition-all duration-300 flex items-center gap-1.5 shadow-md group-hover:shadow-emerald-500/20">
                                        Explore Menu <i
                                            class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 shrink-0">
                    <span class="font-bold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </span>
                    <button @click="showAllStoresModal = false"
                        class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Animations JavaScript (Socia.ph style Canvas Splash, 3D Tilt, Counters, Swiper) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Interactive Particle Canvas with Liquid Splash & Cursor Interaction
            const canvas = document.getElementById('hero-splash-canvas');
            const heroSection = document.getElementById('hero-interactive');
            const spotlight = document.getElementById('hero-spotlight');

            if (canvas && heroSection) {
                const ctx = canvas.getContext('2d');
                let width = canvas.width = heroSection.offsetWidth;
                let height = canvas.height = heroSection.offsetHeight;

                window.addEventListener('resize', () => {
                    width = canvas.width = heroSection.offsetWidth;
                    height = canvas.height = heroSection.offsetHeight;
                });

                // Particles collection (Lightweight for 60fps performance)
                const particles = [];
                const particleCount = 22;
                const colors = ['rgba(163, 230, 53, ', 'rgba(34, 197, 94, ', 'rgba(45, 212, 191, '];

                for (let i = 0; i < particleCount; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        radius: Math.random() * 2 + 1,
                        colorPrefix: colors[Math.floor(Math.random() * colors.length)],
                        opacity: Math.random() * 0.4 + 0.2,
                        vx: (Math.random() - 0.5) * 0.4,
                        vy: (Math.random() - 0.5) * 0.4 - 0.1,
                        pulse: Math.random() * Math.PI,
                    });
                }

                // Mouse splash tracking
                let mouse = {
                    x: -1000,
                    y: -1000,
                    isHovering: false
                };
                let ripples = [];

                heroSection.addEventListener('mousemove', (e) => {
                    const rect = heroSection.getBoundingClientRect();
                    mouse.x = e.clientX - rect.left;
                    mouse.y = e.clientY - rect.top;
                    mouse.isHovering = true;

                    if (spotlight) {
                        spotlight.style.left = `${mouse.x}px`;
                        spotlight.style.top = `${mouse.y}px`;
                    }

                    if (Math.random() > 0.8) {
                        ripples.push({
                            x: mouse.x,
                            y: mouse.y,
                            radius: 2,
                            maxRadius: Math.random() * 25 + 15,
                            alpha: 0.4
                        });
                    }
                });

                heroSection.addEventListener('mouseleave', () => {
                    mouse.isHovering = false;
                });

                // 60FPS Optimized Canvas Animation Loop (No heavy shadowBlur filters)
                function animate() {
                    ctx.clearRect(0, 0, width, height);

                    // Draw ripples
                    for (let i = ripples.length - 1; i >= 0; i--) {
                        const r = ripples[i];
                        r.radius += 1.5;
                        r.alpha -= 0.02;

                        ctx.beginPath();
                        ctx.arc(r.x, r.y, r.radius, 0, Math.PI * 2);
                        ctx.strokeStyle = `rgba(163, 230, 53, ${r.alpha})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();

                        if (r.alpha <= 0 || r.radius >= r.maxRadius) {
                            ripples.splice(i, 1);
                        }
                    }

                    // Draw particles
                    for (let i = 0; i < particles.length; i++) {
                        const p = particles[i];
                        p.pulse += 0.02;
                        p.x += p.vx;
                        p.y += p.vy;

                        if (p.x < 0) p.x = width;
                        if (p.x > width) p.x = 0;
                        if (p.y < 0) p.y = height;
                        if (p.y > height) p.y = 0;

                        const currentAlpha = p.opacity + Math.sin(p.pulse) * 0.1;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                        ctx.fillStyle = `${p.colorPrefix}${Math.max(0.1, currentAlpha)})`;
                        ctx.fill();

                        // Faint connection lines
                        for (let j = i + 1; j < particles.length; j++) {
                            const p2 = particles[j];
                            const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                            if (dist < 70) {
                                ctx.beginPath();
                                ctx.moveTo(p.x, p.y);
                                ctx.lineTo(p2.x, p2.y);
                                ctx.strokeStyle = `rgba(163, 230, 53, ${0.08 * (1 - dist / 70)})`;
                                ctx.lineWidth = 0.5;
                                ctx.stroke();
                            }
                        }
                    }

                    requestAnimationFrame(animate);
                }
                animate();
            }

            // 2. High-Performance On-Demand 3D Tilt (Zero idle CPU usage)
            const tiltWrapper = document.getElementById('hero-tilt-wrapper');
            const heroCard = document.getElementById('hero-3d-card');

            if (tiltWrapper && heroCard) {
                let currentX = 0;
                let currentY = 0;
                let targetX = 0;
                let targetY = 0;
                let isHovered = false;
                let tiltRunning = false;

                function updateTilt() {
                    currentX += (targetX - currentX) * 0.1;
                    currentY += (targetY - currentY) * 0.1;

                    const scale = isHovered ? 1.02 : 1;
                    heroCard.style.transform =
                        `perspective(1000px) rotateX(${currentX.toFixed(2)}deg) rotateY(${currentY.toFixed(2)}deg) scale3d(${scale}, ${scale}, ${scale})`;

                    // Stop loop when resting at zero to keep CPU at 0%
                    if (isHovered || Math.abs(currentX) > 0.05 || Math.abs(currentY) > 0.05) {
                        requestAnimationFrame(updateTilt);
                    } else {
                        heroCard.style.transform =
                            'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                        tiltRunning = false;
                    }
                }

                tiltWrapper.addEventListener('mousemove', (e) => {
                    const rect = tiltWrapper.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    targetX = ((y - centerY) / centerY) * -7;
                    targetY = ((x - centerX) / centerX) * 7;
                    isHovered = true;

                    if (!tiltRunning) {
                        tiltRunning = true;
                        requestAnimationFrame(updateTilt);
                    }
                });

                tiltWrapper.addEventListener('mouseleave', () => {
                    targetX = 0;
                    targetY = 0;
                    isHovered = false;
                    if (!tiltRunning) {
                        tiltRunning = true;
                        requestAnimationFrame(updateTilt);
                    }
                });
            }

            // 3. Scroll Reveal Animation for all sections
            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.05
            });

            revealElements.forEach(el => observer.observe(el));

            // 4. Animated Metric Counter (Counts up smoothly when viewed)
            const counters = document.querySelectorAll('[data-counter]');
            let counted = false;
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !counted) {
                        counted = true;
                        counters.forEach(c => {
                            const target = parseInt(c.getAttribute('data-counter'), 10);
                            const prefix = c.getAttribute('data-prefix') || '';
                            const suffix = c.getAttribute('data-suffix') || '';
                            let count = 0;
                            const duration = 1500;
                            const stepTime = Math.abs(Math.floor(duration / target));

                            const timer = setInterval(() => {
                                count += Math.ceil(target / 40);
                                if (count >= target) {
                                    count = target;
                                    clearInterval(timer);
                                }
                                c.textContent = `${prefix}${count}${suffix}`;
                            }, 30);
                        });
                    }
                });
            }, {
                threshold: 0.2
            });

            counters.forEach(c => counterObserver.observe(c));

            // 5. Initialize Customer Reviews Swiper Carousel
            function initReviewsSwiper() {
                if (typeof Swiper !== 'undefined' && document.querySelector('.customer-reviews-swiper')) {
                    new Swiper('.customer-reviews-swiper', {
                        slidesPerView: 1,
                        spaceBetween: 24,
                        loop: {{ $customerReviews->count() > 3 ? 'true' : 'false' }},
                        autoplay: {
                            delay: 4500,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        },
                        pagination: {
                            el: '.swiper-pagination-reviews',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.reviews-swiper-next',
                            prevEl: '.reviews-swiper-prev',
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 24,
                            },
                        },
                    });
                }
            }

            if (typeof Swiper !== 'undefined') {
                initReviewsSwiper();
            } else {
                window.addEventListener('load', initReviewsSwiper);
            }
        });
    </script>
@endsection
