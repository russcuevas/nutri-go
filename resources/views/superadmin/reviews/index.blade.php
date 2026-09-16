@extends('layouts.admin')

@section('title', 'Customer Reviews Management | Super Admin')
@section('header_title', 'Customer Reviews & Feedback')

@section('content')
    <div class="space-y-6" x-data="{ addReviewModal: false }">

        <!-- Top Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Reviews -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Reviews</p>
                    <h3 class="text-2xl font-black text-gray-900 font-heading mt-1">{{ $totalReviews }}</h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">Submitted by customers</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>

            <!-- Displayed on Website (is_active = 1) -->
            <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Displayed on Home</p>
                    <h3 class="text-2xl font-black text-emerald-900 font-heading mt-1">{{ $activeReviewsCount }}</h3>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> is_active = 1
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>

            <!-- Hidden / Inactive (is_active = 0) -->
            <div class="bg-white p-5 rounded-2xl border border-amber-200 bg-amber-50/20 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Hidden / Unmoderated</p>
                    <h3 class="text-2xl font-black text-amber-900 font-heading mt-1">{{ $inactiveReviewsCount }}</h3>
                    <p class="text-[11px] text-amber-600 mt-0.5">Pending superadmin tag</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Average Rating</p>
                    <div class="flex items-center gap-2 mt-1">
                        <h3 class="text-2xl font-black text-gray-900 font-heading">{{ number_format($avgRating, 1) }}</h3>
                        <div class="flex text-amber-400 text-xs">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-0.5">Satisfaction level</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
        </div>

        <!-- Main Reviews Container -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
            <!-- Header & Filter Bar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black font-heading text-gray-900">Manage Customer Reviews & Testimonials</h3>
                    <p class="text-xs text-gray-500">
                        Select which reviews are tagged as <span class="font-bold text-emerald-700">Active (<code class="bg-emerald-50 px-1 py-0.5 rounded text-emerald-800">is_active = 1</code>)</span> to showcase on the NutriGo homepage.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter Tabs -->
                    <div class="flex items-center bg-gray-100 p-1 rounded-xl text-xs font-bold">
                        <a href="{{ route('superadmin.reviews.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                            class="px-3 py-1.5 rounded-lg transition {{ $status === 'all' ? 'bg-nutri-950 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            All ({{ $totalReviews }})
                        </a>
                        <a href="{{ route('superadmin.reviews.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ $status === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:text-emerald-900' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active ({{ $activeReviewsCount }})
                        </a>
                        <a href="{{ route('superadmin.reviews.index', array_merge(request()->query(), ['status' => 'inactive'])) }}"
                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ $status === 'inactive' ? 'bg-amber-500 text-white shadow-xs' : 'text-amber-700 hover:text-amber-900' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Hidden ({{ $inactiveReviewsCount }})
                        </a>
                    </div>

                    <!-- Add Review Button -->
                    <button @click="addReviewModal = true"
                        class="px-4 py-2 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white text-xs font-extrabold shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-limey-400"></i> Add Review
                    </button>
                </div>
            </div>

            <!-- Search & Rating Filter Controls -->
            <form method="GET" action="{{ route('superadmin.reviews.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="sm:col-span-8 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by customer name, email, contact number, or keyword in review message..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/10">
                </div>

                <div class="sm:col-span-3">
                    <select name="rating" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs font-medium focus:bg-white focus:outline-none focus:border-nutri-500">
                        <option value="all" {{ $rating === 'all' ? 'selected' : '' }}>⭐ All Ratings (1 to 5 Stars)</option>
                        <option value="5" {{ $rating === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars Only</option>
                        <option value="4" {{ $rating === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars Only</option>
                        <option value="3" {{ $rating === '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Stars Only</option>
                        <option value="2" {{ $rating === '2' ? 'selected' : '' }}>⭐⭐ 2 Stars Only</option>
                        <option value="1" {{ $rating === '1' ? 'selected' : '' }}>⭐ 1 Star Only</option>
                    </select>
                </div>

                <div class="sm:col-span-1 flex items-center gap-1.5">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-800 text-white font-bold text-xs transition flex items-center justify-center">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if($search || $rating !== 'all')
                        <a href="{{ route('superadmin.reviews.index', ['status' => $status]) }}" class="px-2.5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs transition" title="Clear Filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Reviews List / Cards -->
            <div class="divide-y divide-gray-100">
                @forelse ($reviews as $review)
                    <div class="py-5 flex flex-col lg:flex-row lg:items-start justify-between gap-5 group hover:bg-gray-50/60 p-4 rounded-2xl transition"
                        x-data="{ viewModal: false }">
                        
                        <!-- Left Info: Reviewer & Message -->
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <!-- Avatar -->
                            <img src="{{ $review->avatar }}" alt="{{ $review->name }}"
                                class="w-12 h-12 rounded-2xl object-cover border border-gray-200 shadow-2xs shrink-0">

                            <div class="space-y-2 flex-1 min-w-0">
                                <!-- Line 1: Name, Rating & Status Badge -->
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <h4 class="font-extrabold text-gray-900 text-sm sm:text-base leading-snug">{{ $review->name }}</h4>

                                    <!-- Rating Stars -->
                                    <div class="flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200/60 text-amber-500 text-xs font-bold">
                                        <div class="flex gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star text-[10px] {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-amber-800 text-[11px] ml-0.5">{{ $review->rating }}.0</span>
                                    </div>

                                    <!-- Active Tag Badge -->
                                    @if ($review->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                            Active on Home (is_active = 1)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Hidden (is_active = 0)
                                        </span>
                                    @endif
                                </div>

                                <!-- Line 2: Contact Pills -->
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg text-[11px]">
                                        <i class="fa-solid fa-envelope text-[10px] text-gray-400"></i>
                                        {{ $review->email }}
                                    </span>

                                    @if ($review->contact)
                                        <span class="inline-flex items-center gap-1.5 font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg text-[11px]">
                                            <i class="fa-solid fa-phone text-[10px] text-nutri-600"></i>
                                            {{ $review->contact }}
                                        </span>
                                    @endif

                                    <span class="inline-flex items-center gap-1 text-gray-400 text-[11px] ml-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        {{ $review->created_at->format('M d, Y h:i A') }} ({{ $review->created_at->diffForHumans() }})
                                    </span>
                                </div>

                                <!-- Line 3: Message Content -->
                                <div class="relative bg-white p-3.5 rounded-2xl border border-gray-200/80 shadow-2xs mt-2 text-xs text-gray-700 leading-relaxed">
                                    <i class="fa-solid fa-quote-left text-gray-300 text-xs mr-1"></i>
                                    {{ $review->message }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Action Buttons -->
                        <div class="flex flex-wrap items-center lg:flex-col lg:items-end gap-2 shrink-0 pt-2 lg:pt-0">
                            <!-- Toggle Active / Inactive Button -->
                            <form action="{{ route('superadmin.reviews.toggle', $review->id) }}" method="POST" class="inline">
                                @csrf
                                @if ($review->is_active)
                                    <button type="submit"
                                        class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold text-xs transition flex items-center gap-1.5 shadow-2xs"
                                        title="Click to hide this review from the homepage">
                                        <i class="fa-solid fa-eye-slash text-amber-600"></i> Hide from Home
                                    </button>
                                @else
                                    <button type="submit"
                                        class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm"
                                        title="Click to tag as active and display on the homepage">
                                        <i class="fa-solid fa-check-double text-limey-300"></i> Tag to Display on Home
                                    </button>
                                @endif
                            </form>

                            <!-- Delete Button -->
                            <form action="{{ route('superadmin.reviews.destroy', $review->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to permanently delete this customer review?');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1"
                                    title="Delete review permanently">
                                    <i class="fa-solid fa-trash-can text-rose-500"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center space-y-3">
                        <div class="w-16 h-16 rounded-3xl bg-gray-100 text-gray-400 flex items-center justify-center text-2xl mx-auto">
                            <i class="fa-solid fa-comment-slash"></i>
                        </div>
                        <h4 class="text-base font-black text-gray-800 font-heading">No customer reviews found</h4>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto">
                            No reviews match your selected filter or search query. You can add a new review manually or check other filters.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if ($reviews->hasPages())
                <div class="pt-4 border-t border-gray-100">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>

        <!-- ADD NEW REVIEW MODAL (SuperAdmin Manual Insertion) -->
        <div x-show="addReviewModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="addReviewModal = false"
                class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-gray-100 overflow-hidden"
                x-data="{ starRating: 5 }">
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-nutri-950 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-limey-400 text-nutri-950 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-black font-heading text-white">Add Customer Review</h4>
                            <p class="text-[11px] text-nutri-200">Manually insert a verified customer review</p>
                        </div>
                    </div>
                    <button @click="addReviewModal = false" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form action="{{ route('superadmin.reviews.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Customer Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Maria Clara Lipa"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/10">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required placeholder="customer@gmail.com"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/10">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Contact Number (Optional)</label>
                            <input type="text" name="contact" placeholder="0917XXXXXXX"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/10">
                        </div>
                    </div>

                    <!-- Star Rating Interactive Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Rating (Stars) <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="rating" :value="starRating">
                        <div class="flex items-center gap-2 p-3 bg-amber-50/50 rounded-2xl border border-amber-200/60">
                            <div class="flex items-center gap-1.5 cursor-pointer">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="starRating = star"
                                        class="text-2xl transition hover:scale-125 focus:outline-none"
                                        :class="star <= starRating ? 'text-amber-400' : 'text-gray-300'">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                </template>
                            </div>
                            <span class="text-xs font-extrabold text-amber-900 ml-2" x-text="starRating + ' out of 5 Stars'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Customer Review Message <span class="text-rose-500">*</span></label>
                        <textarea name="message" rows="4" required placeholder="Share what the customer loved about their healthy meals, nutrition, or rider delivery experience..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-nutri-500 focus:ring-2 focus:ring-nutri-500/10"></textarea>
                    </div>

                    <div class="flex items-center gap-2.5 p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200">
                        <input type="checkbox" name="is_active" id="modal_is_active" value="1" checked
                            class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                        <label for="modal_is_active" class="text-xs font-bold text-emerald-900 cursor-pointer">
                            Tag as Active (<code class="bg-white px-1 py-0.5 rounded text-emerald-800 text-[10px]">is_active = 1</code>) to showcase immediately on Home section
                        </label>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="addReviewModal = false"
                            class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-nutri-900 hover:bg-emerald-600 text-white font-extrabold text-xs transition shadow-sm">
                            <i class="fa-solid fa-check mr-1"></i> Save Review
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
