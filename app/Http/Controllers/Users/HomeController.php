<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Creator;
use App\Models\RecipeAndVlog;
use App\Models\SubscriptionPlan;
use App\Services\LipaLocationService;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('order_num', 'asc')->get();
        
        $featuredStores = Store::where('status', 'approved')
            ->where('is_open', true)
            ->withCount('products')
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        $healthyChoices = Product::where('is_healthy_choice', true)
            ->where('is_available', true)
            ->whereHas('store', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['store', 'category'])
            ->take(6)
            ->get();

        $creators = Creator::where('is_verified', true)
            ->withCount('recipes')
            ->take(3)
            ->get();

        $featuredRecipes = RecipeAndVlog::with(['creator', 'store'])
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();

        $subscriptionPlans = SubscriptionPlan::all();
        $barangays = LipaLocationService::getBarangayList();

        return view('users.home.index', compact(
            'categories',
            'featuredStores',
            'healthyChoices',
            'creators',
            'featuredRecipes',
            'subscriptionPlans',
            'barangays'
        ));
    }
}
