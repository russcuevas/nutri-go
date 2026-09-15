<style>
    /* Responsive Grid vs List View Styles */
    .products-container-list {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.25rem !important;
    }
    .products-container-list .product-card-item {
        flex-direction: row !important;
        align-items: stretch !important;
    }
    .products-container-list .product-card-image-wrap {
        width: 230px !important;
        height: auto !important;
        min-height: 100% !important;
        flex-shrink: 0 !important;
        border-radius: 1.5rem 0 0 1.5rem !important;
    }
    .products-container-list .product-card-main-body {
        display: flex !important;
        flex: 1 !important;
        flex-direction: row !important;
        justify-content: space-between !important;
    }
    .products-container-list .product-card-content-wrap {
        flex: 1 !important;
        padding: 1.25rem 1.5rem !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
    }
    .products-container-list .product-card-action-wrap {
        padding: 1.5rem !important;
        border-left: 1px solid #f1f5f9 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-end !important;
        justify-content: center !important;
        min-width: 180px !important;
        background-color: #fafbfc !important;
        border-radius: 0 1.5rem 1.5rem 0 !important;
        gap: 0.75rem !important;
    }

    @media (max-width: 768px) {
        .products-container-list .product-card-item {
            flex-direction: column !important;
        }
        .products-container-list .product-card-image-wrap {
            width: 100% !important;
            height: 190px !important;
            border-radius: 1.5rem 1.5rem 0 0 !important;
        }
        .products-container-list .product-card-main-body {
            flex-direction: column !important;
        }
        .products-container-list .product-card-action-wrap {
            border-left: none !important;
            border-top: 1px solid #f1f5f9 !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            border-radius: 0 0 1.5rem 1.5rem !important;
        }
    }
</style>

<!-- Top Bar: Category Pills + View Mode Controls -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none flex-grow">
        <a href="{{ route('users.explore') }}" data-category="" class="category-pill-btn px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition {{ empty($selectedCategory) ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
            All Categories
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('users.explore', ['category' => $cat->slug]) }}" data-category="{{ $cat->slug }}" class="category-pill-btn px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition flex items-center gap-1.5 {{ $selectedCategory == $cat->slug ? 'bg-nutri-900 text-white shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-nutri-300' }}">
                <span>{{ $cat->icon }}</span> {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- View Mode Switcher (Grid vs List) -->
    <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl shrink-0 self-end sm:self-auto border border-gray-200/80 shadow-inner">
        <button type="button" onclick="setViewMode('grid')" id="view-mode-grid-btn"
                title="Grid View"
                class="view-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition bg-white text-nutri-950 shadow-xs">
            <i class="fa-solid fa-table-cells-large text-nutri-600"></i>
            <span class="hidden md:inline">Grid</span>
        </button>
        <button type="button" onclick="setViewMode('list')" id="view-mode-list-btn"
                title="List View"
                class="view-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition text-gray-500 hover:text-gray-900">
            <i class="fa-solid fa-list-ul"></i>
            <span class="hidden md:inline">List</span>
        </button>
    </div>
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
    <div id="products-listing-wrapper" class="products-container-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 transition-all duration-300">
        @foreach($products as $item)
            <div class="product-card-item bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition group flex flex-col justify-between hover:-translate-y-1">
                <!-- Image & Calorie Tag -->
                <div class="product-card-image-wrap relative h-44 overflow-hidden bg-gray-100">
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

                <!-- Main Body for Grid / List Flex -->
                <div class="product-card-main-body flex flex-col justify-between flex-1">
                    <!-- Card Body -->
                    <div class="product-card-content-wrap p-5">
                        <a href="{{ route('users.stores.show', $item->store->slug) }}" class="text-[10px] font-extrabold text-nutri-600 uppercase tracking-wider hover:underline block mb-1 leading-tight">
                            {{ $item->store->store_name }}
                            <br>
                            <span class="text-[9px] text-gray-400 font-normal normal-case block truncate mt-0.5"><i class="fa-solid fa-location-dot text-[8px] text-gray-400 mr-0.5"></i>{{ $item->store->address_line ?? $item->store->barangay }}</span>
                        </a>
                        
                        <h3 class="text-base font-black text-gray-900 font-heading group-hover:text-nutri-600 transition leading-snug">
                            {{ $item->name }}
                        </h3>
                        
                        <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">{{ $item->description }}</p>

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

                    <!-- Price & Add -->
                    <div class="product-card-action-wrap p-5 pt-0 flex items-center justify-between">
                        <div class="product-card-price-block">
                            <span class="text-lg font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                            @if($item->original_price)
                                <span class="text-[11px] text-gray-400 line-through ml-1">₱{{ number_format($item->original_price, 2) }}</span>
                            @endif
                        </div>
                        @if($item->store && $item->store->is_open)
                            <form action="{{ route('users.cart.add') }}" method="POST" class="ajax-add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->id }}">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 active:scale-95">
                                    <i class="fa-solid fa-plus"></i> Add to Cart
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="px-3.5 py-2 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed flex items-center gap-1.5" title="Store is closed">
                                <i class="fa-solid fa-lock text-[10px]"></i> Closed
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-6 pagination-wrapper">
        {{ $products->links() }}
    </div>
@endif
