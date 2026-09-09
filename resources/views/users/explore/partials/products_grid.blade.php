<!-- Category Pills -->
<div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
    <a href="{{ route('users.explore') }}" data-category="" class="category-pill-btn px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition {{ empty($selectedCategory) ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
        All Categories
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('users.explore', ['category' => $cat->slug]) }}" data-category="{{ $cat->slug }}" class="category-pill-btn px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition flex items-center gap-1.5 {{ $selectedCategory == $cat->slug ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
            <span>{{ $cat->icon }}</span> {{ $cat->name }}
        </a>
    @endforeach
</div>

<!-- Products List -->
@if($products->isEmpty())
    <div class="bg-white p-12 rounded-3xl border border-gray-200 text-center space-y-4 shadow-sm mt-6">
        <div class="w-16 h-16 rounded-full bg-nutri-50 text-nutri-600 text-2xl flex items-center justify-center mx-auto">
            <i class="fa-solid fa-salad"></i>
        </div>
        <h3 class="text-xl font-black text-gray-900 font-heading">No healthy items match your filter</h3>
        <p class="text-xs text-gray-500 max-w-md mx-auto">Try selecting a different Lipa City barangay, calorie range, or dietary tag.</p>
        <button type="button" onclick="clearLiveFilters()" class="inline-flex px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white text-xs font-bold transition">
            Clear All Filters
        </button>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($products as $item)
            <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition group flex flex-col justify-between">
                <div>
                    <!-- Image & Calorie Tag -->
                    <div class="relative h-44 overflow-hidden bg-gray-100">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
                            @if(isset($item->distance_km))
                                <span class="px-2 py-0.5 rounded-full bg-emerald-700 text-white text-[9px] font-extrabold shadow flex items-center gap-1">
                                    <i class="fa-solid fa-location-arrow text-limey-300"></i> {{ number_format($item->distance_km, 1) }} km
                                </span>
                            @endif
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

                        <div class="absolute bottom-2.5 right-2.5 px-2.5 py-1 rounded-lg bg-gray-900/90 text-white text-xs font-black backdrop-blur-md shadow-md flex items-center gap-1">
                            🔥 {{ $item->calories }} kcal
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        <a href="{{ route('users.stores.show', $item->store->slug) }}" class="text-[10px] font-extrabold text-nutri-600 uppercase tracking-wider hover:underline block mb-1 leading-tight">
                            {{ $item->store->store_name }}
                            <br>
                            <span class="text-[9px] text-gray-400 font-normal normal-case block truncate mt-0.5"><i class="fa-solid fa-location-dot text-[8px] text-gray-400 mr-0.5"></i>{{ $item->store->address_line ?? $item->store->barangay }}</span>
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
                    @if($item->store && $item->store->is_open)
                        <form action="{{ route('users.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1 active:scale-95">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed flex items-center gap-1" title="Store is closed">
                            <i class="fa-solid fa-lock text-[10px]"></i> Closed
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-6 pagination-wrapper">
        {{ $products->links() }}
    </div>
@endif
