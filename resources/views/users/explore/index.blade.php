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
            <form id="explore-filter-form" action="{{ route('users.explore') }}" method="GET" class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-6">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Search Food / Store</label>
                    <div class="relative">
                        <input type="text" id="search-input" name="q" value="{{ $searchQuery }}" placeholder="e.g. Quinoa, Salad, Steak..." oninput="debounceFilterSubmit()" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- Near Me Stores (GPS Auto-Filter) -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2 flex items-center justify-between">
                        <span>Stores Near Me</span>
                        <span id="near-me-status-pill" class="{{ !empty($nearMe) ? '' : 'hidden' }} text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                            ● Active
                        </span>
                    </label>

                    <input type="hidden" id="user_lat" name="user_lat" value="{{ $userLat ?? request('user_lat') }}">
                    <input type="hidden" id="user_lng" name="user_lng" value="{{ $userLng ?? request('user_lng') }}">
                    <input type="hidden" id="near_me" name="near_me" value="{{ !empty($nearMe) ? '1' : request('near_me', '') }}">

                    <div class="space-y-2">
                        <button type="button" id="btn-near-me" onclick="toggleNearMe()"
                                class="w-full py-2.5 px-3 rounded-xl border-2 {{ !empty($nearMe) ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-600' }} font-extrabold text-xs transition flex items-center justify-center gap-2 shadow-xs group cursor-pointer">
                            <i class="fa-solid fa-location-crosshairs text-sm {{ !empty($nearMe) ? 'text-limey-300' : 'text-emerald-600' }} group-hover:scale-110 transition-transform"></i>
                            <span id="btn-near-me-text">{{ !empty($nearMe) ? 'Location Active (Nearest First)' : 'Find Stores Near Me' }}</span>
                        </button>
                        
                        <div id="near-me-info" class="{{ !empty($nearMe) ? '' : 'hidden' }} text-[11px] text-emerald-800 bg-emerald-50 border border-emerald-200 p-2.5 rounded-xl flex items-center justify-between">
                            <span class="flex items-center gap-1.5 truncate">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                <span>Sorted by nearest Lipa store</span>
                            </span>
                            <button type="button" onclick="clearNearMe()" class="text-xs text-rose-600 hover:text-rose-800 font-bold px-1.5 py-0.5 rounded hover:bg-rose-100 transition" title="Clear Near Me">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Healthy Store / Restaurant Filter -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Healthy Restaurant / Store</label>
                    <select name="store" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500 bg-white cursor-pointer">
                        <option value="">All Healthy Stores</option>
                        @foreach($allStores as $s)
                            <option value="{{ $s->id }}" {{ $selectedStore == $s->id ? 'selected' : '' }}>
                                {{ $s->store_name }} ({{ $s->address_line ?? $s->barangay }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Calorie Range Filter -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Calorie Budget (kcal)</label>
                    <div class="space-y-1.5 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="" {{ empty($calorieFilter) ? 'checked' : '' }} onchange="this.form.submit()" class="text-nutri-600">
                            <span>All Calories</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="under300" {{ $calorieFilter == 'under300' ? 'checked' : '' }} onchange="this.form.submit()" class="text-nutri-600">
                            <span>🥗 Under 300 kcal (Light & Detox)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="300to500" {{ $calorieFilter == '300to500' ? 'checked' : '' }} onchange="this.form.submit()" class="text-nutri-600">
                            <span>🍗 300 - 500 kcal (Balanced Meal)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calorie_range" value="over500" {{ $calorieFilter == 'over500' ? 'checked' : '' }} onchange="this.form.submit()" class="text-nutri-600">
                            <span>💪 Over 500 kcal (High Energy/Gym)</span>
                        </label>
                    </div>
                </div>

                <!-- Dietary Tags -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2">Dietary Preference</label>
                    <select name="diet" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500 bg-white cursor-pointer">
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
                        <input type="checkbox" name="supplements" value="1" {{ $onlySupplements ? 'checked' : '' }} onchange="this.form.submit()" class="rounded text-purple-600">
                        <span>💊 Show Supplements Only</span>
                    </label>
                </div>
            </form>
        </aside>

        <!-- Product & Store Grid -->
        <main id="products-view-container" class="lg:col-span-9 space-y-8 relative transition-opacity duration-200 min-h-[400px]">
            @include('users.explore.partials.products_grid')
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let filterDebounceTimer;

    function debounceFilterSubmit() {
        clearTimeout(filterDebounceTimer);
        filterDebounceTimer = setTimeout(() => {
            fetchLiveFilteredProducts();
        }, 300);
    }

    function triggerLiveFilter() {
        fetchLiveFilteredProducts();
    }

    function toggleNearMe() {
        const btn = document.getElementById('btn-near-me');
        const btnText = document.getElementById('btn-near-me-text');
        const userLatInput = document.getElementById('user_lat');
        const userLngInput = document.getElementById('user_lng');
        const nearMeInput = document.getElementById('near_me');
        const info = document.getElementById('near-me-info');
        const statusPill = document.getElementById('near-me-status-pill');

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        btn.disabled = true;
        if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Getting GPS location...';

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                if (userLatInput) userLatInput.value = pos.coords.latitude.toFixed(6);
                if (userLngInput) userLngInput.value = pos.coords.longitude.toFixed(6);
                if (nearMeInput) nearMeInput.value = '1';

                btn.classList.remove('bg-emerald-50', 'text-emerald-800');
                btn.classList.add('bg-emerald-600', 'text-white');
                if (btnText) btnText.innerHTML = 'Location Active (Nearest First)';
                btn.disabled = false;

                if (info) info.classList.remove('hidden');
                if (statusPill) statusPill.classList.remove('hidden');

                triggerLiveFilter();
            },
            function(err) {
                console.warn('Geolocation error:', err);
                btn.disabled = false;
                if (btnText) btnText.innerText = 'Find Stores Near Me (GPS)';
                alert('Could not access your location. Please check browser location permissions to find nearby Lipa stores.');
            },
            { timeout: 10000, enableHighAccuracy: true }
        );
    }

    function clearNearMe() {
        const btn = document.getElementById('btn-near-me');
        const btnText = document.getElementById('btn-near-me-text');
        const userLatInput = document.getElementById('user_lat');
        const userLngInput = document.getElementById('user_lng');
        const nearMeInput = document.getElementById('near_me');
        const info = document.getElementById('near-me-info');
        const statusPill = document.getElementById('near-me-status-pill');

        if (userLatInput) userLatInput.value = '';
        if (userLngInput) userLngInput.value = '';
        if (nearMeInput) nearMeInput.value = '';

        if (btn) {
            btn.classList.remove('bg-emerald-600', 'text-white');
            btn.classList.add('bg-emerald-50', 'text-emerald-800');
            if (btnText) btnText.innerText = 'Find Stores Near Me';
        }
        if (info) info.classList.add('hidden');
        if (statusPill) statusPill.classList.add('hidden');

        triggerLiveFilter();
    }

    function clearLiveFilters() {
        const form = document.getElementById('explore-filter-form');
        if (form) {
            form.reset();
            const searchInput = document.getElementById('search-input');
            if (searchInput) searchInput.value = '';
            const hiddenCat = form.querySelector('input[name="category"]');
            if (hiddenCat) hiddenCat.value = '';
        }
        clearNearMe();
        fetchLiveFilteredProducts('{{ route("users.explore") }}');
    }

    async function fetchLiveFilteredProducts(customUrl = null) {
        const container = document.getElementById('products-view-container');
        const form = document.getElementById('explore-filter-form');
        
        let targetUrl;
        if (customUrl) {
            targetUrl = customUrl;
        } else {
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, val] of formData.entries()) {
                if (val !== '' && val !== null) {
                    params.append(key, val);
                }
            }
            targetUrl = `{{ route('users.explore') }}?${params.toString()}`;
        }

        if (container) {
            container.classList.add('opacity-50', 'pointer-events-none');
        }

        try {
            const response = await fetch(targetUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Live-Filter': '1',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.html && container) {
                    container.innerHTML = data.html;
                    applyViewMode();
                    // Update URL without page reload and without scrolling
                    window.history.pushState({ path: targetUrl }, '', targetUrl);
                }
            }
        } catch (err) {
            console.error('Live filter error:', err);
        } finally {
            if (container) {
                container.classList.remove('opacity-50', 'pointer-events-none');
            }
        }
    }

    let currentViewMode = localStorage.getItem('nutrigo_explore_view_mode') || 'grid';

    function setViewMode(mode) {
        currentViewMode = mode;
        try {
            localStorage.setItem('nutrigo_explore_view_mode', mode);
        } catch (e) {}
        applyViewMode();
    }

    function applyViewMode() {
        const wrapper = document.getElementById('products-listing-wrapper');
        const gridBtn = document.getElementById('view-mode-grid-btn');
        const listBtn = document.getElementById('view-mode-list-btn');

        if (!wrapper) return;

        if (currentViewMode === 'list') {
            wrapper.classList.remove('products-container-grid', 'grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
            wrapper.classList.add('products-container-list');

            if (listBtn) {
                listBtn.classList.remove('text-gray-500', 'hover:text-gray-900');
                listBtn.classList.add('bg-white', 'text-nutri-950', 'shadow-xs');
            }
            if (gridBtn) {
                gridBtn.classList.remove('bg-white', 'text-nutri-950', 'shadow-xs');
                gridBtn.classList.add('text-gray-500', 'hover:text-gray-900');
            }
        } else {
            wrapper.classList.remove('products-container-list');
            wrapper.classList.add('products-container-grid', 'grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

            if (gridBtn) {
                gridBtn.classList.remove('text-gray-500', 'hover:text-gray-900');
                gridBtn.classList.add('bg-white', 'text-nutri-950', 'shadow-xs');
            }
            if (listBtn) {
                listBtn.classList.remove('bg-white', 'text-nutri-950', 'shadow-xs');
                listBtn.classList.add('text-gray-500', 'hover:text-gray-900');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyViewMode();
        // Handle input events in the sidebar form
        const form = document.getElementById('explore-filter-form');
        if (form) {
            form.querySelectorAll('select, input[type="radio"], input[type="checkbox"]').forEach(el => {
                el.removeAttribute('onchange');
                el.addEventListener('change', function() {
                    triggerLiveFilter();
                });
            });
        }

        // Intercept Category Pill Clicks & Pagination Clicks
        document.addEventListener('click', function(e) {
            const pill = e.target.closest('.category-pill-btn');
            if (pill) {
                e.preventDefault();
                const categorySlug = pill.dataset.category || '';
                
                // Update hidden category input in sidebar form
                let hiddenCat = form ? form.querySelector('input[name="category"]') : null;
                if (!hiddenCat && form) {
                    hiddenCat = document.createElement('input');
                    hiddenCat.type = 'hidden';
                    hiddenCat.name = 'category';
                    form.appendChild(hiddenCat);
                }
                if (hiddenCat) {
                    hiddenCat.value = categorySlug;
                }

                fetchLiveFilteredProducts();
                return;
            }

            // Intercept pagination clicks inside container
            const pageLink = e.target.closest('.pagination-wrapper a');
            if (pageLink) {
                e.preventDefault();
                const targetUrl = pageLink.getAttribute('href');
                if (targetUrl) {
                    fetchLiveFilteredProducts(targetUrl);
                }
            }
        });

        // Browser Back / Forward Button Handling
        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    });
</script>
@endpush
