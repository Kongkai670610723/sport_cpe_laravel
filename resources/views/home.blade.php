@extends('layouts.app')

@section('title', 'HomePage — SPORT.CPE')

@section('content')
<!-- Top Dark Navy Hero Banner Section (Transitions seamlessly from navbar) -->
<div class="bg-[#0b1329] text-white pb-10">
    <div class="max-w-[1040px] mx-auto px-6 mt-7">
        <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-white select-none">
            <img src="/images/figma/hero_banner_full.png" alt="BUY 2 GET 1 FREE - SAVE15 PERKS20 Shop Voucher" class="w-full h-auto block">
            <!-- Interactive Clickable Hotspots over SAVE15, PERKS20, and Shop Voucher -->
            <a href="{{ route('vouchers.index', ['claim' => 'SAVE15']) }}" class="absolute top-[67%] left-[12%] w-[16%] h-[20%] rounded-lg cursor-pointer transition hover:ring-2 hover:ring-white/80" title="Claim SAVE15 Voucher"></a>
            <a href="{{ route('vouchers.index', ['claim' => 'PERKS20']) }}" class="absolute top-[67%] left-[32%] w-[16%] h-[20%] rounded-lg cursor-pointer transition hover:ring-2 hover:ring-white/80" title="Claim PERKS20 Voucher"></a>
            <a href="{{ route('vouchers.index') }}" class="absolute top-[75%] right-[5%] w-[18%] h-[16%] rounded-lg cursor-pointer transition hover:ring-2 hover:ring-white/80" title="Go to Voucher Center"></a>
        </div>
    </div>
</div>

