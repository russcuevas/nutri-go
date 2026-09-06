@extends('layouts.store')

@section('title', 'Menu & Nutrition Facts | ' . $store->store_name)
@section('header_title', 'Healthy Food Menu')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Manage Food & Nutrition Information</h3>
            <p class="text-xs text-gray-500">Every item must have accurate calories, macros, and dietary tags</p>
        </div>

        <a href="{{ route('store.products.create') }}" class="px-4 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Add New Healthy Meal
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm flex flex-col justify-between">
                <div>
                    <div class="relative h-40 overflow-hidden bg-gray-100">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        <div class="absolute top-2 left-2 flex gap-1">
                            @if($product->is_healthy_choice)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[9px] font-extrabold shadow">
                                    Healthy Choice
                                </span>
                            @endif
                            @if($product->is_supplement)
                                <span class="px-2 py-0.5 rounded-full bg-purple-600 text-white text-[9px] font-extrabold shadow">
                                    Supplement
                                </span>
                            @endif
                        </div>
                        <div class="absolute bottom-2 right-2 px-2.5 py-1 rounded-lg bg-gray-900/90 text-white text-xs font-black shadow-md flex items-center gap-1">
                            🔥 {{ $product->calories }} kcal
                        </div>
                    </div>

                    <div class="p-4 space-y-2">
                        <span class="text-[10px] font-bold text-nutri-600 uppercase">{{ $product->category->name ?? 'General' }}</span>
                        <h4 class="text-sm font-bold text-gray-900">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $product->description }}</p>
                        
                        <div class="pt-2 flex items-center justify-between text-[11px] font-bold text-gray-600 border-t">
                            <span>💪 {{ $product->protein_g }}g P</span>
                            <span>🍞 {{ $product->carbs_g }}g C</span>
                            <span>🥑 {{ $product->fat_g }}g F</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 pt-0 flex items-center justify-between border-t border-gray-100 mt-2">
                    <span class="text-base font-black text-gray-900 font-heading">₱{{ number_format($product->price, 2) }}</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('store.products.edit', $product->id) }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <form action="{{ route('store.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-12 text-center text-xs text-gray-400">No products added yet. Click "Add New Healthy Meal" above.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
