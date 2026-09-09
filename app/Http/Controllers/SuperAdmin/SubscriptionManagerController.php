<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;

class SubscriptionManagerController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount('subscriptions')->get();
        $subscribers = UserSubscription::with(['user', 'plan'])->latest()->paginate(15);
        
        $activeSubscribersCount = UserSubscription::where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })->count();

        $totalRevenue = UserSubscription::where('status', 'active')
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.price');

        return view('superadmin.subscriptions.index', compact('plans', 'subscribers', 'activeSubscribersCount', 'totalRevenue'));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|in:monthly,quarterly,annual',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'perks' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $perksArray = [];
        if (!empty($validated['perks'])) {
            $perksArray = array_filter(array_map('trim', explode("\n", $validated['perks'])));
        }

        SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'price' => $validated['price'],
            'billing_period' => $validated['billing_period'],
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'perks' => $perksArray,
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return back()->with('success', 'Subscription Plan added successfully!');
    }

    public function destroyPlan($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return back()->with('success', 'Subscription Plan removed successfully!');
    }

    public function destroySubscription($id)
    {
        $subscription = UserSubscription::findOrFail($id);
        $subscription->delete();

        return back()->with('success', 'Subscriber record removed successfully!');
    }
}
