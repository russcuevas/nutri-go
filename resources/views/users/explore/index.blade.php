@extends('layouts.app')

@section('title', 'Explore Healthy Foods & Stores in Lipa City | NutriGo')

@section('content')
<div class="bg-gradient-to-b from-nutri-900 to-nutri-950 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight">Explore Healthy Foods in Lipa</h1>
        <p class="text-xs sm:text-sm text-nutri-200 mt-1">Calorie-counted, verified organic, high-protein & keto foods in Lipa City</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-3 space-y-6">
            <form action="{{ route('users.explore') }}" method="GET" class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-6">
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Search Food / Store</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ $searchQuery }}" placeholder="e.g. Quinoa, Salad, Steak..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- Lipa Barangay Filter -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Lipa City Barangay</label>
                    <select name="barangay" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500 bg-white">
                        <option value="">All Lipa Barangays</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b }}" {{ $selectedBarangay == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Calorie Range Filter -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Calorie Budget (kcal)</label>
                    <div class="space-y-1.5 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="" {{ empty($calorieFilter) ? 'checked' : '' }} class="text-nutri-600">
                            <span>All Calories</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="under300" {{ $calorieFilter == 'under300' ? 'checked' : '' }} class="text-nutri-600">
                            <span>🥗 Under 300 kcal (Light & Detox)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="300to500" {{ $calorieFilter == '300to500' ? 'checked' : '' }} class="text-nutri-600">
                            <span>🍗 300 - 500 kcal (Balanced Meal)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="over500" {{ $calorieFilter == 'over500' ? 'checked' : '' }} class="text-nutri-600">
                            <span>💪 Over 500 kcal (High Energy/Gym)</span>
                        </label>
                    </div>
                </div>

                <!-- Dietary Tags -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Dietary Preference</label>
                    <select name="diet" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500 bg-white">
                        <option value="">All Diets</option>
                        <option value="Vegan" {{ $selectedDiet == 'Vegan' ? 'selected' : '' }}>🌱 100% Vegan</option>
                        <option value="High-Protein" {{ $selectedDiet == 'High-Protein' ? 'selected' : '' }}>🍗 High-Protein</option>
                        <option value="Keto" {{ $selectedDiet == 'Keto' ? 'selected' : '' }}>🥑 Keto & Low-Carb</option>
                        <option value="Diabetic-Friendly" {{ $selectedDiet == 'Diabetic-Friendly' ? 'selected' : '' }}>🩸 Diabetic-Friendly</option>
                        <option value="Gluten-Free" {{ $selectedDiet == 'Gluten-Free' ? 'selected' : '' }}>🌾 Gluten-Free</option>
                        <option value="Low-Sodium" {{ $selectedDiet == 'Low-Sodium' ? 'selected' : '' }}>🧂 Low-Sodium</option>
                    </select>
                </div>

                <!-- Supplements Only Checkbox -->
                <div class="pt-2 border-t border-gray-100">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-purple-900">
                        <input type="checkbox" name="supplements" value="1" {{ $onlySupplements ? 'checked' : '' }} class="rounded text-purple-600">
                        <span>💊 Show Supplements Only</span>
                    </label>
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-nutri-900 text-white font-bold text-xs shadow-sm hover:bg-nutri-800 transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('users.explore') }}" class="px-3 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs hover:bg-gray-200 transition text-center">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        <!-- Product & Store Grid -->
        <main class="lg:col-span-9 space-y-8">
            
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('users.explore') }}" class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition {{ empty($selectedCategory) ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
                    All Categories
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('users.explore', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition flex items-center gap-1.5 {{ $selectedCategory == $cat->slug ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
                        <span>{{ $cat->icon }}</span> {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Products List -->
            @if($products->isEmpty())
                <div class="bg-white p-12 rounded-3xl border border-gray-200 text-center space-y-4 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-nutri-50 text-nutri-600 text-2xl flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-salad"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 font-heading">No healthy items match your filter</h3>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">Try selecting a different Lipa City barangay, calorie range, or dietary tag.</p>
                    <a href="{{ route('users.explore') }}" class="inline-flex px-4 py-2 rounded-xl bg-nutri-900 text-white text-xs font-bold">
                        Clear All Filters
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $item)
                        <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition group flex flex-col justify-between">
                            <div>
                                <!-- Image & Calorie Tag -->
                                <div class="relative h-44 overflow-hidden bg-gray-100">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    
                                    <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
                                        @if($item->is_healthy_choice)
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[9px] font-extrabold shadow">
                                                <i class="fa-solid fa-shield-heart mr-0.5"></i> Healthy Choice
                                            </span>
                                        @endif
                                        @if($item->is_supplement)
                                            <span class="px-2 py-0.5 rounded-full bg-purple-600 text-white text-[9px] font-extrabold shadow">
                                                💊 Supplement
                                            </span>
                                        @endif
                                    </div>

                                    <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded-lg bg-black/75 text-limey-300 text-[11px] font-bold backdrop-blur-md">
                                        🔥 {{ $item->calories }} kcal
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-5">
                                    <a href="{{ route('users.stores.show', $item->store->slug) }}" class="text-[10px] font-extrabold text-nutri-600 uppercase tracking-wider hover:underline block mb-1">
                                        {{ $item->store->store_name }} • <span class="text-gray-400 font-normal">Brgy. {{ $item->store->barangay }}</span>
                                    </a>
                                    
                                    <h3 class="text-base font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                        {{ $item->name }}
                                    </h3>
                                    
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>

                                    <!-- Dietary Badges -->
                                    @if(!empty($item->dietary_tags))
                                        <div class="flex flex-wrap gap-1 mt-2.5">
                                            @foreach(array_slice($item->dietary_tags, 0, 3) as $tag)
                                                <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-semibold">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Macros -->
                                    <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[11px] font-bold text-gray-600">
                                        <span>💪 {{ $item->protein_g }}g P</span>
                                        <span>🍞 {{ $item->carbs_g }}g C</span>
                                        <span>🥑 {{ $item->fat_g }}g F</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price & Add -->
                            <div class="p-5 pt-0 flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                                    @if($item->original_price)
                                        <span class="text-[11px] text-gray-400 line-through ml-1">₱{{ number_format($item->original_price, 2) }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('users.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif

        </main>
    </div>
</div>
@endsection
