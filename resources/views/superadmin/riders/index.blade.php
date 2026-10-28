@extends('layouts.admin')

@section('title', 'Delivery Rider Approvals | Super Admin')
@section('header_title', 'Rider Fleet Approvals')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-card space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black font-heading text-gray-900">Delivery Rider Applications</h3>
            <p class="text-xs text-gray-500">Vet driver's licenses and vehicle details for Lipa City delivery operations</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold">
            <a href="{{ route('superadmin.riders.index') }}" class="px-3 py-1.5 rounded-xl {{ $status == 'all' ? 'bg-nutri-900 text-white' : 'bg-gray-100 text-gray-700' }}">All ({{ $counts['all'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=pending" class="px-3 py-1.5 rounded-xl {{ $status == 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Pending ({{ $counts['pending'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=approved" class="px-3 py-1.5 rounded-xl {{ $status == 'approved' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}">Approved ({{ $counts['approved'] }})</a>
            <a href="{{ route('superadmin.riders.index') }}?status=rejected" class="px-3 py-1.5 rounded-xl {{ $status == 'rejected' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700' }}">Rejected ({{ $counts['rejected'] }})</a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($riders as $r)
            <div class="py-4 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-gray-50/50 px-2 rounded-2xl transition" x-data="{ openRejectModal: false, openLicenseModal: false, zoomImage: null }">
                <div class="flex items-start gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-nutri-50 text-nutri-800 border border-nutri-100 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                        <i class="fa-solid fa-motorcycle text-lg text-nutri-700"></i>
                    </div>

                    <div class="space-y-1.5 min-w-0">
                        <!-- Line 1: Name & Status -->
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-bold text-gray-900 text-sm sm:text-base leading-snug">{{ $r->user->name }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $r->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($r->status === 'rejected' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                {{ $r->status }}
                            </span>
                        </div>

                        <!-- Line 2: Vehicle, Phone & License Badges -->
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-gauge text-[10px] text-nutri-700"></i>
                                {{ $r->vehicle_type }} • <span class="font-mono text-gray-900">{{ $r->plate_number }}</span>
                            </span>

                            <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-phone text-[10px] text-gray-400"></i>
                                {{ $r->phone }}
                            </span>

                            <span class="inline-flex items-center gap-1.5 text-gray-700 bg-gray-50 border border-gray-200/70 px-2.5 py-1 rounded-lg text-[11px]">
                                <i class="fa-solid fa-id-card text-[10px] text-gray-400"></i>
                                License: <span class="font-mono font-bold text-gray-800">{{ $r->license_number }}</span>
                            </span>

                            <!-- License Front Badge -->
                            @if($r->license_image_url)
                                <button type="button" @click="openLicenseModal = true" class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/70 px-2.5 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer" title="Suriin ang Harap ng Lisensya">
                                    <i class="fa-solid fa-id-card text-[10px] text-blue-500"></i>
                                    <span>Harap (Front ID)</span>
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-400 bg-gray-50 border border-gray-200/70 px-2 py-1 rounded-lg text-[10px]">
                                    <i class="fa-solid fa-circle-xmark text-gray-300"></i> Walang Harap
                                </span>
                            @endif

                            <!-- License Back Badge -->
                            @if($r->license_back_image_url)
                                <button type="button" @click="openLicenseModal = true" class="inline-flex items-center gap-1.5 text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200/70 px-2.5 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer" title="Suriin ang Likod ng Lisensya">
                                    <i class="fa-solid fa-id-card-clip text-[10px] text-purple-500"></i>
                                    <span>Likod (Back ID)</span>
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-400 bg-gray-50 border border-gray-200/70 px-2 py-1 rounded-lg text-[10px]">
                                    <i class="fa-solid fa-circle-xmark text-gray-300"></i> Walang Likod
                                </span>
                            @endif
                        </div>

                        <!-- Line 3: Address -->
                        @if($r->address_line)
                            <div class="text-[11px] text-gray-500 flex items-center gap-1.5 pt-0.5">
                                <i class="fa-solid fa-location-dot text-rose-500 text-xs shrink-0"></i>
                                <span>{{ $r->address_line }}</span>
                            </div>
                        @endif

                        <!-- Line 4: Rejection Reason if any -->
                        @if($r->rejection_reason)
                            <div class="text-[11px] text-rose-700 bg-rose-50 border border-rose-200/60 px-2.5 py-1 rounded-lg font-medium inline-flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                                <span>Rejection Reason: "{{ $r->rejection_reason }}"</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 justify-end flex-wrap">
                    <button type="button" @click="openLicenseModal = true" class="px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer" title="Suriin ang litrato ng Harap at Likod ng lisensya bago i-apruba">
                        <i class="fa-solid fa-magnifying-glass-plus text-blue-600"></i> Suriin ang Lisensya
                    </button>

                    @if($r->status !== 'approved')
                        <form action="{{ route('superadmin.riders.approve', $r->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </form>
                    @endif

                    @if($r->status !== 'rejected')
                        <button @click="openRejectModal = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i> Reject & Delete
                        </button>
                    @endif
                </div>

                <!-- License ID Inspection Modal (Front & Back) -->
                <div x-show="openLicenseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-xs"
                     @keydown.escape.window="openLicenseModal = false">
                    <div @click.outside="openLicenseModal = false" class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-stone-900 via-stone-800 to-lime-950 text-white flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-lime-500/20 border border-lime-400/30 text-lime-300 flex items-center justify-center text-lg shadow-sm">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-lime-300 bg-white/10 px-2 py-0.5 rounded-full">ID Inspection</span>
                                        <span class="text-[11px] text-stone-300">{{ $r->vehicle_type }} • {{ $r->plate_number }}</span>
                                    </div>
                                    <h4 class="text-base font-black font-heading text-white mt-0.5">Suriin ang Lisensya ni {{ $r->user->name }}</h4>
                                </div>
                            </div>
                            <button type="button" @click="openLicenseModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>

                        <!-- Body (Scrollable) -->
                        <div class="p-5 sm:p-6 overflow-y-auto space-y-4">
                            <!-- Info Notice -->
                            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-center gap-2.5">
                                <i class="fa-solid fa-shield-halved text-amber-600 text-base shrink-0"></i>
                                <span>I-verify kung lehitimo at tumutugma ang <strong>License No. ({{ $r->license_number }} | {{ $r->vehicle_type }})</strong> sa ipinasang litrato sa harap at likod bago i-apruba.</span>
                            </div>

                            <!-- Rider Info Summary -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100 text-xs">
                                <div>
                                    <span class="block text-[10px] font-bold uppercase text-gray-400">Rider Name</span>
                                    <span class="font-bold text-gray-900">{{ $r->user->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase text-gray-400">License Number</span>
                                    <span class="font-mono font-bold text-gray-900">{{ $r->license_number }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase text-gray-400">Phone (GCash)</span>
                                    <span class="font-mono font-bold text-gray-900">{{ $r->phone }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase text-gray-400">Plate Number</span>
                                    <span class="font-mono font-bold text-gray-900">{{ $r->plate_number }}</span>
                                </div>
                            </div>

                            <!-- Dual Cards: Front & Back Photos -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <!-- Harap (Front) Card -->
                                <div class="p-4 rounded-2xl bg-blue-50/40 border border-blue-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                                            <i class="fa-solid fa-id-card text-blue-600"></i> Harap (Front of ID)
                                        </span>
                                        @if($r->license_image_url)
                                            <a href="{{ $r->license_image_url }}" target="_blank" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 underline flex items-center gap-1">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Bagong Tab
                                            </a>
                                        @endif
                                    </div>
                                    <div class="w-full h-56 rounded-xl overflow-hidden border border-blue-200 bg-white p-2 flex items-center justify-center shadow-inner">
                                        @if($r->license_image_url)
                                            <img src="{{ $r->license_image_url }}" alt="Front License of {{ $r->user->name }}" class="max-w-full max-h-full object-contain cursor-pointer hover:scale-105 transition" @click="zoomImage = '{{ $r->license_image_url }}'">
                                        @else
                                            <div class="text-center p-4 text-gray-400 space-y-1">
                                                <i class="fa-solid fa-image-slash text-2xl"></i>
                                                <p class="text-xs font-medium">Walang na-upload na harap ng lisensya</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Likod (Back) Card -->
                                <div class="p-4 rounded-2xl bg-purple-50/40 border border-purple-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-purple-950 flex items-center gap-1.5">
                                            <i class="fa-solid fa-id-card-clip text-purple-600"></i> Likod (Back of ID)
                                        </span>
                                        @if($r->license_back_image_url)
                                            <a href="{{ $r->license_back_image_url }}" target="_blank" class="text-[11px] font-bold text-purple-700 hover:text-purple-900 underline flex items-center gap-1">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Bagong Tab
                                            </a>
                                        @endif
                                    </div>
                                    <div class="w-full h-56 rounded-xl overflow-hidden border border-purple-200 bg-white p-2 flex items-center justify-center shadow-inner">
                                        @if($r->license_back_image_url)
                                            <img src="{{ $r->license_back_image_url }}" alt="Back License of {{ $r->user->name }}" class="max-w-full max-h-full object-contain cursor-pointer hover:scale-105 transition" @click="zoomImage = '{{ $r->license_back_image_url }}'">
                                        @else
                                            <div class="text-center p-4 text-gray-400 space-y-1">
                                                <i class="fa-solid fa-image-slash text-2xl"></i>
                                                <p class="text-xs font-medium">Walang na-upload na likod ng lisensya</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0 flex-wrap">
                            <button type="button" @click="openLicenseModal = false" class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 text-xs font-bold transition">
                                Isara
                            </button>
                            <div class="flex items-center gap-2">
                                @if($r->status !== 'rejected')
                                    <button type="button" @click="openLicenseModal = false; openRejectModal = true" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i> Reject & Delete
                                    </button>
                                @endif
                                @if($r->status !== 'approved')
                                    <form action="{{ route('superadmin.riders.approve', $r->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                                            <i class="fa-solid fa-check"></i> I-apruba si {{ $r->user->name }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zoom Fullscreen Overlay for ID Pictures in Superadmin -->
                <div x-show="zoomImage !== null" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
                     @click.self="zoomImage = null" @keydown.escape.window="zoomImage = null">
                    <div class="bg-white rounded-3xl p-4 max-w-2xl w-full text-center space-y-3 relative shadow-2xl">
                        <button type="button" @click="zoomImage = null" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-800 flex items-center justify-center font-bold">
                            ✕
                        </button>
                        <h4 class="text-xs font-bold text-gray-600 uppercase">Driver's License Photo Inspection</h4>
                        <div class="w-full h-96 rounded-2xl overflow-hidden bg-gray-900 flex items-center justify-center p-2">
                            <img :src="zoomImage" alt="Zoomed ID" class="max-w-full max-h-full object-contain">
                        </div>
                    </div>
                </div>

                <!-- Reject & Delete Modal -->
                <div x-show="openRejectModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
                    <div @click.outside="openRejectModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl border border-rose-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="font-black font-heading text-base text-gray-900 text-center">I-reject at I-delete si {{ $r->user->name }}?</h4>
                        <p class="text-xs text-gray-600 text-center leading-relaxed">
                            Kapag na-reject, ang rider na ito kasama ang kanyang user account ay <b>tuluyang mabubura sa database</b> at hindi na makakapasok sa system.
                        </p>
                        <form action="{{ route('superadmin.riders.reject', $r->id) }}" method="POST" class="space-y-3 pt-2">
                            @csrf
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-trash-can"></i> Oo, I-reject & I-delete
                                </button>
                                <button type="button" @click="openRejectModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition">Kanselahin</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="py-12 text-center text-xs text-gray-400">No riders found.</div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $riders->links() }}
    </div>
</div>
@endsection
