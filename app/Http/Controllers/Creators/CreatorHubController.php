<?php

namespace App\Http\Controllers\Creators;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Creator;
use App\Models\RecipeAndVlog;
use App\Models\Product;
use App\Models\User;

class CreatorHubController extends Controller
{
    public function index()
    {
        $creators = Creator::where('is_verified', true)->withCount('recipes')->get();
        
        $recipes = RecipeAndVlog::with(['creator', 'store'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        /** @var User|null $user */
        $user = Auth::user();
        $isVip = $user && $user->isVipSubscriber();

        return view('creators.index', compact('creators', 'recipes', 'isVip'));
    }

    public function show(string $slug)
    {
        $recipe = RecipeAndVlog::where('slug', $slug)->with(['creator', 'store'])->firstOrFail();
        /** @var User|null $user */
        $user = Auth::user();
        $isVip = $user && $user->isVipSubscriber();

        $recipe->increment('views_count');

        $linkedProducts = $recipe->getLinkedProducts();

        $relatedRecipes = RecipeAndVlog::where('id', '!=', $recipe->id)
            ->where('creator_id', $recipe->creator_id)
            ->take(3)
            ->get();

        return view('creators.show', compact('recipe', 'isVip', 'linkedProducts', 'relatedRecipes'));
    }

    public function addRecipeIngredientsToCart(Request $request, int $id)
    {
        $recipe = RecipeAndVlog::findOrFail($id);
        $products = $recipe->getLinkedProducts();

        if ($products->isEmpty()) {
            return back()->with('info', 'No direct store products linked to this recipe yet.');
        }

        $cart = session()->get('cart', []);
        $storeId = $products->first()->store_id;

        session()->put('cart_store_id', $storeId);

        foreach ($products as $product) {
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += 1;
            } else {
                $cart[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => 1,
                    'image' => $product->image_url,
                    'calories' => (int) $product->calories,
                    'protein_g' => (float) $product->protein_g,
                    'carbs_g' => (float) $product->carbs_g,
                    'fat_g' => (float) $product->fat_g,
                    'store_id' => $product->store_id,
                    'store_name' => $product->store->store_name,
                ];
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('users.cart.index')->with('success', 'All healthy recipe ingredients added to your cart from ' . $products->first()->store->store_name . '!');
    }
}
