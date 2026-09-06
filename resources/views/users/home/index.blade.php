@extends('layouts.app')

@section('title', 'NutriGo | Lipa City Healthy Food & Nutrition Delivery')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white py-20 lg:py-28">
    <!-- Glow Background Accents -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-limey-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Hero Left: Text & CTA -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <!-- Location Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-limey-300 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-limey-400 animate-ping"></span>
                    <i class="fa-solid fa-location-dot"></i> Exclusively Serving Lipa City, Batangas
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight font-heading leading-[1.1]">
                    Healthy Food, <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-limey-400 via-emerald-300 to-teal-200">Anytime, Anywhere.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-nutri-100 max-w-2xl leading-relaxed mx-auto lg:mx-0 font-normal">
                    Discover verified organic restaurants, high-protein gym bowls, keto meals, certified supplements, and creator-led cooking vlogs delivered fast to your doorstep across Lipa.
                </p>

                <!-- Search & Quick Finder -->
                <form action="{{ route('users.explore') }}" method="GET" class="p-2 sm:p-2.5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-xl shadow-2xl flex flex-col sm:flex-row gap-2 max-w-xl mx-auto lg:mx-0">
                    <div class="flex-1 flex items-center pl-3 pr-2 py-2.5 rounded-xl bg-white text-gray-900">
                        <i class="fa-solid fa-magnifying-glass text-nutri-600 mr-2.5"></i>
                        <input type="text" name="q" placeholder="Search salads, keto, calories, protein, stores..." class="w-full text-xs sm:text-sm outline-none bg-transparent font-medium">
                    </div>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-extrabold text-sm shadow-md transition font-heading shrink-0 flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Search Lipa Food
                    </button>
                </form>

                <!-- Key Metrics & Perks -->
                <div class="pt-4 grid grid-cols-3 gap-4 border-t border-white/10 max-w-lg mx-auto lg:mx-0 text-left">
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-limey-400 font-heading">100%</div>
                        <div class="text-[11px] text-nutri-200">Calorie & Macro Counted</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-white font-heading">72</div>
                        <div class="text-[11px] text-nutri-200">Lipa Barangays Covered</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-300 font-heading">₱40</div>
                        <div class="text-[11px] text-nutri-200">Fair Base Delivery Fare</div>
                    </div>
                </div>
            </div>

            <!-- Hero Right: Interactive 3D Card Stack -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md">
                    <!-- Main Hero Image Showcase -->
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white/15 group">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&q=80" alt="NutriGo Fresh Salad" class="w-full h-96 object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-nutri-950/90 via-nutri-950/30 to-transparent"></div>
                        
                        <div class="absolute bottom-6 left-6 right-6">
                            <span class="px-2.5 py-1 rounded-full bg-limey-400 text-nutri-950 text-[10px] font-extrabold uppercase tracking-wider mb-2 inline-block">
                                <i class="fa-solid fa-heart mr-1"></i> Healthy Choice of the Day
                            </span>
                            <h3 class="text-xl font-black text-white font-heading">Avocado Quinoa Power Salad</h3>
                            <div class="flex items-center gap-3 text-xs text-nutri-200 mt-1">
                                <span>🔥 380 kcal</span>
                                <span>💪 14.5g Protein</span>
                                <span>🥑 Healthy Fats</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badge 1: Lipa Live Delivery -->
                    <div class="absolute -top-6 -left-6 p-4 rounded-2xl bg-white/95 text-gray-900 shadow-xl border border-nutri-200 backdrop-blur-md hidden sm:flex items-center gap-3 animate-bounce" style="animation-duration: 4s;">
                        <div class="w-10 h-10 rounded-xl bg-nutri-50 text-nutri-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-900">Live GPS Order Dispatch</p>
                            <p class="text-[10px] text-nutri-600 font-extrabold">Instant Point-to-Point Rate</p>
                        </div>
                    </div>

                    <!-- Floating Badge 2: Certified Healthy -->
                    <div class="absolute -bottom-6 -right-6 p-4 rounded-2xl bg-white/95 text-gray-900 shadow-xl border border-nutri-200 backdrop-blur-md hidden sm:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-900">Vetted Healthy Stores Only</p>
                            <p class="text-[10px] text-gray-500 font-semibold">Zero Junk Food Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Interactive Calorie & Macro Target Estimator Widget -->
