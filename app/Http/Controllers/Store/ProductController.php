<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        $products = Product::where('store_id', $store->id)
            ->with(['category', 'alternativeTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('store.products.index', compact('store', 'products'));
    }

    public function create()
    {
        $store = Auth::user()->store;
        $categories = Category::orderBy('order_num', 'asc')->get();
        $otherProducts = Product::where('store_id', $store->id)->get();

        return view('store.products.create', compact('store', 'categories', 'otherProducts'));
    }

    public function store(Request $request)
    {
        $store = Auth::user()->store;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'calories' => 'required|integer|min:0',
            'protein_g' => 'required|numeric|min:0',
            'carbs_g' => 'required|numeric|min:0',
            'fat_g' => 'required|numeric|min:0',
            'dietary_tags' => 'nullable|array',
            'is_healthy_choice' => 'boolean',
            'is_supplement' => 'boolean',
            'alternative_to_id' => 'nullable|exists:products,id',
            'healthy_alternative_notes' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/products');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        Product::create([
            'store_id' => $store->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'calories' => $validated['calories'],
            'protein_g' => $validated['protein_g'],
            'carbs_g' => $validated['carbs_g'],
            'fat_g' => $validated['fat_g'],
            'dietary_tags' => $validated['dietary_tags'] ?? [],
            'is_healthy_choice' => $request->boolean('is_healthy_choice', true),
            'is_supplement' => $request->boolean('is_supplement', false),
            'alternative_to_id' => $validated['alternative_to_id'] ?? null,
            'healthy_alternative_notes' => $validated['healthy_alternative_notes'] ?? null,
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('store.products.index')->with('success', 'Healthy food item added successfully!');
    }

    public function edit(int $id)
    {
        $store = Auth::user()->store;
        $product = Product::where('store_id', $store->id)->where('id', $id)->firstOrFail();
        $categories = Category::orderBy('order_num', 'asc')->get();
        $otherProducts = Product::where('store_id', $store->id)->where('id', '!=', $product->id)->get();

        return view('store.products.edit', compact('store', 'product', 'categories', 'otherProducts'));
    }

    public function update(Request $request, int $id)
    {
        $store = Auth::user()->store;
        $product = Product::where('store_id', $store->id)->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'calories' => 'required|integer|min:0',
            'protein_g' => 'required|numeric|min:0',
            'carbs_g' => 'required|numeric|min:0',
            'fat_g' => 'required|numeric|min:0',
            'dietary_tags' => 'nullable|array',
            'is_healthy_choice' => 'boolean',
            'is_supplement' => 'boolean',
            'alternative_to_id' => 'nullable|exists:products,id',
            'healthy_alternative_notes' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:3072',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/products');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $imagePath = 'uploads/products/' . $filename;
        } elseif ($request->boolean('remove_image')) {
            $imagePath = null;
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'calories' => $validated['calories'],
            'protein_g' => $validated['protein_g'],
            'carbs_g' => $validated['carbs_g'],
            'fat_g' => $validated['fat_g'],
            'dietary_tags' => $validated['dietary_tags'] ?? [],
            'is_healthy_choice' => $request->boolean('is_healthy_choice', true),
            'is_supplement' => $request->boolean('is_supplement', false),
            'alternative_to_id' => $validated['alternative_to_id'] ?? null,
            'healthy_alternative_notes' => $validated['healthy_alternative_notes'] ?? null,
            'stock' => $validated['stock'],
            'is_available' => $request->boolean('is_available', true),
            'image' => $imagePath,
        ]);

        return redirect()->route('store.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(int $id)
    {
        $store = Auth::user()->store;
        $product = Product::where('store_id', $store->id)->where('id', $id)->firstOrFail();
        $product->delete();

        return redirect()->route('store.products.index')->with('success', 'Product deleted.');
    }
}
