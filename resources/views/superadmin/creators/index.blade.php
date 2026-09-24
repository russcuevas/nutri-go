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
        @if($creators->isEmpty())
            <div class="text-center py-6 text-gray-400 text-xs">
                <i class="fa-solid fa-users text-2xl mb-2 text-gray-300 block"></i>
                No partner creators added yet. Click "+ Add Creator" to get started.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($creators as $c)
                    <div x-data="{ openEditCreator: false }" class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between gap-3 group hover:border-gray-200 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $c->avatar_url }}" alt="{{ $c->name }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                            <div class="text-xs min-w-0">
                                <span class="font-bold text-gray-900 block truncate">{{ $c->name }}</span>
                                <span class="text-rose-600 font-semibold truncate block">{{ $c->channel_name }}</span>
                                <span class="text-gray-400 text-[10px] block">{{ $c->recipes_count }} Recipes</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <!-- Edit Creator Button -->
                            <button type="button" @click="openEditCreator = true" class="p-2 rounded-xl text-gray-400 hover:text-nutri-700 hover:bg-nutri-50 transition cursor-pointer" title="Edit Creator">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            
                            <!-- Delete Creator Form -->
                            <form action="{{ route('superadmin.creators.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete creator {{ addslashes($c->name) }} and all their recipes?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Delete Creator">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Edit Creator Modal -->
                        <div x-show="openEditCreator" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs text-left">
                            <div @click.outside="openEditCreator = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
                                <!-- Header -->
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-nutri-100 text-nutri-700 flex items-center justify-center text-sm font-bold">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black font-heading text-base text-gray-900">Edit Partner Creator</h4>
                                            <p class="text-[11px] text-gray-500">Update creator details and social channels</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="openEditCreator = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>

                                <!-- Form Body -->
                                <form action="{{ route('superadmin.creators.update', $c->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Creator Full Name <span class="text-rose-500">*</span></label>
                                            <input type="text" name="name" value="{{ $c->name }}" required placeholder="e.g. Coach Mika Santos" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Channel / Handle Name <span class="text-rose-500">*</span></label>
                                            <input type="text" name="channel_name" value="{{ $c->channel_name }}" required placeholder="e.g. Mika Fits & Eats" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Bio & Specialties</label>
                                            <textarea name="bio" rows="2" placeholder="Certified Nutritionist, Lipa Healthy Meal Prep Enthusiast" class="w-full p-3 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">{{ $c->bio }}</textarea>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                            <div>
                                                <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-youtube text-red-500 mr-1"></i>YouTube URL</label>
                                                <input type="url" name="youtube_url" value="{{ $c->youtube_url }}" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-tiktok text-gray-800 mr-1"></i>TikTok URL</label>
                                                <input type="url" name="tiktok_url" value="{{ $c->tiktok_url }}" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                                            </div>
                                            <div>
                                                <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-instagram text-pink-500 mr-1"></i>Instagram URL</label>
                                                <input type="url" name="instagram_url" value="{{ $c->instagram_url }}" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Avatar / Profile Photo (Optional to replace)</label>
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $c->avatar_url }}" alt="Preview" class="w-10 h-10 rounded-xl object-cover border border-gray-200 shrink-0">
                                                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="openEditCreator = false" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold text-xs transition">Cancel</button>
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Published Recipes List -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-black font-heading text-gray-900 uppercase tracking-wider">Published Recipes & Vlogs ({{ $recipes->total() }})</h4>
        </div>
        @if($recipes->isEmpty())
            <div class="text-center py-10 text-gray-400 text-xs">
                <i class="fa-solid fa-video-slash text-3xl mb-2.5 text-gray-300 block"></i>
                No recipes published yet. Click "Publish Recipe Vlog" to add one.
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($recipes as $v)
                    <div x-data="{ openEditRecipe: false }" class="py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition">
                        <!-- Thumbnail & Info -->
                        <div class="flex items-start sm:items-center gap-4 min-w-0">
                            <div class="relative shrink-0 w-20 h-16 sm:w-24 sm:h-18 rounded-2xl overflow-hidden bg-gray-100 border border-gray-100 shadow-xs">
                                <img src="{{ $v->thumbnail_url }}" alt="{{ $v->title }}" class="w-full h-full object-cover">
                                @if($v->is_premium_only)
                                    <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded-md bg-amber-500 text-white font-extrabold text-[9px] shadow-xs">VIP</span>
                                @endif
                            </div>
                            
                            <div class="space-y-1.5 min-w-0">
                                <h5 class="font-bold text-gray-900 text-sm sm:text-base leading-snug line-clamp-1 group-hover:text-nutri-900 transition">
                                    {{ $v->title }}
                                </h5>
                                
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1 font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-lg text-[11px]">
                                        <i class="fa-solid fa-user text-[10px] text-nutri-700"></i>
                                        {{ $v->creator->name ?? 'Partner Creator' }}
                                    </span>
                                    
                                    <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg text-[11px]">
                                        <i class="fa-solid fa-fire text-[10px] text-amber-500"></i>
                                        {{ $v->calories }} kcal
                                    </span>

                                    <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded-lg text-[11px] {{ $v->is_premium_only ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $v->is_premium_only ? '👑 VIP Only' : '✨ Free Access' }}
                                    </span>

                                    @if($v->is_local_video)
                                        <span class="inline-flex items-center gap-1 font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg text-[11px]" title="{{ $v->video_url }}">
                                            <i class="fa-solid fa-file-video text-[10px] text-indigo-500"></i>
                                            Local Video
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 font-bold text-rose-700 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg text-[11px]" title="{{ $v->video_url }}">
                                            <i class="fa-brands fa-youtube text-[10px] text-rose-500"></i>
                                            Video Link
                                        </span>
                                    @endif

                                    @if($v->store)
                                        <span class="inline-flex items-center gap-1 text-gray-500 bg-gray-50 border border-gray-200/60 px-2 py-0.5 rounded-lg text-[11px]">
                                            <i class="fa-solid fa-store text-[10px] text-gray-400"></i>
                                            {{ $v->store->store_name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-[11px] text-gray-400 flex items-center gap-3">
                                    <span><i class="fa-regular fa-clock mr-1"></i> {{ $v->prep_time_mins }} mins prep</span>
                                    <span>•</span>
                                    <span>Protein: <strong class="text-gray-600">{{ $v->protein_g }}g</strong></span>
                                    <span>•</span>
                                    <span>Carbs: <strong class="text-gray-600">{{ $v->carbs_g }}g</strong></span>
                                    <span>•</span>
                                    <span>Fats: <strong class="text-gray-600">{{ $v->fat_g }}g</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions & Views -->
                        <div class="flex items-center justify-between sm:justify-end gap-2.5 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs">
                                <i class="fa-regular fa-eye text-gray-400"></i>
                                {{ number_format($v->views_count) }}
                            </span>

                            <!-- Edit Recipe Button -->
                            <button type="button" @click="openEditRecipe = true" class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-gray-700 hover:text-nutri-800 hover:bg-nutri-50 border border-gray-200 hover:border-nutri-200 transition flex items-center gap-1 text-xs font-bold cursor-pointer" title="Edit Recipe">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            
                            <!-- Delete Recipe Form -->
                            <form action="{{ route('superadmin.creators.recipe.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this recipe: {{ addslashes($v->title) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition flex items-center gap-1 text-xs font-bold cursor-pointer" title="Delete Recipe">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </button>
                            </form>
                        </div>

                        <!-- Edit Recipe Modal -->
                        <div x-show="openEditRecipe" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-xs text-left">
                            <div @click.outside="openEditRecipe = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh] border border-gray-100">
                                <!-- Header -->
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-nutri-100 text-nutri-700 flex items-center justify-center text-sm font-bold">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black font-heading text-base text-gray-900">Edit Cooking Vlog / Recipe</h4>
                                            <p class="text-[11px] text-gray-500">Update vlog title, video file/link, macros, or linked store</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="openEditRecipe = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>

                                <!-- Form Body -->
                                <form action="{{ route('superadmin.creators.recipe.update', $v->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                                        <!-- Creator & Store Selection -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-1">Creator <span class="text-rose-500">*</span></label>
                                                <select name="creator_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                                    @foreach($creators as $cOption)
                                                        <option value="{{ $cOption->id }}" {{ $cOption->id == $v->creator_id ? 'selected' : '' }}>{{ $cOption->name }} ({{ $cOption->channel_name }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-1">Partner Lipa Store</label>
                                                <select name="store_id" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                                    <option value="" {{ is_null($v->store_id) ? 'selected' : '' }}>None (Generic Recipe)</option>
                                                    @foreach($stores as $st)
                                                        <option value="{{ $st->id }}" {{ $st->id == $v->store_id ? 'selected' : '' }}>{{ $st->store_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Recipe / Vlog Title <span class="text-rose-500">*</span></label>
                                            <input type="text" name="title" value="{{ $v->title }}" required placeholder="e.g. 5-Minute Post-Workout High Protein Chicken Bowl" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                        </div>

                                        <!-- Video Upload or Link Selection -->
                                        <div x-data="{ videoSource: '{{ $v->is_local_video ? 'file' : 'link' }}' }" class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <label class="block font-bold text-gray-800 text-xs">Recipe Video</label>
                                                    <span class="text-[10px] text-gray-500">
                                                        Current: 
                                                        @if($v->is_local_video)
                                                            <span class="text-indigo-600 font-bold"><i class="fa-solid fa-file-video mr-1"></i>{{ $v->video_url }}</span>
                                                        @else
                                                            <span class="text-rose-600 font-bold"><i class="fa-brands fa-youtube mr-1"></i>{{ Str::limit($v->video_url, 35) }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center bg-gray-200/80 p-0.5 rounded-xl text-[11px] font-bold">
                                                    <button type="button" @click="videoSource = 'file'" :class="videoSource === 'file' ? 'bg-white text-nutri-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                                        <i class="fa-solid fa-cloud-arrow-up"></i> Video File
                                                    </button>
                                                    <button type="button" @click="videoSource = 'link'" :class="videoSource === 'link' ? 'bg-white text-nutri-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                                        <i class="fa-solid fa-link"></i> Video Link
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- File Upload Option -->
                                            <div x-show="videoSource === 'file'" class="space-y-2">
                                                <div class="border-2 border-dashed border-gray-200 hover:border-nutri-400 bg-white rounded-2xl p-4 text-center transition">
                                                    <i class="fa-solid fa-file-video text-2xl text-nutri-600 mb-1"></i>
                                                    <p class="text-xs font-bold text-gray-700">Upload new video file (leave empty to keep current)</p>
                                                    <p class="text-[10px] text-gray-400 mb-2">Stored in <code class="text-nutri-700 bg-nutri-50 px-1 py-0.5 rounded">images/creators/videos</code> (MP4, WebM, MOV up to 100MB)</p>
                                                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                                                </div>
                                            </div>

                                            <!-- Link Option -->
                                            <div x-show="videoSource === 'link'" class="space-y-1.5">
                                                <label class="block font-semibold text-gray-600 text-[11px]">YouTube / Video Link URL</label>
                                                <div class="relative">
                                                    <i class="fa-brands fa-youtube absolute left-3.5 top-1/2 -translate-y-1/2 text-red-500 text-sm"></i>
                                                    <input type="text" name="video_url" value="{{ !$v->is_local_video ? $v->video_url : '' }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-white text-xs transition">
                                                </div>
                                                <p class="text-[10px] text-gray-400">Leave blank if keeping current uploaded file or enter a new link</p>
                                            </div>
                                        </div>

                                        <!-- Macronutrients Grid Card -->
                                        <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                            <label class="block font-bold text-gray-800 mb-2 text-[11px] uppercase tracking-wider">Nutritional Breakdown & Prep</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                                <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                                    <label class="block font-semibold text-gray-500 text-[10px] uppercase">Prep Time</label>
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <input type="number" name="prep_time_mins" value="{{ $v->prep_time_mins }}" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                                        <span class="text-[10px] text-gray-400 font-medium">min</span>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                                    <label class="block font-semibold text-amber-600 text-[10px] uppercase">Calories</label>
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <input type="number" name="calories" value="{{ $v->calories }}" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                                        <span class="text-[10px] text-gray-400 font-medium">kcal</span>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                                    <label class="block font-semibold text-emerald-600 text-[10px] uppercase">Protein</label>
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <input type="number" step="0.1" name="protein_g" value="{{ $v->protein_g }}" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                                        <span class="text-[10px] text-gray-400 font-medium">g</span>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                                    <label class="block font-semibold text-blue-600 text-[10px] uppercase">Carbs</label>
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <input type="number" step="0.1" name="carbs_g" value="{{ $v->carbs_g }}" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                                        <span class="text-[10px] text-gray-400 font-medium">g</span>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                                    <label class="block font-semibold text-rose-600 text-[10px] uppercase">Fats</label>
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <input type="number" step="0.1" name="fat_g" value="{{ $v->fat_g }}" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                                        <span class="text-[10px] text-gray-400 font-medium">g</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ingredients & Instructions Side-by-Side on Desktop -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-1">Ingredients (1 per line)</label>
                                                <textarea name="ingredients" rows="3" class="w-full p-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition" placeholder="200g Lean Chicken Breast&#10;1 cup Broccoli&#10;1 tbsp Olive Oil">{{ is_array($v->ingredients) ? implode("\n", $v->ingredients) : $v->ingredients }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-1">Step-by-Step Instructions</label>
                                                <textarea name="instructions" rows="3" class="w-full p-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition" placeholder="1. Season chicken...&#10;2. Sear on pan for 5 mins...">{{ $v->instructions }}</textarea>
                                            </div>
                                        </div>

                                        <!-- Options & Thumbnail -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 items-center">
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-1">Thumbnail Photo (Optional to replace)</label>
                                                <div class="flex items-center gap-3">
                                                    @if($v->thumbnail)
                                                        <img src="{{ $v->thumbnail_url }}" alt="Thumbnail" class="w-12 h-10 rounded-xl object-cover border border-gray-200 shrink-0">
                                                    @endif
                                                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                                                </div>
                                            </div>
                                            <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl flex items-center gap-3">
                                                <input type="checkbox" id="edit_is_premium_{{ $v->id }}" name="is_premium_only" value="1" {{ $v->is_premium_only ? 'checked' : '' }} class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-gray-300">
                                                <label for="edit_is_premium_{{ $v->id }}" class="cursor-pointer">
                                                    <span class="font-bold text-amber-900 block text-xs">👑 VIP Exclusive Content</span>
                                                    <span class="text-[10px] text-amber-700">Requires VIP subscription to view full video & recipe</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="openEditRecipe = false" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold text-xs transition">Cancel</button>
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-4 border-t border-gray-100">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>

    <!-- Add Creator Modal -->
    <div x-show="openCreatorModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.outside="openCreatorModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-nutri-100 text-nutri-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h4 class="font-black font-heading text-base text-gray-900">Add Partner Content Creator</h4>
                        <p class="text-[11px] text-gray-500">Register a new health & fitness influencer</p>
                    </div>
                </div>
                <button type="button" @click="openCreatorModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form action="{{ route('superadmin.creators.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Creator Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Coach Mika Santos" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Channel / Handle Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="channel_name" required placeholder="e.g. Mika Fits & Eats" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Bio & Specialties</label>
                        <textarea name="bio" rows="2" placeholder="Certified Nutritionist, Lipa Healthy Meal Prep Enthusiast" class="w-full p-3 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-youtube text-red-500 mr-1"></i>YouTube URL</label>
                            <input type="url" name="youtube_url" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-tiktok text-gray-800 mr-1"></i>TikTok URL</label>
                            <input type="url" name="tiktok_url" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1 text-[11px]"><i class="fa-brands fa-instagram text-pink-500 mr-1"></i>Instagram URL</label>
                            <input type="url" name="instagram_url" placeholder="https://..." class="w-full px-2.5 py-2 rounded-xl border border-gray-200 focus:border-nutri-500 bg-gray-50/50 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Avatar / Profile Photo</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openCreatorModal = false" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold text-xs transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition">Add Creator</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Recipe Modal -->
    <div x-show="openRecipeModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-xs">
        <div @click.outside="openRecipeModal = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh] border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-nutri-100 text-nutri-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div>
                        <h4 class="font-black font-heading text-base text-gray-900">Publish Cooking Vlog / Recipe</h4>
                        <p class="text-[11px] text-gray-500">Post educational recipes and link partner store dishes</p>
                    </div>
                </div>
                <button type="button" @click="openRecipeModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Form Body -->
            <form action="{{ route('superadmin.creators.recipe.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                    <!-- Creator & Store Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Creator <span class="text-rose-500">*</span></label>
                            <select name="creator_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                @foreach($creators as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->channel_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Partner Lipa Store</label>
                            <select name="store_id" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                                <option value="">None (Generic Recipe)</option>
                                @foreach($stores as $st)
                                    <option value="{{ $st->id }}">{{ $st->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Recipe / Vlog Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. 5-Minute Post-Workout High Protein Chicken Bowl" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition">
                    </div>

                    <!-- Video Upload or Link Selection -->
                    <div x-data="{ videoSource: 'file' }" class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block font-bold text-gray-800 text-xs">Recipe Video <span class="text-rose-500">*</span></label>
                            <div class="flex items-center bg-gray-200/80 p-0.5 rounded-xl text-[11px] font-bold">
                                <button type="button" @click="videoSource = 'file'" :class="videoSource === 'file' ? 'bg-white text-nutri-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
                                </button>
                                <button type="button" @click="videoSource = 'link'" :class="videoSource === 'link' ? 'bg-white text-nutri-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-link"></i> Video Link / URL
                                </button>
                            </div>
                        </div>

                        <!-- File Upload Option -->
                        <div x-show="videoSource === 'file'" class="space-y-2">
                            <div class="border-2 border-dashed border-gray-200 hover:border-nutri-400 bg-white rounded-2xl p-4 text-center transition">
                                <i class="fa-solid fa-file-video text-2xl text-nutri-600 mb-1"></i>
                                <p class="text-xs font-bold text-gray-700">Choose a video file from your computer</p>
                                <p class="text-[10px] text-gray-400 mb-2">Stored directly in <code class="text-nutri-700 bg-nutri-50 px-1 py-0.5 rounded">images/creators/videos</code> (MP4, WebM, MOV up to 100MB)</p>
                                <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                            </div>
                        </div>

                        <!-- Link Option -->
                        <div x-show="videoSource === 'link'" class="space-y-1.5">
                            <label class="block font-semibold text-gray-600 text-[11px]">YouTube / Video Link URL</label>
                            <div class="relative">
                                <i class="fa-brands fa-youtube absolute left-3.5 top-1/2 -translate-y-1/2 text-red-500 text-sm"></i>
                                <input type="text" name="video_url" placeholder="https://www.youtube.com/watch?v=... or any web video link" class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-white text-xs transition">
                            </div>
                            <p class="text-[10px] text-gray-400">Paste a YouTube, Vimeo, or web video stream link</p>
                        </div>
                    </div>

                    <!-- Macronutrients Grid Card -->
                    <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <label class="block font-bold text-gray-800 mb-2 text-[11px] uppercase tracking-wider">Nutritional Breakdown & Prep</label>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                <label class="block font-semibold text-gray-500 text-[10px] uppercase">Prep Time</label>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <input type="number" name="prep_time_mins" value="15" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                    <span class="text-[10px] text-gray-400 font-medium">min</span>
                                </div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                <label class="block font-semibold text-amber-600 text-[10px] uppercase">Calories</label>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <input type="number" name="calories" value="380" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                    <span class="text-[10px] text-gray-400 font-medium">kcal</span>
                                </div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                <label class="block font-semibold text-emerald-600 text-[10px] uppercase">Protein</label>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <input type="number" step="0.1" name="protein_g" value="30" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                    <span class="text-[10px] text-gray-400 font-medium">g</span>
                                </div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                <label class="block font-semibold text-blue-600 text-[10px] uppercase">Carbs</label>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <input type="number" step="0.1" name="carbs_g" value="25" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                    <span class="text-[10px] text-gray-400 font-medium">g</span>
                                </div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-gray-100 shadow-2xs">
                                <label class="block font-semibold text-rose-600 text-[10px] uppercase">Fats</label>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <input type="number" step="0.1" name="fat_g" value="10" required class="w-full font-bold text-gray-800 text-xs border-0 p-0 focus:ring-0">
                                    <span class="text-[10px] text-gray-400 font-medium">g</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients & Instructions Side-by-Side on Desktop -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Ingredients (1 per line)</label>
                            <textarea name="ingredients" rows="3" class="w-full p-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition" placeholder="200g Lean Chicken Breast&#10;1 cup Broccoli&#10;1 tbsp Olive Oil"></textarea>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Step-by-Step Instructions</label>
                            <textarea name="instructions" rows="3" class="w-full p-2.5 rounded-xl border border-gray-200 focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/20 bg-gray-50/50 focus:bg-white text-xs transition" placeholder="1. Season chicken...&#10;2. Sear on pan for 5 mins..."></textarea>
                        </div>
                    </div>

                    <!-- Options & Thumbnail -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 items-center">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Thumbnail Photo</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-nutri-50 file:text-nutri-700 hover:file:bg-nutri-100 transition cursor-pointer">
                        </div>
                        <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl flex items-center gap-3">
                            <input type="checkbox" id="is_premium_only" name="is_premium_only" value="1" class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-gray-300">
                            <label for="is_premium_only" class="cursor-pointer">
                                <span class="font-bold text-amber-900 block text-xs">👑 VIP Exclusive Content</span>
                                <span class="text-[10px] text-amber-700">Requires VIP subscription to view full video & recipe</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openRecipeModal = false" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold text-xs transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition">Publish Recipe</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
