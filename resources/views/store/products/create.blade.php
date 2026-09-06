@extends('layouts.store')

@section('title', 'Add Healthy Food Item | ' . $store->store_name)
@section('header_title', 'Add Healthy Item')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Add New Nutrition Item</h3>
            <p class="text-xs text-gray-500">Provide accurate calorie counts and dietary classifications</p>
        </div>
        <a href="{{ route('store.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900">
            Cancel
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
            @foreach($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('store.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Product Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500" placeholder="e.g. Avocado Quinoa Salad">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Category *</label>
                <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500 bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-700 uppercase mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full p-3 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-nutri-500" placeholder="Describe fresh ingredients and health perks...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Price (₱) *</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price') }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs" placeholder="250.00">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Original Price (₱)</label>
                <input type="number" step="0.01" name="original_price" value="{{ old('original_price') }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs" placeholder="Optional discount">
            </div>
            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Daily Stock</label>
                <input type="number" name="stock" required value="{{ old('stock', 50) }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs">
            </div>
        </div>

        <!-- Nutrition Facts Breakdown -->
        <div class="p-4 rounded-2xl bg-nutri-50/70 border border-nutri-200 space-y-3">
            <span class="font-extrabold uppercase text-nutri-900 tracking-wider block">Nutrition Facts Breakdown *</span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Calories (kcal)</label>
                    <input type="number" name="calories" required value="{{ old('calories', 350) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Protein (g)</label>
                    <input type="number" step="0.1" name="protein_g" required value="{{ old('protein_g', 25) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Carbs (g)</label>
                    <input type="number" step="0.1" name="carbs_g" required value="{{ old('carbs_g', 30) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Fat (g)</label>
                    <input type="number" step="0.1" name="fat_g" required value="{{ old('fat_g', 10) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
            </div>
        </div>

        <!-- Dietary Tags -->
        <div>
            <label class="block font-bold text-gray-700 uppercase mb-2">Dietary Tags</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach(['Vegan', 'Keto', 'High-Protein', 'Diabetic-Friendly', 'Gluten-Free', 'Low-Sodium', 'Low-Calorie', 'Organic'] as $tag)
                    <label class="flex items-center gap-2 p-2 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="dietary_tags[]" value="{{ $tag }}" class="rounded text-nutri-600">
                        <span class="font-semibold">{{ $tag }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Smart Healthy Swap Recommendation -->
        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-3">
            <span class="font-bold text-amber-950 uppercase tracking-wider block">Healthy Alternative Swap (Optional)</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Swaps with which item?</label>
                    <select name="alternative_to_id" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                        <option value="">None (Standalone meal)</option>
                        @foreach($otherProducts as $op)
                            <option value="{{ $op->id }}">{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Recommendation Note</label>
                    <input type="text" name="healthy_alternative_notes" placeholder="e.g. Swaps white rice for cauliflower rice." class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs bg-white">
                </div>
            </div>
        </div>

        <!-- Badges & Image -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-emerald-800">
                    <input type="checkbox" name="is_healthy_choice" value="1" checked class="rounded text-emerald-600">
                    <span>Award "Healthy Choice" Badge</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-purple-800">
                    <input type="checkbox" name="is_supplement" value="1" class="rounded text-purple-600">
                    <span>This is a certified health supplement</span>
                </label>
            </div>

            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Food Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-[11px] text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-nutri-900 file:text-white">
            </div>
        </div>

        <div class="pt-4 border-t flex gap-3">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow transition">
                Save & Publish Healthy Item
            </button>
        </div>
    </form>
</div>
@endsection
