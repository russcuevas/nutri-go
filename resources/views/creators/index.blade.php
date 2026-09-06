@extends('layouts.app')

@section('title', 'Partner Health Creators & Cooking Vlogs | NutriGo Lipa')

@section('content')
<div class="bg-gradient-to-b from-nutri-900 via-nutri-900 to-nutri-950 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-rose-500 text-white inline-block mb-3">
                <i class="fa-solid fa-fire mr-1"></i> NutriGo Creator Partnerships
            </span>
            <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight">Learn, Cook & Order Ingredients in 1-Click</h1>
            <p class="text-xs sm:text-sm text-nutri-200 mt-2 leading-relaxed">
                Watch certified sports nutritionists and health vloggers prepare delicious guilt-free meals. Like the recipe? Order all ingredients directly from partner stores in Lipa City!
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
    
    <!-- Partner Vloggers Spotlight -->
    <div>
        <h2 class="text-2xl font-black font-heading text-gray-900 mb-6">Featured Health Creators</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($creators as $c)
                <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card flex items-start gap-4">
                    <img src="{{ $c->avatar_url }}" alt="{{ $c->name }}" class="w-16 h-16 rounded-2xl object-cover shrink-0 border border-gray-100 shadow-sm">
                    <div class="space-y-1">
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-base font-black text-gray-900 font-heading">{{ $c->name }}</h3>
                            <i class="fa-solid fa-circle-check text-nutri-500 text-xs"></i>
                        </div>
                        <p class="text-xs font-bold text-rose-600">{{ $c->channel_name }}</p>
                        <p class="text-[11px] text-gray-500 line-clamp-2">{{ $c->bio }}</p>
                        <div class="pt-2 flex items-center gap-3 text-gray-400 text-xs">
                            <span class="font-bold text-gray-700 text-[11px]">{{ $c->recipes_count }} Video Recipes</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cooking Recipes & Vlogs Grid -->
    <div>
        <h2 class="text-2xl font-black font-heading text-gray-900 mb-6">Latest Healthy Cooking Vlogs</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($recipes as $vlog)
                <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-card hover:shadow-xl transition flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail & VIP Lock -->
                        <div class="relative h-56 overflow-hidden bg-gray-900">
                            <img src="{{ $vlog->thumbnail_url }}" alt="{{ $vlog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                            
                            @if($vlog->is_premium_only)
                                <div class="absolute top-3 left-3 px-3 py-1 rounded-full bg-amber-400 text-nutri-950 text-[10px] font-black uppercase tracking-wider shadow">
                                    👑 VIP Exclusive Masterclass
                                </div>
                            @endif

                            <div class="absolute inset-0 flex items-center justify-center">
                                <a href="{{ route('creators.show', $vlog->slug) }}" class="w-16 h-16 rounded-full bg-rose-600 text-white flex items-center justify-center text-2xl shadow-xl hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play ml-1"></i>
                                </a>
                            </div>

                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-[11px] font-bold backdrop-blur-md bg-black/60 px-3 py-1.5 rounded-xl">
                                <span>⏱️ {{ $vlog->prep_time_mins }} mins</span>
                                <span>🔥 {{ $vlog->calories }} kcal</span>
                                <span>💪 {{ $vlog->protein_g }}g Protein</span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 space-y-2">
                            <div class="flex items-center gap-2">
                                <img src="{{ $vlog->creator->avatar_url }}" alt="{{ $vlog->creator->name }}" class="w-5 h-5 rounded-full object-cover">
                                <span class="text-xs font-bold text-gray-600">{{ $vlog->creator->name }}</span>
                            </div>

                            <h3 class="text-lg font-black text-gray-900 font-heading group-hover:text-rose-600 transition">
                                <a href="{{ route('creators.show', $vlog->slug) }}">{{ $vlog->title }}</a>
                            </h3>

                            <p class="text-xs text-gray-500 line-clamp-2">{{ $vlog->description }}</p>

                            <!-- Ingredients preview tag -->
                            @if(!empty($vlog->ingredients))
                                <div class="pt-2 text-[11px] text-gray-600 font-medium">
                                    <span class="font-bold text-gray-800">Key ingredients:</span> {{ implode(', ', array_slice($vlog->ingredients, 0, 3)) }}...
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 1-Click Order Ingredients Button -->
                    <div class="p-6 pt-0 space-y-2">
                        <form action="{{ route('creators.order.ingredients', $vlog->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center justify-center gap-2 shadow-xs">
                                <i class="fa-solid fa-cart-shopping text-rose-600"></i>
                                <span>1-Click Order All Recipe Ingredients</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $recipes->links() }}
        </div>
    </div>

</div>
@endsection
