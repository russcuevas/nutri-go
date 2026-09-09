<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        /** @var User|null $user */
        $user = Auth::user();
        $currentSubscription = $user ? $user->activeSubscription : null;

        return view('users.subscriptions.index', compact('plans', 'user', 'currentSubscription'));
    }

    public function subscribe(Request $request, int $planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        /** @var User|null $user */
        $user = Auth::user();

        $validated = $request->validate([
            'payment_reference' => 'required|string|max:50',
            'payment_proof' => 'nullable|image|max:3072',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('subscriptions/proofs', 'public');
        }

        $durationDays = $plan->billing_period === 'annual' ? 365 : 30;

        UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'payment_reference' => $validated['payment_reference'],
            'payment_proof' => $proofPath,
        ]);

        return redirect()->route('users.subscriptions.index')->with('success', 'Congratulations! You are now a NutriGo VIP member. Enjoy exclusive recipe masterclasses and delivery perks.');
    }
}
