@extends('layouts.store')

@section('title', 'Edit ' . $product->name . ' | ' . $store->store_name)
@section('header_title', 'Edit Food Item')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Edit Nutrition Item</h3>
            <p class="text-xs text-gray-500">Update calories, macros, or availability</p>
        </div>
        <a href="{{ route('store.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900">
            Cancel
        </a>
    </div>

    <form action="{{ route('store.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Product Name *</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Category *</label>
                <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-700 uppercase mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full p-3 rounded-xl border border-gray-200 text-xs">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Price (₱) *</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price', $product->price) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Original Price (₱)</label>
                <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Daily Stock</label>
                <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-nutri-50/70 border border-nutri-200 space-y-3">
            <span class="font-extrabold uppercase text-nutri-900 tracking-wider block">Nutrition Facts Breakdown *</span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Calories (kcal)</label>
                    <input type="number" name="calories" required value="{{ old('calories', $product->calories) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Protein (g)</label>
                    <input type="number" step="0.1" name="protein_g" required value="{{ old('protein_g', $product->protein_g) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Carbs (g)</label>
                    <input type="number" step="0.1" name="carbs_g" required value="{{ old('carbs_g', $product->carbs_g) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Fat (g)</label>
                    <input type="number" step="0.1" name="fat_g" required value="{{ old('fat_g', $product->fat_g) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-700 uppercase mb-2">Dietary Tags</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @php $currentTags = $product->dietary_tags ?? []; @endphp
                @foreach(['Vegan', 'Keto', 'High-Protein', 'Diabetic-Friendly', 'Gluten-Free', 'Low-Sodium', 'Low-Calorie', 'Organic'] as $tag)
                    <label class="flex items-center gap-2 p-2 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="dietary_tags[]" value="{{ $tag }}" {{ in_array($tag, $currentTags) ? 'checked' : '' }} class="rounded text-nutri-600">
                        <span class="font-semibold">{{ $tag }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-emerald-800">
                    <input type="checkbox" name="is_healthy_choice" value="1" {{ $product->is_healthy_choice ? 'checked' : '' }} class="rounded text-emerald-600">
                    <span>Award "Healthy Choice" Badge</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-purple-800">
                    <input type="checkbox" name="is_supplement" value="1" {{ $product->is_supplement ? 'checked' : '' }} class="rounded text-purple-600">
                    <span>This is a certified health supplement</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-800">
                    <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} class="rounded text-nutri-600">
                    <span>Item is currently available for ordering</span>
                </label>
            </div>

            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Replace Food Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-[11px] text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-nutri-900 file:text-white">
            </div>
        </div>

        <div class="pt-4 border-t flex gap-3">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow transition">
                Update Healthy Food Item
            </button>
        </div>
    </form>
</div>
@endsection
