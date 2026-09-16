<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerReview;

class CustomerReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $rating = $request->get('rating', 'all');
        $search = $request->get('search', '');

        $query = CustomerReview::latest();

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($rating !== 'all' && is_numeric($rating)) {
            $query->where('rating', (int) $rating);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate(12)->withQueryString();

        // Statistics
        $totalReviews = CustomerReview::count();
        $activeReviewsCount = CustomerReview::where('is_active', true)->count();
        $inactiveReviewsCount = CustomerReview::where('is_active', false)->count();
        $avgRating = CustomerReview::avg('rating') ? round(CustomerReview::avg('rating'), 1) : 5.0;

        return view('superadmin.reviews.index', compact(
            'reviews',
            'status',
            'rating',
            'search',
            'totalReviews',
            'activeReviewsCount',
            'inactiveReviewsCount',
            'avgRating'
        ));
    }

    public function toggleActive($id)
    {
        $review = CustomerReview::findOrFail($id);
        $review->is_active = !$review->is_active;
        $review->save();

        $statusMsg = $review->is_active
            ? "Review by {$review->name} is now ACTIVE and will be displayed on the website home section."
            : "Review by {$review->name} is now HIDDEN (inactive).";

        return back()->with('success', $statusMsg);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'contact' => 'nullable|string|max:30',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        CustomerReview::create($validated);

        return back()->with('success', 'New customer review successfully added.');
    }

    public function destroy($id)
    {
        $review = CustomerReview::findOrFail($id);
        $name = $review->name;
        $review->delete();

        return back()->with('success', "Review from {$name} has been deleted.");
    }
}