<section class="py-12 bg-white border-b border-gray-100" x-data="{
    goal: 'weight_loss',
    activity: 'moderate',
    calories: 1850,
    protein: 140,
    recalculate() {
        if(this.goal === 'weight_loss') { this.calories = 1650; this.protein = 135; }
        else if(this.goal === 'muscle_gain') { this.calories = 2400; this.protein = 175; }
        else { this.calories = 2000; this.protein = 145; }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-nutri-50 via-white to-lime-50 border border-nutri-200 shadow-card">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 space-y-3">
                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-nutri-100 text-nutri-800">
                        <i class="fa-solid fa-calculator mr-1"></i> Interactive NutriEstimator
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading tracking-tight">
                        Find meals that fit your exact daily health goals
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Select your wellness target to immediately filter food that matches your calorie budget in Lipa City.
                    </p>
                    
                    <!-- Goal Selector -->
                    <div class="grid grid-cols-3 gap-2 pt-2">
                        <button @click="goal = 'weight_loss'; recalculate()" :class="goal === 'weight_loss' ? 'bg-nutri-800 text-white shadow' : 'bg-white text-gray-700 border border-gray-200'" class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                            🥗 Weight Loss
                        </button>
                        <button @click="goal = 'muscle_gain'; recalculate()" :class="goal === 'muscle_gain' ? 'bg-nutri-800 text-white shadow' : 'bg-white text-gray-700 border border-gray-200'" class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                            🍗 Build Muscle
                        </button>
                        <button @click="goal = 'maintenance'; recalculate()" :class="goal === 'maintenance' ? 'bg-nutri-800 text-white shadow' : 'bg-white text-gray-700 border border-gray-200'" class="p-2.5 rounded-xl text-xs font-bold transition text-center">
                            🥑 Stay Fit
                        </button>
                    </div>
                </div>

                <!-- Calculator Result Card -->
                <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-nutri-200 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Suggested Per Meal Target</span>
                                <div class="text-3xl font-black text-nutri-900 font-heading">
                                    <span x-text="Math.round(calories / 3)"></span> <span class="text-sm font-semibold text-nutri-600">kcal / meal</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-bold text-gray-700">
                                <div><span class="text-rose-600">💪</span> <span x-text="Math.round(protein / 3)"></span>g Protein</div>
                                <div><span class="text-amber-500">🥑</span> Low-Carb Option</div>
                                <div><span class="text-emerald-600">🌱</span> High-Fiber</div>
                            </div>
                        </div>

                        <div class="text-center sm:text-right">
                            <a :href="'{{ route('users.explore') }}?calorie_range=' + (goal === 'weight_loss' ? 'under300' : (goal === 'muscle_gain' ? 'over500' : '300to500'))" 
                               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-extrabold text-sm shadow-md transition font-heading">
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
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-nutri-600">Curated Categories</span>
                <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-1">What are you craving today?</h2>
            </div>
            <a href="{{ route('users.explore') }}" class="mt-4 md:mt-0 text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1">
                Explore all items <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('users.explore', ['category' => $cat->slug]) }}" 
                   class="p-5 rounded-2xl bg-white border border-gray-100 hover:border-nutri-300 shadow-sm hover:shadow-md transition text-center group">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">{{ $cat->icon }}</div>
                    <h4 class="text-xs font-bold text-gray-900 group-hover:text-nutri-600 transition font-heading">{{ $cat->name }}</h4>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Healthy Choices (With Badges & Calories) -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-award mr-1"></i> Lipa Verified
                </span>
                <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-2">Top Healthy Choice Meals</h2>
            </div>
            <a href="{{ route('users.explore') }}" class="mt-4 md:mt-0 text-xs font-bold text-nutri-600 hover:text-nutri-700 flex items-center gap-1">
                Browse Full Menu <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($healthyChoices as $item)
                <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition group flex flex-col justify-between">
                    <div>
                        <!-- Image Container -->
                        <div class="relative h-48 overflow-hidden bg-gray-100">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold shadow">
                                    <i class="fa-solid fa-shield-heart mr-1"></i> Healthy Choice
                                </span>
                                @if($item->is_supplement)
                                    <span class="px-2.5 py-1 rounded-full bg-purple-600 text-white text-[10px] font-extrabold shadow">
                                        💊 Supplement
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-xl bg-gray-900/90 text-white text-xs font-black backdrop-blur-md shadow-md flex items-center gap-1">
                                🔥 {{ $item->calories }} kcal
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="text-[11px] font-extrabold text-nutri-600 uppercase tracking-wider mb-1 leading-tight">
                                {{ $item->store->store_name }}
                                <br>
                                <span class="text-[10px] text-gray-400 font-normal normal-case block truncate mt-0.5"><i class="fa-solid fa-location-dot text-[9px] text-gray-400 mr-0.5"></i>{{ $item->store->address_line ?? $item->store->barangay }}</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                {{ $item->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>

                            <!-- Macros Breakdown -->
                            <div class="mt-4 pt-3 border-t border-gray-100 grid grid-cols-3 gap-2 text-center text-xs">
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
                            <span class="text-xl font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                            @if($item->original_price)
                                <span class="text-xs text-gray-400 line-through ml-1">₱{{ number_format($item->original_price, 2) }}</span>
                            @endif
                        </div>
                        <form action="{{ route('users.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm hover:shadow transition flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Content Creator Cooking Vlogs Reel -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800">
                    <i class="fa-solid fa-video mr-1"></i> Partner Health Vloggers
                </span>
                <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-2">Cook Healthy with Lipa Food Creators</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Watch 10-minute cooking videos and order exact ingredients directly from partner stores!</p>
            </div>
            <a href="{{ route('creators.index') }}" class="mt-4 md:mt-0 text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                View All Cooking Vlogs <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredRecipes as $vlog)
                <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail -->
                        <div class="relative h-52 overflow-hidden bg-gray-900">
                            <img src="{{ $vlog->thumbnail_url }}" alt="{{ $vlog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <a href="{{ route('creators.show', $vlog->slug) }}" class="w-14 h-14 rounded-full bg-rose-600 text-white flex items-center justify-center text-xl shadow-lg hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play ml-1"></i>
                                </a>
                            </div>
                            <div class="absolute bottom-3 left-3 px-2.5 py-1 rounded-xl bg-black/70 text-white text-[10px] font-bold backdrop-blur-md">
                                ⏱️ {{ $vlog->prep_time_mins }} mins prep • 🔥 {{ $vlog->calories }} kcal
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ $vlog->creator->avatar_url }}" alt="{{ $vlog->creator->name }}" class="w-6 h-6 rounded-full object-cover">
                                <span class="text-xs font-bold text-gray-700">{{ $vlog->creator->channel_name }}</span>
                            </div>
                            <h3 class="text-base font-black text-gray-900 font-heading group-hover:text-rose-600 transition">
                                <a href="{{ route('creators.show', $vlog->slug) }}">{{ $vlog->title }}</a>
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $vlog->description }}</p>
                        </div>
                    </div>

                    <!-- 1-Click Order Ingredients Button -->
                    <div class="p-6 pt-0">
                        <form action="{{ route('creators.order.ingredients', $vlog->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-cart-plus"></i> Order Ingredients from {{ $vlog->store->store_name ?? 'Lipa Store' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Verified Healthy Stores in Lipa -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-nutri-600">Local Partnerships</span>
            <h2 class="text-3xl font-black text-gray-900 font-heading tracking-tight mt-1">Verified Healthy Stores in Lipa City</h2>
            <p class="text-xs text-gray-500 mt-1">Every store is strictly vetted for nutritional quality before onboarding.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredStores as $store)
                <a href="{{ route('users.stores.show', $store->slug) }}" class="p-6 rounded-3xl bg-white border border-gray-200/80 hover:border-nutri-300 shadow-card hover:shadow-xl transition group text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-50 p-1 border border-gray-100 shadow-sm mb-4 group-hover:scale-105 transition-transform">
                        <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover rounded-xl">
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-nutri-50 text-nutri-700 border border-nutri-200 mb-2">
                        {{ $store->health_category }}
                    </span>
                    <h3 class="text-base font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                        {{ $store->store_name }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $store->address_line ?? ('Brgy. ' . $store->barangay . ', Lipa') }}</p>
                    <div class="flex items-center gap-1 text-xs font-bold text-amber-500 mt-2">
                        <i class="fa-solid fa-star"></i> {{ number_format($store->rating, 2) }} <span class="text-gray-400 font-normal">({{ $store->total_reviews }})</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- VIP Subscription Banner -->
<section class="py-16 bg-gradient-to-r from-nutri-900 via-nutri-800 to-nutri-950 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-4">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-amber-400 text-nutri-950">
                    👑 NutriGo VIP Club
                </span>
                <h2 class="text-3xl sm:text-4xl font-black font-heading tracking-tight">
                    Upgrade to VIP for Exclusive Meal Preps & Free Deliveries
                </h2>
                <p class="text-xs sm:text-sm text-nutri-200 max-w-2xl leading-relaxed">
                    Unlock exclusive vlogger masterclasses, personal nutritionist macro plans, 15% discount on partner supplements, and free delivery perks across Lipa City.
                </p>
            </div>
            <div class="lg:col-span-4 text-center lg:text-right">
                <a href="{{ route('users.subscriptions.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-amber-400 hover:bg-amber-300 text-nutri-950 font-black text-sm shadow-xl transition font-heading transform hover:scale-105">
                    Explore VIP Plans <i class="fa-solid fa-crown"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
