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
        $selectedStore = $request->get('store');
        $onlySupplements = $request->boolean('supplements');

        $userLat = $request->get('user_lat');
        $userLng = $request->get('user_lng');
        $nearMe = $request->boolean('near_me') && is_numeric($userLat) && is_numeric($userLng);

        // All approved stores for the filter dropdown
        $allStoresQuery = Store::where('status', 'approved');
        if ($nearMe) {
            $lat = (float) $userLat;
            $lng = (float) $userLng;
            $allStores = $allStoresQuery
                ->select('stores.*')
                ->selectRaw('(6371 * acos(LEAST(1.0, GREATEST(-1.0, cos(radians(?)) * cos(radians(COALESCE(latitude, 13.9419))) * cos(radians(COALESCE(longitude, 121.1631)) - radians(?)) + sin(radians(?)) * sin(radians(COALESCE(latitude, 13.9419))))))) AS distance_km', [$lat, $lng, $lat])
                ->orderBy('distance_km', 'asc')
                ->get();
        } else {
            $allStores = $allStoresQuery->orderBy('store_name', 'asc')->get();
        }

        // Stores query for listings
        $storesQuery = Store::where('status', 'approved')
            ->where('is_open', true)
            ->withCount('products');

        $stores = $storesQuery->get();

        // Products query
        $productsQuery = Product::where('is_available', true)
            ->whereHas('store', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['store', 'category', 'alternativeTo']);

        if ($nearMe) {
            $lat = (float) $userLat;
            $lng = (float) $userLng;
            $productsQuery->select('products.*')
                ->join('stores', 'stores.id', '=', 'products.store_id')
                ->selectRaw('(6371 * acos(LEAST(1.0, GREATEST(-1.0, cos(radians(?)) * cos(radians(COALESCE(stores.latitude, 13.9419))) * cos(radians(COALESCE(stores.longitude, 121.1631)) - radians(?)) + sin(radians(?)) * sin(radians(COALESCE(stores.latitude, 13.9419))))))) AS distance_km', [$lat, $lng, $lat])
                ->orderBy('distance_km', 'asc');
        } else {
            $productsQuery->latest('products.created_at');
        }

        if ($selectedStore) {
            $productsQuery->where('products.store_id', $selectedStore);
        }

        if ($onlySupplements) {
            $productsQuery->where('products.is_supplement', true);
        }

        if ($selectedCategory) {
            $categoryModel = Category::where('slug', $selectedCategory)->first();
            if ($categoryModel) {
                $productsQuery->where('products.category_id', $categoryModel->id);
            }
        }

        if ($searchQuery) {
            $productsQuery->where(function($q) use ($searchQuery) {
                $q->where('products.name', 'like', "%{$searchQuery}%")
                  ->orWhere('products.description', 'like', "%{$searchQuery}%")
                  ->orWhereHas('store', function($sq) use ($searchQuery) {
                      $sq->where('store_name', 'like', "%{$searchQuery}%");
                  });
            });
        }

        if ($calorieFilter) {
            if ($calorieFilter === 'under300') {
                $productsQuery->where('products.calories', '<', 300);
            } elseif ($calorieFilter === '300to500') {
                $productsQuery->whereBetween('products.calories', [300, 500]);
            } elseif ($calorieFilter === 'over500') {
                $productsQuery->where('products.calories', '>', 500);
            }
        }

        if ($selectedDiet) {
            $productsQuery->whereJsonContains('products.dietary_tags', $selectedDiet);
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Live-Filter')) {
            return response()->json([
                'success' => true,
                'html' => view('users.explore.partials.products_grid', compact('categories', 'selectedCategory', 'products'))->render(),
                'total' => $products->total(),
            ]);
        }

        return view('users.explore.index', compact(
            'categories',
            'barangays',
            'stores',
            'allStores',
            'products',
            'selectedCategory',
            'searchQuery',
            'calorieFilter',
            'selectedDiet',
            'selectedStore',
            'onlySupplements',
            'nearMe',
            'userLat',
            'userLng'
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
