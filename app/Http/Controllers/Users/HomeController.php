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
use App\Models\CustomerReview;
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

        $allStores = Store::where('status', 'approved')
            ->withCount('products')
            ->orderBy('rating', 'desc')
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

        // Customer Reviews displayed on website (is_active = 1)
        $customerReviews = CustomerReview::where('is_active', true)
            ->latest()
            ->get();

        $totalActiveReviews = $customerReviews->count();
        $avgCustomerRating = $totalActiveReviews > 0 ? round($customerReviews->avg('rating'), 1) : 5.0;

        return view('users.home.index', compact(
            'categories',
            'featuredStores',
            'allStores',
            'healthyChoices',
            'creators',
            'featuredRecipes',
            'subscriptionPlans',
            'barangays',
            'customerReviews',
            'totalActiveReviews',
            'avgCustomerRating'
        ));
    }

    public function submitReview(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'contact' => 'nullable|string|max:30',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:5|max:1500',
        ]);

        // When submitted by public, save with is_active = false for superadmin approval & moderation
        $validated['is_active'] = false;

        CustomerReview::create($validated);

        return back()->with('success', 'Thank you for your feedback! Your review has been submitted to management for verification and display.');
    }

    public function howItWorks()
    {
        return view('docs.how_it_works');
    }
}
