@extends('layouts.rider')

@section('title', 'NutriGo Rider Wallet, Remittance & GCash Payouts')

@section('content')
<div class="space-y-6" x-data="{ openRemitModal: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-black font-heading text-gray-900 tracking-tight">Rider Digital Wallet & Remittance</h2>
            <p class="text-xs text-gray-500">Manage your delivery trip earnings, GCash cashouts, and 15-day store sales remittances</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-lime-100 text-lime-800 border border-lime-300">
                <i class="fa-solid fa-wallet mr-1 text-lime-600"></i> Active Wallet
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-nutri-100 text-nutri-800 border border-nutri-300">
                <i class="fa-solid fa-calendar-days mr-1 text-nutri-600"></i> 15-Day Remittance Cycle
            </span>
        </div>
    </div>

    <!-- Wallet Balance Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-nutri-200 shadow-card">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Available Balance (Trip Earnings)</span>
            <div class="text-3xl font-black text-nutri-900 font-heading mt-1">₱{{ number_format($wallet->balance, 2) }}</div>
            <span class="text-[10px] text-emerald-600 font-semibold">Ready for GCash Cashout / Withdrawal</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Career Earnings</span>
            <div class="text-3xl font-black text-gray-900 font-heading mt-1">₱{{ number_format($wallet->total_earnings, 2) }}</div>
            <span class="text-[10px] text-gray-400 font-semibold">Lifetime Delivery Fees Retained</span>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending GCash Payouts</span>
            <div class="text-3xl font-black text-amber-600 font-heading mt-1">₱{{ number_format($wallet->pending_payout, 2) }}</div>
            <span class="text-[10px] text-amber-600 font-semibold">Processing by Super Admin</span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 15-DAY REMITTANCE & STORE SALES BREAKDOWN SECTION -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-3xl border-2 border-nutri-700/20 shadow-xl overflow-hidden">
        
        <!-- Section Header with Semi-Monthly Period Switcher -->
        <div class="bg-gradient-to-r from-nutri-900 via-nutri-950 to-nutri-900 text-white p-6 sm:p-7">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-limey-400 text-nutri-950">
                            15-Day Remittance Cycle
                        </span>
                        <span class="text-xs text-nutri-200 font-medium">• Every 15th & 30th Remittance</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black font-heading text-white tracking-tight">
                        Cash on Delivery (COD) Remittance Tracker
                    </h3>
                    <p class="text-xs text-nutri-200 max-w-2xl">
                        Monitor the store sales you collected from customers that must be remitted to Super Admin, while your delivery fees go straight to your withdrawable balance.
                    </p>
                </div>

                <!-- 15-Day Cycle Dropdown Selector -->
                <form method="GET" action="{{ route('riders.wallet.index') }}" class="bg-white/10 p-2.5 rounded-2xl border border-white/20 shrink-0">
                    <div class="text-[10px] font-bold uppercase text-limey-300 mb-1">Select 15-Day Period:</div>
                    <select name="cycle" onchange="this.form.submit()" class="bg-nutri-950 text-white text-xs font-bold px-3 py-2 rounded-xl border border-nutri-700 focus:ring-2 focus:ring-limey-400 cursor-pointer w-full">
                        @foreach($availableCycles as $c)
                            <option value="{{ $c['key'] }}" {{ $selectedCycle['key'] === $c['key'] ? 'selected' : '' }}>
                                {{ $c['label'] }} {{ $c['is_current'] ? '★ Current' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            <!-- Flow Explanation Banner (Client Example) -->
            <div class="p-4 sm:p-5 rounded-2xl bg-lime-50/80 border border-lime-200 text-xs text-lime-950 space-y-2">
                <div class="flex items-center gap-2 font-black text-nutri-950 text-sm font-heading">
                    <i class="fa-solid fa-circle-info text-lime-600"></i>
                    <span>Paano Gumagana ang 15-Day Remittance at Withdrawal?</span>
                </div>
                <p class="leading-relaxed text-gray-700">
                    Kada deliver mo ng <strong>COD order</strong>, kinukolekta mo ang buong bayad mula sa customer. Ang <strong>Delivery Fee</strong> ay <em>awtomatikong pumapasok sa iyong Available Balance</em> para ma-withdraw mo agad via GCash. Ang <strong>benta naman ng bawat tindahan (Each store total sales)</strong> ay iniipon at kailangang i-remit sa Super Admin kada kinsenas (tuwing 15th at katapusan ng buwan).
                </p>
                <div class="p-3 bg-white rounded-xl border border-lime-300/60 font-medium text-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px]">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-nutri-900 text-white flex items-center justify-center font-bold text-xs">Ex</span>
                        <span>May order kay <strong>Store 1 na ₱500</strong> total (Delivery Fee: ₱50 + Store Food: ₱450):</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">➜ ₱50 sa Wallet Mo (Withdrawal)</span>
                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-bold">➜ ₱450 Store 1 Sales (I-remit kay Admin)</span>
                    </div>
                </div>
            </div>

            <!-- 3 Highlight Numbers for Selected 15-Day Period -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- 1. Total Money Need to Remit -->
                <div class="p-5 rounded-3xl bg-gradient-to-br from-nutri-900 to-nutri-950 text-white shadow-md relative overflow-hidden">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-limey-300 block">
                        Total Money Need to Remit
                    </span>
                    <div class="text-3xl font-black font-heading mt-1 text-white">
                        ₱{{ number_format($totalMoneyToRemit, 2) }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-[11px] text-nutri-200">
                        <i class="fa-solid fa-building-columns text-limey-400"></i>
                        <span>Store food sales to remit for {{ $selectedCycle['label'] }}</span>
                    </div>
                </div>

                <!-- 2. Each Store Total Sales Summary -->
                <div class="p-5 rounded-3xl bg-white border border-gray-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500 block">
                        Each Store Total Sales
                    </span>
                    <div class="text-3xl font-black font-heading mt-1 text-gray-900">
                        {{ count($eachStoreSales) }} <span class="text-sm font-bold text-gray-500">Store(s)</span>
                    </div>
                    <div class="mt-2 text-[11px] text-gray-500">
                        From <strong>{{ $codOrders->count() }}</strong> completed COD deliveries
                    </div>
                </div>

                <!-- 3. Trip Earnings Retained -->
                <div class="p-5 rounded-3xl bg-white border border-lime-300 bg-lime-50/30 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-lime-900 block">
                        Your Trip Fees (In Your Wallet)
                    </span>
                    <div class="text-3xl font-black font-heading mt-1 text-lime-700">
                        ₱{{ number_format($periodDeliveryFees, 2) }}
                    </div>
                    <div class="mt-2 text-[11px] text-emerald-700 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Kept by you • Credited to Available Balance</span>
                    </div>
                </div>

            </div>

            <!-- EACH STORE TOTAL SALES BREAKDOWN TABLE -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-base font-black font-heading text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-store text-lime-600"></i>
                            <span>Each Store Total Sales Breakdown</span>
                        </h4>
                        <p class="text-xs text-gray-500">Detailed cash collected per merchant for the 15-day period ({{ $selectedCycle['label'] }})</p>
                    </div>
                </div>

                @if($eachStoreSales->isEmpty())
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-200 text-center text-xs text-gray-500 space-y-1">
                        <i class="fa-solid fa-bag-shopping text-2xl text-gray-300 block mb-1"></i>
                        <p class="font-bold text-gray-700">Walang COD deliveries para sa 15-day period na ito.</p>
                        <p class="text-[11px] text-gray-400">Kapag nakapag-deliver ka ng COD order, awtomatikong lalabas dito ang benta ng bawat tindahan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-2xl border border-gray-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-100 text-gray-700 uppercase font-bold text-[10px]">
                                <tr>
                                    <th class="py-3 px-4">Partner Store</th>
                                    <th class="py-3 px-4">Location</th>
                                    <th class="py-3 px-4 text-center">Orders</th>
                                    <th class="py-3 px-4 text-right font-black text-nutri-900 bg-lime-100/60">Each Store Total Sales</th>
                                    <th class="py-3 px-4 text-right">Trip Fee (In Wallet)</th>
                                    <th class="py-3 px-4 text-right">Total Cash Collected</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($eachStoreSales as $sb)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="py-3 px-4 font-black text-gray-900 flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-nutri-100 text-nutri-900 flex items-center justify-center text-xs shrink-0 font-bold">
                                                <i class="fa-solid fa-utensils"></i>
                                            </div>
                                            <div>
                                                <span>{{ $sb['store_name'] }}</span>
                                                @if($sb['store_phone'])
                                                    <span class="text-[10px] text-gray-400 block font-normal">{{ $sb['store_phone'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            Brgy. {{ $sb['store_barangay'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-gray-800">
                                            <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800 text-[11px]">
                                                {{ $sb['orders_count'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right font-black text-nutri-950 bg-lime-50/50 text-sm font-heading">
                                            ₱{{ number_format($sb['total_sales'], 2) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-lime-700">
                                            ₱{{ number_format($sb['delivery_fees'], 2) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-bold text-gray-900">
                                            ₱{{ number_format($sb['total_cash_collected'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50/80 font-black text-xs border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-gray-900 uppercase">
                                        Total for {{ $selectedCycle['label'] }}:
                                    </td>
                                    <td class="py-3 px-4 text-right text-nutri-900 text-base font-heading bg-lime-100/50">
                                        ₱{{ number_format($totalMoneyToRemit, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-right text-lime-700 text-sm font-heading">
                                        ₱{{ number_format($periodDeliveryFees, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-right text-gray-900 text-sm font-heading">
                                        ₱{{ number_format($totalCashCollected, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            <!-- REMITTANCE SUBMISSION / STATUS CARD -->
            <div class="p-6 rounded-3xl bg-gray-50 border border-gray-200 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h4 class="text-sm font-black font-heading uppercase tracking-wider text-gray-900">
                            Super Admin Remittance Settlement
                        </h4>
                        <p class="text-xs text-gray-500">Remit your collected store sales (₱{{ number_format($totalMoneyToRemit, 2) }}) to NutriGo HQ</p>
                    </div>

                    @if($remittanceRecord)
                        <div class="flex items-center gap-2">
                            @if($remittanceRecord->status === 'verified')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i> Remittance Verified & Settled
                                </span>
                            @elseif($remittanceRecord->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                    <i class="fa-solid fa-clock mr-1 text-amber-600"></i> Proof Submitted (Under Admin Review)
                                </span>
                            @elseif($remittanceRecord->status === 'rejected')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-rose-100 text-rose-800 border border-rose-300">
                                    <i class="fa-solid fa-circle-xmark mr-1 text-rose-600"></i> Proof Rejected (Please Resubmit)
                                </span>
                            @endif
                        </div>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-gray-200 text-gray-700">
                            Status: Unremitted
                        </span>
                    @endif
                </div>

                @if($remittanceRecord)
                    <div class="p-4 rounded-2xl bg-white border border-gray-200 text-xs space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <span class="text-gray-400 text-[10px] font-bold uppercase block">Remittance Reference</span>
                                <span class="font-mono font-bold text-gray-900 text-sm">{{ $remittanceRecord->reference_no }}</span>
                                <span class="text-gray-500 text-[11px] uppercase ml-1 font-bold">({{ $remittanceRecord->payment_method }})</span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-[10px] font-bold uppercase block">Submitted Date</span>
                                <span class="text-gray-700 font-medium">{{ $remittanceRecord->remitted_at?->format('M d, Y • h:i A') }}</span>
                            </div>
                            @if($remittanceRecord->proof_url)
                                <div>
                                    <a href="{{ $remittanceRecord->proof_url }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-lime-50 text-lime-900 hover:bg-lime-100 font-bold border border-lime-300 text-[11px] inline-flex items-center gap-1.5 transition">
                                        <i class="fa-solid fa-receipt text-lime-600"></i> View Uploaded Receipt
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($remittanceRecord->admin_notes)
                            <div class="p-2.5 rounded-xl {{ $remittanceRecord->status === 'verified' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-rose-50 text-rose-900 border border-rose-200' }}">
                                <span class="font-bold">Super Admin Note:</span> {{ $remittanceRecord->admin_notes }}
                            </div>
                        @endif

                        @if($remittanceRecord->status === 'rejected')
                            <button @click="openRemitModal = true" class="mt-2 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-redo"></i> Resubmit Remittance Proof
                            </button>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-gray-200">
                        <div class="space-y-1 text-xs">
                            <span class="font-bold text-gray-900 block">Super Admin Official GCash for Remittance:</span>
                            <p class="font-mono font-bold text-base text-nutri-900">0917-888-NUTR (0917-888-6887)</p>
                            <p class="text-[11px] text-gray-500">Account Name: <strong>NutriGo Lipa Remittance Hub</strong></p>
                        </div>

                        @if($totalMoneyToRemit > 0)
                            <button @click="openRemitModal = true" class="px-6 py-3 rounded-2xl bg-nutri-900 hover:bg-nutri-950 text-white font-black text-xs shadow-md transition font-heading flex items-center gap-2 cursor-pointer shrink-0">
                                <i class="fa-solid fa-paper-plane text-limey-400"></i>
                                <span>Submit Remittance Proof (₱{{ number_format($totalMoneyToRemit, 2) }})</span>
                            </button>
                        @else
                            <button disabled class="px-6 py-3 rounded-2xl bg-gray-200 text-gray-400 font-bold text-xs cursor-not-allowed shrink-0">
                                No Remittance Due (₱0.00)
                            </button>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- SUBMIT REMITTANCE MODAL -->
    <div x-show="openRemitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @keydown.escape.window="openRemitModal = false">
        <div @click.outside="openRemitModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-gray-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-lime-700 bg-lime-100 px-2.5 py-0.5 rounded-full border border-lime-300">
                        Semi-Monthly Remittance
                    </span>
                    <h4 class="text-xl font-black font-heading text-gray-900 mt-1">Submit Remittance Proof</h4>
                </div>
                <button @click="openRemitModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- Amount & Period Summary -->
            <div class="p-4 rounded-2xl bg-nutri-900 text-white space-y-1 text-xs">
                <div class="flex justify-between items-center text-nutri-200">
                    <span>15-Day Remittance Period:</span>
                    <span class="font-bold text-white">{{ $selectedCycle['label'] }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-nutri-800 text-sm font-bold text-limey-400">
                    <span>Total Store Sales to Remit:</span>
                    <span class="text-xl font-black font-heading text-white">₱{{ number_format($totalMoneyToRemit, 2) }}</span>
                </div>
            </div>

            <!-- Super Admin Payment Destination Box -->
            <div class="p-3.5 rounded-2xl bg-lime-50 border border-lime-200 text-xs text-lime-950 space-y-1">
                <span class="font-bold text-[10px] uppercase text-lime-800 tracking-wider block">Super Admin GCash Details:</span>
                <p class="font-mono font-black text-sm text-nutri-950">0917-888-6887 (NutriGo Lipa Hub)</p>
                <p class="text-[11px] text-gray-600">I-send ang kabuuang ₱{{ number_format($totalMoneyToRemit, 2) }} at i-upload ang screenshot ng resibo sa ibaba.</p>
            </div>

            <form action="{{ route('riders.wallet.remit') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="cycle_key" value="{{ $selectedCycle['key'] }}">

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900">
                        <option value="gcash">GCash Transfer</option>
                        <option value="bank_transfer">Bank Transfer (BDO / BPI / Maya)</option>
                        <option value="office_cash">NutriGo Lipa Office (Cash Remittance)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash / Transaction Reference No. *</label>
                    <input type="text" name="reference_no" required placeholder="e.g. 1002 9384 1928" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 font-mono text-xs text-gray-900 font-bold">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">Proof of Payment / Receipt Screenshot *</label>
                    <input type="file" name="proof_image" accept="image/*" required class="w-full text-xs text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-lime-500 file:text-nutri-950 hover:file:bg-lime-600 cursor-pointer">
                    <span class="text-[10px] text-gray-400 block mt-1">PNG, JPG hanggang 5MB</span>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">Notes / Remarks for Admin (Optional)</label>
                    <input type="text" name="notes" placeholder="e.g. Paid in full for Oct 1-15 period" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openRemitModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-nutri-900 hover:bg-nutri-950 text-white font-black text-xs shadow-md transition font-heading flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-limey-400"></i> Submit Remittance Proof
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request GCash Payout Card (Withdrawal of Delivery Fees) -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-lime-200 shadow-card space-y-4">
        <h3 class="text-lg font-black font-heading text-gray-900 flex items-center gap-2">
            <i class="fa-solid fa-money-bill-transfer text-lime-600"></i> Request GCash Cashout / Withdrawal (Delivery Fees)
        </h3>
        <p class="text-xs text-gray-500">Withdraw your earned delivery fees directly into your personal GCash account</p>

        <form action="{{ route('riders.wallet.payout') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">Cashout Amount (₱) *</label>
                    <input type="number" step="1" min="100" max="{{ $wallet->balance }}" name="amount" required placeholder="Min. ₱100" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">Your GCash Number *</label>
                    <input type="text" name="gcash_number" required value="{{ $rider->phone }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-mono">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">GCash Account Name *</label>
                    <input type="text" name="gcash_account_name" required value="{{ $rider->user->name }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs">
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 uppercase mb-1">Notes for Admin (Optional)</label>
                <input type="text" name="notes" placeholder="e.g. Weekly trip payout request" class="w-full px-4 py-2 rounded-xl border border-gray-200 text-xs">
            </div>

            <button type="submit" {{ $wallet->balance < 100 ? 'disabled' : '' }} class="px-6 py-3 rounded-xl bg-lime-500 hover:bg-lime-600 disabled:bg-gray-200 text-nutri-950 font-black text-xs shadow-md transition font-heading flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i> Submit Cashout Request
            </button>
            @if($wallet->balance < 100)
                <span class="text-[11px] text-gray-400 block italic">Minimum balance to withdraw is ₱100.00</span>
            @endif
        </form>
    </div>

    <!-- Payout Requests History -->
    @if($payoutRequests->isNotEmpty())
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4">
            <h3 class="text-sm font-black font-heading uppercase tracking-wider text-gray-900">Your Withdrawal / Payout Requests</h3>
            <div class="divide-y divide-gray-100 text-xs">
                @foreach($payoutRequests as $pr)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-gray-900">₱{{ number_format($pr->amount, 2) }}</span> to {{ $pr->gcash_number }} ({{ $pr->gcash_account_name }})
                            <span class="text-[10px] text-gray-400 block">{{ $pr->requested_at->format('M d, Y • h:i A') }}</span>
                            @if($pr->admin_reference_no)
                                <span class="text-[10px] text-emerald-700 font-mono font-bold block">Admin Ref: {{ $pr->admin_reference_no }}</span>
                            @endif
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $pr->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($pr->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $pr->status }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Trip Transactions Ledger with Progressive 5-by-5 Scroll & Spinner -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-card space-y-4" 
         x-data="{
             allTx: @js($transactions),
             displayCount: 5,
             isLoading: false,
             selectedOrder: null,
             get visibleTx() {
                 return this.allTx.slice(0, this.displayCount);
             },
             get hasMore() {
                 return this.displayCount < this.allTx.length;
             },
             loadMore() {
                 if (this.isLoading || !this.hasMore) return;
                 this.isLoading = true;
                 setTimeout(() => {
                     this.displayCount += 5;
                     this.isLoading = false;
                 }, 500);
             },
             init() {
                 this.$nextTick(() => {
                     const sentinel = this.$refs.scrollSentinel;
                     if (sentinel && window.IntersectionObserver) {
                         const observer = new IntersectionObserver((entries) => {
                             if (entries[0].isIntersecting && this.hasMore && !this.isLoading) {
                                 this.loadMore();
                             }
                         }, { rootMargin: '150px' });
                         observer.observe(sentinel);
                     }
                 });
             }
         }">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black font-heading uppercase tracking-wider text-gray-900">Wallet Transactions Ledger</h3>
                <p class="text-[11px] text-gray-500">Review your delivery earnings, customer details, and delivered store items</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                    Showing <span class="text-nutri-900 font-black" x-text="Math.min(displayCount, allTx.length)"></span> of <span class="text-nutri-900 font-black" x-text="allTx.length"></span>
                </span>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="divide-y divide-gray-100 text-xs">
            <template x-for="tx in visibleTx" :key="tx.id">
                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/70 p-3 rounded-2xl transition">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-black text-gray-900 font-heading text-sm" x-text="tx.order ? ('Order #' + tx.order.order_number) : tx.description"></span>
                            <template x-if="tx.order">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i> Delivered
                                </span>
                            </template>
                            <template x-if="tx.type === 'payout_deduction'">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                    <i class="fa-solid fa-money-bill-transfer mr-1 text-amber-600"></i> Payout Cashout
                                </span>
                            </template>
                        </div>

                        <template x-if="tx.order">
                            <!-- Delivery Details Breakdown -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Pickup Store:</span>
                                    <span class="font-bold text-gray-900">
                                        <i class="fa-solid fa-store text-amber-600 mr-1"></i>
                                        <span x-text="tx.order.store ? tx.order.store.store_name : 'Store'"></span>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Customer / Destination:</span>
                                    <span class="font-bold text-gray-900">
                                        <i class="fa-solid fa-user text-nutri-700 mr-1"></i>
                                        <span x-text="tx.order.recipient_name"></span>
                                    </span>
                                    <span class="text-[11px] text-gray-600 block mt-0.5 leading-snug">
                                        <i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>
                                        <span x-text="tx.order.delivery_address"></span>
                                    </span>
                                    <template x-if="tx.order.delivery_landmark">
                                        <span class="text-[10px] text-amber-800 font-medium block mt-0.5">
                                            📍 Landmark: <span x-text="tx.order.delivery_landmark"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!tx.order">
                            <p class="text-xs text-gray-600" x-text="tx.description"></p>
                        </template>

                        <div class="flex items-center gap-3 text-[11px] text-gray-400">
                            <span><i class="fa-regular fa-clock mr-1"></i> <span x-text="tx.created_at_formatted"></span></span>
                            <template x-if="tx.order && tx.order.distance_km">
                                <span>•</span>
                            </template>
                            <template x-if="tx.order && tx.order.distance_km">
                                <span><i class="fa-solid fa-route mr-1 text-gray-500"></i> <span x-text="tx.order.distance_km + ' km'"></span></span>
                            </template>
                        </div>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 shrink-0">
                        <span class="font-black font-heading text-base" :class="tx.amount > 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="(tx.amount > 0 ? '+' : '') + '₱' + Number(tx.amount).toFixed(2)">
                        </span>

                        <template x-if="tx.order">
                            <button type="button" 
                                    @click="selectedOrder = tx.order"
                                    class="px-3 py-1.5 rounded-xl bg-nutri-50 hover:bg-nutri-100 text-nutri-800 text-[11px] font-bold border border-nutri-200 shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-receipt text-emerald-600"></i>
                                <span>View History</span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="allTx.length === 0">
                <div class="py-12 text-center text-xs text-gray-400">
                    <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300 block"></i>
                    No transactions recorded yet.
                </div>
            </template>
        </div>

        <!-- Scroll Sentinel for Infinite Scrolling Trigger -->
        <div x-ref="scrollSentinel" class="h-2 w-full"></div>

        <!-- Animated Loading Spinner when fetching next 5 -->
        <div x-show="isLoading" x-cloak class="py-5 flex flex-col items-center justify-center gap-2 text-xs font-bold text-nutri-800">
            <div class="w-8 h-8 rounded-full border-3 border-emerald-600 border-t-transparent animate-spin"></div>
            <span class="text-gray-500 font-medium">Loading 5 more transactions...</span>
        </div>

        <!-- Manual Load More Option -->
        <div x-show="hasMore && !isLoading" class="text-center pt-2">
            <button type="button" @click="loadMore()" class="px-5 py-2.5 rounded-2xl bg-nutri-50 hover:bg-nutri-100 text-nutri-800 font-extrabold text-xs border border-nutri-200 shadow-xs transition cursor-pointer flex items-center gap-2 mx-auto">
                <i class="fa-solid fa-angles-down text-emerald-600"></i>
                <span>Load 5 More Transactions</span>
            </button>
        </div>

        <!-- All Loaded Indicator -->
        <div x-show="!hasMore && allTx.length > 5" class="py-3 text-center text-[11px] text-gray-400">
            ✓ All <span x-text="allTx.length"></span> transactions loaded
        </div>

        <!-- Trip & Items History Modal -->
        <div x-show="selectedOrder !== null" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" @keydown.escape.window="selectedOrder = null">
            <div @click.outside="selectedOrder = null" class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                            Completed Trip History
                        </span>
                        <h4 class="font-black font-heading text-lg text-gray-900 mt-1" x-text="'Order #' + (selectedOrder ? selectedOrder.order_number : '')"></h4>
                    </div>
                    <button @click="selectedOrder = null" class="text-gray-400 hover:text-gray-600 cursor-pointer p-1">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <template x-if="selectedOrder">
                    <div class="space-y-4 text-xs">
                        
                        <!-- Store Info -->
                        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-1">
                            <span class="text-[10px] font-bold uppercase text-amber-800 tracking-wider block">Pickup Store</span>
                            <h5 class="font-black font-heading text-sm text-gray-900" x-text="selectedOrder.store ? selectedOrder.store.store_name : 'Store'"></h5>
                            <p class="text-gray-600" x-text="selectedOrder.store ? (selectedOrder.store.address_line || ('Brgy. ' + selectedOrder.store.barangay + ', Lipa')) : ''"></p>
                            <p class="text-gray-500 text-[11px]" x-show="selectedOrder.store && selectedOrder.store.phone">
                                <i class="fa-solid fa-phone mr-1"></i> <span x-text="selectedOrder.store ? selectedOrder.store.phone : ''"></span>
                            </p>
                        </div>

                        <!-- Customer Info -->
                        <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-1">
                            <span class="text-[10px] font-bold uppercase text-emerald-800 tracking-wider block">Delivered To Customer</span>
                            <div class="flex items-center justify-between">
                                <h5 class="font-black font-heading text-sm text-gray-900" x-text="selectedOrder.recipient_name"></h5>
                                <a :href="'tel:' + selectedOrder.recipient_phone" class="text-emerald-700 font-bold hover:underline" x-show="selectedOrder.recipient_phone">
                                    <i class="fa-solid fa-phone mr-1"></i> <span x-text="selectedOrder.recipient_phone"></span>
                                </a>
                            </div>
                            <p class="text-gray-700 font-medium"><i class="fa-solid fa-house text-rose-500 mr-1"></i> <span x-text="selectedOrder.delivery_address"></span></p>
                            <template x-if="selectedOrder.delivery_landmark">
                                <p class="p-2 rounded-xl bg-amber-100/60 text-amber-900 text-[11px] font-medium">
                                    📍 <strong>Landmark:</strong> <span x-text="selectedOrder.delivery_landmark"></span>
                                </p>
                            </template>
                            <template x-if="selectedOrder.delivery_notes">
                                <p class="p-2 rounded-xl bg-white text-gray-600 text-[11px] italic border border-gray-200">
                                    📝 <strong>Notes:</strong> <span x-text="selectedOrder.delivery_notes"></span>
                                </p>
                            </template>
                        </div>

                        <!-- Delivered Items List -->
                        <div class="space-y-2">
                            <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider block">Delivered Menu Items</span>
                            <div class="divide-y divide-gray-100 rounded-2xl border border-gray-200 overflow-hidden bg-white">
                                <template x-for="item in (selectedOrder.items || [])" :key="item.id">
                                    <div class="p-3 flex items-center justify-between text-xs hover:bg-gray-50/50">
                                        <div class="space-y-0.5">
                                            <span class="font-bold text-gray-900" x-text="item.product_name"></span>
                                            <span class="text-[10px] text-gray-400 block" x-show="item.calories" x-text="item.calories + ' kcal each'"></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-bold text-gray-700" x-text="item.quantity + 'x • ₱' + Number(item.price).toFixed(2)"></span>
                                            <span class="font-black text-gray-900 block" x-text="'₱' + Number(item.subtotal).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Trip Financial Summary -->
                        <div class="p-4 rounded-2xl bg-nutri-950 text-white space-y-2">
                            <div class="flex justify-between text-[11px] text-nutri-300">
                                <span>Trip Distance</span>
                                <span class="font-bold text-white" x-text="selectedOrder.distance_km + ' km'"></span>
                            </div>
                            <div class="flex justify-between text-[11px] text-nutri-300">
                                <span>Order Total Amount</span>
                                <span class="font-bold text-white" x-text="'₱' + Number(selectedOrder.total_amount).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-xs pt-2 border-t border-nutri-800 text-limey-400 font-bold">
                                <span>Rider Delivery Fee Earned</span>
                                <span class="text-sm font-black font-heading" x-text="'₱' + Number(selectedOrder.delivery_fee).toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Doorstep Delivery Proof Photo -->
                        <template x-if="selectedOrder.rider_proof_image">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider block">Doorstep Proof Photo</span>
                                <div class="rounded-2xl overflow-hidden border border-gray-200 max-h-48">
                                    <img :src="selectedOrder.rider_proof_image" alt="Proof of delivery" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="selectedOrder = null" class="w-full py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition cursor-pointer">
                            Close History
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection
