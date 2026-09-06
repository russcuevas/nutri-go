@extends('layouts.app')

@section('title', $recipe->title . ' | NutriGo Cooking Vlogs')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-gray-500 font-semibold gap-2">
        <a href="{{ route('home') }}" class="hover:text-nutri-600">Home</a>
        <span>/</span>
        <a href="{{ route('creators.index') }}" class="hover:text-nutri-600">Creators & Vlogs</a>
        <span>/</span>
        <span class="text-gray-900 truncate max-w-xs">{{ $recipe->title }}</span>
    </nav>

    <!-- Main Title & Creator Header -->
    <div class="space-y-3">
        <div class="flex items-center gap-2">
            @if($recipe->is_premium_only)
                <span class="px-3 py-1 rounded-full bg-amber-400 text-nutri-950 text-xs font-black uppercase tracking-wider">
                    👑 VIP Exclusive Masterclass
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-extrabold uppercase tracking-wider">
                    🎬 Free Cooking Guide
                </span>
            @endif
            <span class="text-xs text-gray-400 font-medium">• {{ $recipe->views_count }} Views</span>
        </div>

        <h1 class="text-3xl sm:text-4xl font-black font-heading text-gray-900 tracking-tight leading-tight">
            {{ $recipe->title }}
        </h1>

        <div class="flex items-center gap-3 pt-2">
            <img src="{{ $recipe->creator->avatar_url }}" alt="{{ $recipe->creator->name }}" class="w-10 h-10 rounded-2xl object-cover shadow-sm">
            <div>
                <h4 class="text-xs font-bold text-gray-900">{{ $recipe->creator->name }}</h4>
                <p class="text-[10px] text-rose-600 font-extrabold">{{ $recipe->creator->channel_name }}</p>
            </div>
        </div>
    </div>

    <!-- Video Player Section -->
    <div class="rounded-3xl overflow-hidden shadow-2xl bg-black border border-gray-800 relative">
        @if($recipe->is_premium_only && !$isVip)
            <!-- VIP Paywall Overlay -->
            <div class="relative h-96 flex flex-col items-center justify-center p-8 text-center text-white bg-gradient-to-b from-nutri-950/90 to-black">
                <img src="{{ $recipe->thumbnail_url }}" alt="Locked" class="absolute inset-0 w-full h-full object-cover opacity-20 blur-sm">
                <div class="relative z-10 max-w-md space-y-4">
                    <div class="w-16 h-16 rounded-3xl bg-amber-400 text-nutri-950 text-2xl flex items-center justify-center mx-auto shadow-xl">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3 class="text-2xl font-black font-heading">NutriGo VIP Exclusive Video</h3>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        This full masterclass and customized macro guide is exclusive to NutriGo VIP members. Upgrade today to unlock all video recipes!
                    </p>
                    <a href="{{ route('users.subscriptions.index') }}" class="inline-flex px-6 py-3.5 rounded-2xl bg-amber-400 hover:bg-amber-300 text-nutri-950 font-black text-xs shadow-xl transition font-heading transform hover:scale-105">
                        Upgrade to NutriGo VIP for ₱299/mo <i class="fa-solid fa-crown ml-1.5"></i>
                    </a>
                </div>
            </div>
        @else
            <!-- Active Video Stream / Embed -->
            <div class="aspect-video w-full">
                {!! $recipe->video_embed ?? '<iframe width="100%" height="100%" src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" title="Cooking Video" frameborder="0" allowfullscreen class="w-full h-full"></iframe>' !!}
            </div>
        @endif
    </div>

    <!-- Nutrition Facts & Macros Banner -->
    <div class="p-6 rounded-3xl bg-nutri-50 border border-nutri-200 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
        <div>
            <span class="text-[10px] font-extrabold uppercase text-gray-500">Calories</span>
            <div class="text-2xl font-black text-nutri-900 font-heading">🔥 {{ $recipe->calories }} <span class="text-xs font-semibold text-gray-500">kcal</span></div>
        </div>
        <div>
            <span class="text-[10px] font-extrabold uppercase text-gray-500">Protein</span>
            <div class="text-2xl font-black text-rose-600 font-heading">💪 {{ $recipe->protein_g }} <span class="text-xs font-semibold text-gray-500">g</span></div>
        </div>
        <div>
            <span class="text-[10px] font-extrabold uppercase text-gray-500">Carbs</span>
            <div class="text-2xl font-black text-amber-600 font-heading">🍞 {{ $recipe->carbs_g }} <span class="text-xs font-semibold text-gray-500">g</span></div>
        </div>
        <div>
            <span class="text-[10px] font-extrabold uppercase text-gray-500">Healthy Fats</span>
            <div class="text-2xl font-black text-emerald-600 font-heading">🥑 {{ $recipe->fat_g }} <span class="text-xs font-semibold text-gray-500">g</span></div>
        </div>
    </div>

    <!-- Ingredients & Step by Step Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Ingredients + 1-Click Order -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Required Ingredients</h3>
                    <span class="text-xs text-gray-400 font-medium">⏱️ {{ $recipe->prep_time_mins }} mins prep</span>
                </div>

                <ul class="space-y-2.5 text-xs text-gray-700">
                    @if(!empty($recipe->ingredients))
                        @foreach($recipe->ingredients as $ing)
                            <li class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <span>{{ $ing }}</span>
                            </li>
                        @endforeach
                    @endif
                </ul>

                <!-- 1-Click Order Button -->
                <div class="pt-4 border-t border-gray-100">
                    <form action="{{ route('creators.order.ingredients', $recipe->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs shadow-md transition font-heading flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>1-Click Order Ingredients from Store</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Preparation Instructions -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-card space-y-4">
                <h3 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100">
                    Step-by-Step Cooking Guide
                </h3>

                <div class="text-xs text-gray-700 leading-relaxed whitespace-pre-line space-y-2">
                    {{ $recipe->instructions ?? "1. Prepare and measure fresh local Batangas ingredients.\n2. Follow the creator cooking timestamps in the video above.\n3. Garnish and enjoy your healthy, macro-counted meal!" }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
