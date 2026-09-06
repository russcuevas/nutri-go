<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Services\LipaLocationService;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('order_num', 'asc')->get();
        $barangays = LipaLocationService::getBarangayList();

        $selectedCategory = $request->get('category');
        $searchQuery = $request->get('q');
        $calorieFilter = $request->get('calorie_range');
        $selectedDiet = $request->get('diet');
        $selectedBarangay = $request->get('barangay');
        $onlySupplements = $request->boolean('supplements');

        // Stores query
        $storesQuery = Store::where('status', 'approved')
            ->where('is_open', true)
            ->withCount('products')
            ->when($selectedBarangay, function($q, $b) {
                $q->where('barangay', $b);
            });

        $stores = $storesQuery->get();

        // Products query
        $productsQuery = Product::where('is_available', true)
            ->whereHas('store', function($q) use ($selectedBarangay) {
                $q->where('status', 'approved');
                if ($selectedBarangay) {
                    $q->where('barangay', $selectedBarangay);
                }
            })
            ->with(['store', 'category', 'alternativeTo']);

        if ($onlySupplements) {
            $productsQuery->where('is_supplement', true);
        }

        if ($selectedCategory) {
            $categoryModel = Category::where('slug', $selectedCategory)->first();
            if ($categoryModel) {
                $productsQuery->where('category_id', $categoryModel->id);
            }
        }

        if ($searchQuery) {
            $productsQuery->where(function($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhereHas('store', function($sq) use ($searchQuery) {
                      $sq->where('store_name', 'like', "%{$searchQuery}%");
                  });
            });
        }

        if ($calorieFilter) {
            if ($calorieFilter === 'under300') {
                $productsQuery->where('calories', '<', 300);
            } elseif ($calorieFilter === '300to500') {
                $productsQuery->whereBetween('calories', [300, 500]);
            } elseif ($calorieFilter === 'over500') {
                $productsQuery->where('calories', '>', 500);
            }
        }

        if ($selectedDiet) {
            $productsQuery->whereJsonContains('dietary_tags', $selectedDiet);
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('users.explore.index', compact(
            'categories',
            'barangays',
            'stores',
            'products',
            'selectedCategory',
            'searchQuery',
            'calorieFilter',
            'selectedDiet',
            'selectedBarangay',
            'onlySupplements'
        ));
    }

    public function showStore(string $slug)
    {
        $store = Store::where('slug', $slug)
            ->where('status', 'approved')
            ->with(['products' => function($q) {
                $q->where('is_available', true)->with('category', 'alternativeTo');
            }, 'reviews.user'])
            ->firstOrFail();

        $barangays = LipaLocationService::getBarangayList();

        return view('users.explore.store', compact('store', 'barangays'));
    }

    public function showProduct(int $id)
    {
        $product = Product::with(['store', 'category', 'alternativeTo', 'alternatives.store'])->findOrFail($id);
        
        $suggestedAlternative = null;
        if ($product->alternativeTo) {
            $suggestedAlternative = $product->alternativeTo;
        } elseif ($product->alternatives->isNotEmpty()) {
            $suggestedAlternative = $product->alternatives->first();
        } else {
            $suggestedAlternative = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_available', true)
                ->where('calories', '<', $product->calories)
                ->first();
        }

        return view('users.explore.product_modal', compact('product', 'suggestedAlternative'));
    }

    public function apiCalculateFee(Request $request)
    {
        $storeId = $request->get('store_id');
        $customerLat = (float) $request->get('lat');
        $customerLng = (float) $request->get('lng');
        $barangay = $request->get('barangay');

        $store = Store::find($storeId);
        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        if (!$customerLat || !$customerLng) {
            $coords = LipaLocationService::getCoordinates($barangay ?? 'Sabang');
            $customerLat = $coords['lat'];
            $customerLng = $coords['lng'];
        }

        $distanceKm = LipaLocationService::calculateDistance($store->latitude, $store->longitude, $customerLat, $customerLng);
        $feeData = LipaLocationService::calculateDeliveryFee($distanceKm);

        return response()->json([
            'success' => true,
            'store_name' => $store->store_name,
            'store_barangay' => $store->barangay,
            'store_coords' => ['lat' => $store->latitude, 'lng' => $store->longitude],
            'customer_coords' => ['lat' => $customerLat, 'lng' => $customerLng],
            'fee_breakdown' => $feeData,
        ]);
    }
}
