<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Creator;
use App\Models\RecipeAndVlog;
use App\Models\Store;
use App\Models\Product;

class CreatorManagerController extends Controller
{
    public function index()
    {
        $creators = Creator::withCount('recipes')->get();
        $recipes = RecipeAndVlog::with(['creator', 'store'])->latest()->paginate(15);
        $stores = Store::where('status', 'approved')->get();
        $products = Product::where('is_available', true)->get();

        return view('superadmin.creators.index', compact('creators', 'recipes', 'stores', 'products'));
    }

    public function storeCreator(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'channel_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'specialties' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('creators', 'public');
        }

        Creator::create([
            'name' => $validated['name'],
            'channel_name' => $validated['channel_name'],
            'bio' => $validated['bio'] ?? null,
            'specialties' => $validated['specialties'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'avatar' => $avatarPath,
            'is_verified' => true,
        ]);

        return back()->with('success', 'Partner Health Creator added successfully!');
    }

    public function storeRecipe(Request $request)
    {
        $validated = $request->validate([
            'creator_id' => 'required|exists:creators,id',
            'store_id' => 'nullable|exists:stores,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string',
            'video_embed' => 'nullable|string',
            'prep_time_mins' => 'required|integer|min:1',
            'calories' => 'required|integer|min:0',
            'protein_g' => 'required|numeric|min:0',
            'carbs_g' => 'required|numeric|min:0',
            'fat_g' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_premium_only' => 'boolean',
            'linked_product_ids' => 'nullable|array',
            'thumbnail' => 'nullable|image|max:3072',
        ]);

        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('recipes', 'public');
        }

        // Format ingredients lines into array
        $ingredientsArray = [];
        if (!empty($validated['ingredients'])) {
            $ingredientsArray = array_filter(array_map('trim', explode("\n", $validated['ingredients'])));
        }

        RecipeAndVlog::create([
            'creator_id' => $validated['creator_id'],
            'store_id' => $validated['store_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . rand(100, 999),
            'description' => $validated['description'] ?? null,
            'video_url' => $validated['video_url'],
            'video_embed' => $validated['video_embed'] ?? null,
            'thumbnail' => $thumbPath,
            'prep_time_mins' => $validated['prep_time_mins'],
            'calories' => $validated['calories'],
            'protein_g' => $validated['protein_g'],
            'carbs_g' => $validated['carbs_g'],
            'fat_g' => $validated['fat_g'],
            'ingredients' => $ingredientsArray,
            'instructions' => $validated['instructions'] ?? null,
            'is_premium_only' => $request->boolean('is_premium_only', false),
            'linked_product_ids' => $validated['linked_product_ids'] ?? [],
        ]);

        return back()->with('success', 'Recipe & Cooking Vlog published successfully!');
    }

    public function destroyRecipe($id)
    {
        $recipe = RecipeAndVlog::findOrFail($id);

        if ($recipe->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($recipe->thumbnail)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($recipe->thumbnail);
        }

        $recipe->delete();

        return back()->with('success', 'Recipe & Cooking Vlog deleted successfully!');
    }

    public function destroyCreator($id)
    {
        $creator = Creator::findOrFail($id);

        if ($creator->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($creator->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($creator->avatar);
        }

        // Also delete their recipes' thumbnails and records
        foreach ($creator->recipes as $recipe) {
            if ($recipe->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($recipe->thumbnail)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($recipe->thumbnail);
            }
            $recipe->delete();
        }

        $creator->delete();

        return back()->with('success', 'Partner Creator and associated recipes deleted successfully!');
    }
}