<!-- Main Gray Catalog Section Matching Figma Frame 1 Specifications -->
<div class="bg-[#f1f5f9] flex-1 py-10" id="home-catalog-section">
    <div class="max-w-[1040px] mx-auto px-6">
        
        <!-- Section Header & Filter Toolbar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
            <div class="flex items-baseline gap-3 flex-wrap">
                <h2 class="text-2xl font-black text-slate-900" id="home-section-title">
                    Featured Products
                    @if(request('promo') === 'bogo')
                        <span class="text-sm font-extrabold text-[#166534] bg-[#dcfce7] border border-green-300 px-3 py-0.5 rounded-full align-middle ml-1">🏷️ รายการโปรโมชั่น Buy 2 Get 1 Free ({{ $products->total() }} รายการ)</span>
                    @endif
                </h2>
                <span class="text-xs font-semibold text-slate-500" id="home-page-indicator">
                    @if($products->total() > 0)
                        Showing {{ ($products->currentPage() - 1) * $products->perPage() + 1 }}–{{ min($products->currentPage() * $products->perPage(), $products->total()) }} of {{ $products->total() }} items (Page {{ $products->currentPage() }} of {{ $products->lastPage() }})
                    @else
                        Showing 0 items
                    @endif
                </span>
            </div>

            <!-- Sort by & Buy 2 Get 1 Free Toggle (Matching Category Pages) -->
            <div class="flex flex-wrap items-center gap-5 text-xs">
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 font-medium">Sort by:</span>
                    <select id="home-sort-select" onchange="window.location.href = this.value" class="bg-white border border-slate-300 rounded-md px-3 py-1.5 font-medium text-slate-800 focus:outline-none">
                        <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'default', 'page' => 1])) }}" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Featured (Default)</option>
                        <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'asc', 'page' => 1])) }}" {{ request('sort') === 'asc' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'desc', 'page' => 1])) }}" {{ request('sort') === 'desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    </select>
                </div>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="home-promo-toggle" onchange="window.location.href = this.checked ? '{{ route('home', array_merge(request()->query(), ['promo' => 'bogo', 'page' => 1])) }}' : '{{ route('home', array_diff_key(request()->query(), ['promo' => ''])) }}'" {{ request('promo') === 'bogo' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-9 h-5 bg-slate-300 peer-checked:bg-[#ff6b00] rounded-full relative transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:w-4 after:h-4 after:rounded-full after:transition peer-checked:after:translate-x-4"></div>
                    <span class="text-slate-700 font-medium">Buy 2 Get 1 Free Eligible</span>
                </label>
            </div>
        </div>

        <!-- 12 Cards Grid (4 Columns x 3 Rows) -->
        <div id="home-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
            @forelse($products as $p)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <a href="{{ route('products.show', $p->product_id) }}" class="block group">
                        <div class="h-44 rounded-xl bg-white flex items-center justify-center p-2 mb-3 overflow-hidden">
                            <img src="{{ Str::startsWith($p->image_url, '/') ? $p->image_url : '/' . $p->image_url }}" alt="{{ $p->name }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-200">
                        </div>
                        <div class="mb-2 min-h-[20px]">
                            @if($p->promotions->contains('promo_code', 'BUY2GET1'))
                                <span class="inline-block bg-[#dcfce7] text-[#166534] border border-green-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full">🏷️ Buy 2 Get 1 Free</span>
                            @endif
                        </div>
                        <h3 class="font-medium text-slate-900 text-sm mb-3 leading-snug min-h-[40px] group-hover:text-[#ff6b00] transition">{{ $p->name }}</h3>
                    </a>
                    <div class="flex items-center justify-between pt-1">
                        <span class="font-bold text-slate-900 text-sm">{{ number_format($p->price) }} Baht</span>
                        <form action="{{ route('cart.store') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-[#22c55e] hover:bg-green-600 text-white font-semibold px-3.5 py-1.5 rounded-lg text-xs transition shadow-sm">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500 bg-white rounded-2xl border border-slate-200">
                    <p class="text-4xl mb-2">🔍</p>
                    <p class="font-bold text-slate-700">ไม่พบสินค้าตามเงื่อนไขที่เลือก</p>
                    <a href="{{ route('home') }}" class="inline-block mt-3 text-xs font-semibold text-[#ff6b00] hover:underline">ล้างตัวกรองทั้งหมด</a>
                </div>
            @endforelse
        </div>

        <!-- Dynamic Sliding Pagination Matching Exact Figma Specification -->
        @if($products->hasPages())
            <div id="home-pagination" class="flex items-center justify-center gap-2 text-sm text-slate-800 font-semibold py-4 select-none">
                @if(!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-slate-200 text-slate-700 font-bold flex items-center gap-1 mr-1">‹ Prev</a>
                @endif

                <span class="text-slate-500 font-medium mr-1">Page</span>

                @php
                    $currentPage = $products->currentPage();
                    $totalPages = $products->lastPage();
                @endphp

                @if($totalPages <= 5)
                    @for($i = 1; $i <= $totalPages; $i++)
                        @if($i == $currentPage)
                            <span class="w-8 h-8 rounded-md bg-[#0b1329] text-white font-bold flex items-center justify-center shadow-sm">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $i }}</a>
                        @endif
                    @endfor
                @elseif($currentPage <= 2)
                    @for($i = 1; $i <= 3; $i++)
                        @if($i == $currentPage)
                            <span class="w-8 h-8 rounded-md bg-[#0b1329] text-white font-bold flex items-center justify-center shadow-sm">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $i }}</a>
                        @endif
                    @endfor
                    <span class="text-slate-400 px-1 tracking-widest">......</span>
                    <a href="{{ $products->url($totalPages - 1) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $totalPages - 1 }}</a>
                    <a href="{{ $products->url($totalPages) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $totalPages }}</a>
                @elseif($currentPage <= $totalPages - 3)
                    <a href="{{ $products->url($currentPage - 1) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $currentPage - 1 }}</a>
                    <span class="w-8 h-8 rounded-md bg-[#0b1329] text-white font-bold flex items-center justify-center shadow-sm">{{ $currentPage }}</span>
                    <a href="{{ $products->url($currentPage + 1) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $currentPage + 1 }}</a>
                    <span class="text-slate-400 px-1 tracking-widest">......</span>
                    <a href="{{ $products->url($totalPages - 1) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $totalPages - 1 }}</a>
                    <a href="{{ $products->url($totalPages) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $totalPages }}</a>
                @else
                    <a href="{{ $products->url(1) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">1</a>
                    <a href="{{ $products->url(2) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">2</a>
                    <span class="text-slate-400 px-1 tracking-widest">......</span>
                    @for($i = $totalPages - 2; $i <= $totalPages; $i++)
                        @if($i == $currentPage)
                            <span class="w-8 h-8 rounded-md bg-[#0b1329] text-white font-bold flex items-center justify-center shadow-sm">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $i }}</a>
                        @endif
                    @endfor
                @endif

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-slate-200 text-slate-900 font-bold flex items-center gap-1 ml-1">Next ›</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
