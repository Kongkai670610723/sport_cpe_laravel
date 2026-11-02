@extends('layouts.app')

@section('title', ($currentCategory ? $currentCategory->category_name : 'Products') . ' — SPORT.CPE')

@section('content')
<main class="max-w-[1040px] w-full mx-auto px-6 py-8 flex-1">
    <!-- Category Header Row Matching Figma Frame 2 -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900" id="cat-heading">
                @if(request('search'))
                    Search Results for "{{ request('search') }}"
                @elseif($currentCategory)
                    {{ $currentCategory->category_name }} Equipment
                @else
                    All Equipment
                @endif
                <span class="font-normal text-slate-600 text-lg">({{ $products->total() }} items)</span>
            </h1>
            <p id="cat-description" class="text-xs text-slate-500 font-medium mt-1">
                @if(request('search'))
                    ผลการค้นหาอุปกรณ์กีฬาที่ตรงกับคำค้นหา "{{ request('search') }}"
                @elseif($currentCategory && $currentCategory->description)
                    {{ $currentCategory->description }}
                @else
                    ค้นหาและเลือกซื้ออุปกรณ์กีฬาของแท้ 100% พร้อมการรับประกันและบริการจัดส่งรวดเร็ว
                @endif
            </p>
        </div>

        <div class="flex items-center gap-5 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-slate-600 font-medium">Sort by:</span>
                <select id="sort-select" onchange="window.location.href = this.value" class="bg-white border border-slate-300 rounded-md px-3 py-1.5 font-medium text-slate-800 focus:outline-none">
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'default', 'page' => 1])) }}" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Featured (Default)</option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'asc', 'page' => 1])) }}" {{ request('sort') === 'asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'desc', 'page' => 1])) }}" {{ request('sort') === 'desc' ? 'selected' : '' }}>Price (High to Low)</option>
                </select>
            </div>

            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" id="promo-toggle" onchange="window.location.href = this.checked ? '{{ route('products.index', array_merge(request()->query(), ['promo' => 'bogo', 'page' => 1])) }}' : '{{ route('products.index', array_diff_key(request()->query(), ['promo' => ''])) }}'" {{ request('promo') === 'bogo' ? 'checked' : '' }} class="sr-only peer">
                <div class="w-9 h-5 bg-slate-300 peer-checked:bg-[#ff6b00] rounded-full relative transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:w-4 after:h-4 after:rounded-full after:transition peer-checked:after:translate-x-4"></div>
                <span class="text-slate-700 font-medium">Buy 2 Get 1 Free Eligible</span>
            </label>
        </div>
    </div>

    <!-- 12 Product Cards Grid with Dark Navy Add to Cart Button (Matching Figma Frame 2) -->
    <div id="category-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        @forelse($products as $p)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <a href="{{ route('products.show', $p->product_id) }}" class="block group">
                    <div class="h-40 rounded-xl bg-white flex items-center justify-center p-2 mb-3 overflow-hidden">
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
                        <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white font-semibold px-3.5 py-1.5 rounded-lg text-xs transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 bg-white rounded-2xl border border-slate-200">
                <p class="text-4xl mb-2">🔍</p>
                <p class="font-bold text-slate-700">ไม่พบสินค้าตามเงื่อนไขที่เลือก</p>
                <a href="{{ route('products.index', ['category' => $categorySlug]) }}" class="inline-block mt-3 text-xs font-semibold text-[#ff6b00] hover:underline">ล้างตัวกรอง</a>
            </div>
        @endforelse
    </div>

    <!-- Category Pagination Matching Exact Specification -->
    @if($products->hasPages())
        <div id="category-pagination" class="flex items-center justify-center gap-2 text-sm text-slate-800 font-semibold py-4 select-none">
            @if(!$products->onFirstPage())
                <a href="{{ $products->previousPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-slate-200 text-slate-700 font-bold flex items-center gap-1 mr-1">‹ Prev</a>
            @endif

            <span class="text-slate-500 font-medium mr-1">Page</span>

            @for($i = 1; $i <= $products->lastPage(); $i++)
                @if($i == $products->currentPage())
                    <span class="w-8 h-8 rounded-md bg-[#0b1329] text-white font-bold flex items-center justify-center shadow-sm">{{ $i }}</span>
                @else
                    <a href="{{ $products->url($i) }}" class="px-2 py-1 rounded hover:bg-slate-200 text-slate-800 font-semibold transition">{{ $i }}</a>
                @endif
            @endfor

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-slate-200 text-slate-900 font-bold flex items-center gap-1 ml-1">Next ›</a>
            @endif
        </div>
    @endif
</main>
@endsection
