@extends('layouts.admin')

@section('title', 'Creator Partnerships & Cooking Vlogs | Super Admin')
@section('header_title', 'Partner Creators & Cooking Vlogs')

@section('content')
<div class="space-y-8" x-data="{ openCreatorModal: false, openRecipeModal: false }">
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Health Vloggers & Cooking Content</h3>
            <p class="text-xs text-gray-500">Manage creator partnerships and link their recipes to Lipa store products</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="openCreatorModal = true" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus"></i> Add Creator
            </button>
            <button @click="openRecipeModal = true" class="px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-video"></i> Publish Recipe Vlog
            </button>
        </div>
    </div>

    <!-- Creators List -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <h4 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Active Partner Creators ({{ $creators->count() }})</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($creators as $c)
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center gap-3">
                    <img src="{{ $c->avatar_url }}" alt="{{ $c->name }}" class="w-12 h-12 rounded-xl object-cover">
                    <div class="text-xs">
                        <span class="font-bold text-gray-900 block">{{ $c->name }}</span>
                        <span class="text-rose-600 font-semibold">{{ $c->channel_name }}</span>
                        <span class="text-gray-400 text-[10px] block">{{ $c->recipes_count }} Recipes</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Published Recipes List -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <h4 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Published Recipes & Vlogs</h4>
        <div class="divide-y divide-gray-100 text-xs">
            @foreach($recipes as $v)
                <div class="py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $v->thumbnail_url }}" alt="{{ $v->title }}" class="w-16 h-12 rounded-xl object-cover">
                        <div>
                            <span class="font-bold text-gray-900 block text-sm">{{ $v->title }}</span>
                            <span class="text-gray-500">By {{ $v->creator->name }} • 🔥 {{ $v->calories }} kcal • {{ $v->is_premium_only ? '👑 VIP Only' : 'Free' }}</span>
                        </div>
                    </div>
                    <span class="font-bold text-gray-400">{{ $v->views_count }} Views</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Creator Modal -->
    <div x-show="openCreatorModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="openCreatorModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 text-xs">
            <h4 class="font-black font-heading text-base text-gray-900">Add Partner Content Creator</h4>
            <form action="{{ route('superadmin.creators.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Creator Full Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-xl border">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Channel / Handle Name *</label>
                    <input type="text" name="channel_name" required class="w-full px-3 py-2 rounded-xl border">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Bio / Specialties</label>
                    <textarea name="bio" rows="2" class="w-full p-2.5 rounded-xl border"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Avatar Image</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-xs">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-nutri-900 text-white font-bold">Add Creator</button>
                    <button type="button" @click="openCreatorModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Recipe Modal -->
    <div x-show="openRecipeModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="openRecipeModal = false" class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4 text-xs max-h-[90vh] overflow-y-auto">
            <h4 class="font-black font-heading text-base text-gray-900">Publish Cooking Vlog / Recipe</h4>
            <form action="{{ route('superadmin.creators.recipe.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Creator *</label>
                        <select name="creator_id" required class="w-full px-3 py-2 rounded-xl border bg-white">
                            @foreach($creators as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Partner Lipa Store</label>
                        <select name="store_id" class="w-full px-3 py-2 rounded-xl border bg-white">
                            <option value="">None</option>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Recipe Title *</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 rounded-xl border">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">YouTube Video URL *</label>
                    <input type="text" name="video_url" required placeholder="https://www.youtube.com/watch?v=..." class="w-full px-3 py-2 rounded-xl border">
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Prep (mins)</label>
                        <input type="number" name="prep_time_mins" value="15" required class="w-full px-2 py-1.5 rounded-lg border">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Calories</label>
                        <input type="number" name="calories" value="380" required class="w-full px-2 py-1.5 rounded-lg border">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Protein (g)</label>
                        <input type="number" step="0.1" name="protein_g" value="30" required class="w-full px-2 py-1.5 rounded-lg border">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Carbs (g)</label>
                        <input type="number" step="0.1" name="carbs_g" value="25" required class="w-full px-2 py-1.5 rounded-lg border">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Ingredients (1 per line)</label>
                    <textarea name="ingredients" rows="3" class="w-full p-2.5 rounded-xl border" placeholder="200g Lean Chicken Breast&#10;1 cup Broccoli&#10;1 tbsp Olive Oil"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Step-by-Step Instructions</label>
                    <textarea name="instructions" rows="3" class="w-full p-2.5 rounded-xl border" placeholder="1. Season chicken...&#10;2. Sear on pan for 5 mins..."></textarea>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <label class="flex items-center gap-2 font-bold text-amber-700">
                        <input type="checkbox" name="is_premium_only" value="1" class="rounded text-amber-500">
                        <span>VIP Exclusive Video (Paywall)</span>
                    </label>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Thumbnail Photo</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs">
                </div>

                <div class="flex gap-2 pt-3">
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-nutri-900 text-white font-bold">Publish Recipe</button>
                    <button type="button" @click="openRecipeModal = false" class="px-4 py-3 rounded-xl bg-gray-100 text-gray-600 font-bold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
