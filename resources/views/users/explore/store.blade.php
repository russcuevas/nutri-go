@extends('layouts.app')

@section('title', $store->store_name . ' | Healthy Store in Lipa City | NutriGo')

@section('content')
<!-- Store Banner Header -->
<div class="relative bg-nutri-950 text-white">
    <div class="h-64 sm:h-80 w-full overflow-hidden opacity-40">
        <img src="{{ $store->banner_url }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover">
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-24 sm:-mt-28 pb-8">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-card border border-gray-200 text-gray-900 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-50 border-2 border-white shadow-md shrink-0">
                    <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                            {{ $store->health_category }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $store->is_open ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $store->is_open ? '● Open Now' : 'Closed' }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-gray-900">{{ $store->store_name }}</h1>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                        <span><i class="fa-solid fa-location-dot text-nutri-600"></i> {{ $store->address_line ?? ('Brgy. ' . $store->barangay . ', Lipa City') }}</span>
                        <span>•</span>
                        <span><i class="fa-regular fa-clock text-gray-400"></i> {{ date('h:i A', strtotime($store->opening_time)) }} - {{ date('h:i A', strtotime($store->closing_time)) }}</span>
                    </p>
                </div>
            </div>

            <!-- Rating & Fee Summary -->
            <div class="flex items-center gap-4 bg-gray-50 p-3.5 rounded-2xl border border-gray-200/80">
                <div class="text-center px-2">
                    <div class="text-xl font-black text-amber-500 font-heading flex items-center justify-center gap-1">
                        <i class="fa-solid fa-star text-sm"></i> {{ number_format($store->rating, 2) }}
                    </div>
                    <div class="text-[10px] text-gray-500 font-semibold">{{ $store->total_reviews }} Reviews</div>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="text-center px-2">
                    <div class="text-xl font-black text-nutri-900 font-heading">₱40</div>
                    <div class="text-[10px] text-gray-500 font-semibold">Base Fare (1.5km)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Menu Items List -->
        <div class="lg:col-span-8 space-y-8">
            <div>
                <h2 class="text-2xl font-black font-heading text-gray-900">Healthy Menu & Calorie Counts</h2>
                <p class="text-xs text-gray-500 mt-0.5">Freshly prepared nutritious meals from {{ $store->store_name }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse($store->products as $item)
                    <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition group flex flex-col justify-between">
                        <div>
                            <div class="relative h-44 overflow-hidden bg-gray-100">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                
                                <div class="absolute top-2.5 left-2.5 flex gap-1">
                                    @if($item->is_healthy_choice)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[9px] font-extrabold shadow">
                                            <i class="fa-solid fa-shield-heart mr-0.5"></i> Healthy Choice
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-2.5 right-2.5 px-2.5 py-1 rounded-lg bg-gray-900/90 text-white text-xs font-black backdrop-blur-md shadow-md flex items-center gap-1">
                                    🔥 {{ $item->calories }} kcal
                                </div>
                            </div>

                            <div class="p-5">
                                <h3 class="text-base font-black text-gray-900 font-heading group-hover:text-nutri-600 transition">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>

                                <!-- Smart Healthy Alternative Swap Recommendation -->
                                @if($item->alternativeTo)
                                    <div class="mt-2.5 p-2 rounded-xl bg-amber-50 border border-amber-200 text-[10px] text-amber-900">
                                        <i class="fa-solid fa-arrow-right-arrow-left text-amber-600 mr-1"></i>
                                        <strong>Smart Swap:</strong> {{ $item->healthy_alternative_notes ?? 'Healthier alternative with lower calories.' }}
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

                        <div class="p-5 pt-0 flex items-center justify-between">
                            <div>
                                <span class="text-lg font-black text-nutri-900 font-heading">₱{{ number_format($item->price, 2) }}</span>
                            </div>
                            <form action="{{ route('users.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->id }}">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white p-8 rounded-3xl border border-gray-200 text-center text-xs text-gray-500">
                        No food items available from this store yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Reviews -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Customer Health Reviews -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-nutri-600"></i> Customer Health Feedback
                </h3>

                @forelse($store->reviews as $rev)
                    <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900">{{ $rev->user->name }}</span>
                            <span class="text-amber-500 font-bold"><i class="fa-solid fa-star text-[10px]"></i> {{ $rev->store_rating }}</span>
                        </div>
                        <p class="text-gray-600 italic">"{{ $rev->store_comment }}"</p>
                        @if($rev->health_satisfaction)
                            <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                {{ $rev->health_satisfaction }}
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center py-4">No reviews yet. Be the first to order and review!</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
