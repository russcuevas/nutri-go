@extends('layouts.app')

@section('title', 'My Healthy Cart | NutriGo Lipa')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-black font-heading text-gray-900 tracking-tight">Your Healthy Food Bag</h1>
            <p class="text-xs text-gray-500 mt-1">Review your calorie targets and meal selections before checkout</p>
        </div>

        @if (empty($cart))
            <div class="bg-white p-12 rounded-3xl border border-gray-200 text-center space-y-4 shadow-card max-w-lg mx-auto">
                <div
                    class="w-16 h-16 rounded-full bg-nutri-50 text-nutri-600 text-2xl flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 font-heading">Your bag is empty</h3>
                <p class="text-xs text-gray-500">Discover fresh salads, gym preps, and healthy bowls across Lipa City.</p>
                <a href="{{ route('users.explore') }}"
                    class="inline-flex px-6 py-3 rounded-xl bg-nutri-900 text-white text-xs font-bold shadow-md hover:bg-nutri-800 transition">
                    Start Exploring Food
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Cart Items List -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-white p-6 rounded-3xl border border-gray-200/80 shadow-card">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-nutri-600">Ordering
                                    From</span>
                                <h3 class="text-lg font-black text-gray-900 font-heading">
                                    {{ $store->store_name ?? 'Healthy Partner' }}</h3>
                            </div>
                            <form action="{{ route('users.cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                                    Clear Bag
                                </button>
                            </form>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach ($cart as $item)
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                            class="w-16 h-16 rounded-2xl object-cover bg-gray-50 border border-gray-100 shrink-0">
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">{{ $item['name'] }}</h4>
                                            <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                                <span>🔥 {{ $item['calories'] * $item['quantity'] }} kcal</span>
                                                <span>•</span>
                                                <span>💪 {{ $item['protein_g'] * $item['quantity'] }}g Protein</span>
                                            </div>
                                            <div class="text-xs font-bold text-nutri-800 mt-1">
                                                ₱{{ number_format($item['price'], 2) }} each
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center justify-between sm:justify-end gap-6">
                                        <form action="{{ route('users.cart.update') }}" method="POST"
                                            class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                                class="px-3 py-1 text-xs text-gray-600 hover:bg-gray-200 transition font-bold">-</button>
                                            <span
                                                class="px-3 py-1 text-xs font-bold text-gray-900 bg-white">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                                class="px-3 py-1 text-xs text-gray-600 hover:bg-gray-200 transition font-bold">+</button>
                                        </form>

                                        <div class="text-right">
                                            <div class="text-sm font-black text-gray-900 font-heading">
                                                ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                            </div>
                                            <form action="{{ route('users.cart.remove') }}" method="POST" class="mt-0.5">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                <button type="submit"
                                                    class="text-[10px] text-rose-500 hover:underline">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Nutrition Summary & Checkout -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Calorie & Macro Meter -->
                    <div class="bg-white p-6 rounded-3xl border border-nutri-200 shadow-card space-y-4">
                        <h3 class="text-xs font-extrabold uppercase tracking-widest text-nutri-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-nutri-600"></i> Nutrition Intake Meter
                        </h3>

                        <div class="p-4 rounded-2xl bg-nutri-50 border border-nutri-200 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-xs text-gray-500 font-semibold">Total Energy</div>
                                <div class="text-2xl font-black text-nutri-900 font-heading">{{ $totalCalories }} <span
                                        class="text-xs text-nutri-600 font-bold">kcal</span></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 font-semibold">Total Protein</div>
                                <div class="text-2xl font-black text-rose-600 font-heading">{{ $totalProtein }} <span
                                        class="text-xs text-rose-500 font-bold">g</span></div>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="space-y-2 pt-2 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal:</span>
                                <span class="font-bold text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('users.checkout.index') }}"
                            class="w-full py-4 rounded-2xl bg-limey-400 hover:bg-limey-500 text-nutri-950 font-black text-sm shadow-md transition font-heading flex items-center justify-center gap-2">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
