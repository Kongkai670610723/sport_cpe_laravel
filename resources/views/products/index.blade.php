@extends('layouts.app')

@section('title', ($currentCategory ? $currentCategory->category_name : 'อุปกรณ์กีฬาทั้งหมด') . ' — SPORT.CPE')

@section('content')
<div class="space-y-6">

    <!-- Category Header -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-2">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🏅</span>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ $currentCategory ? $currentCategory->category_name : 'อุปกรณ์กีฬาทั้งหมด' }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    {{ $currentCategory && $currentCategory->description ? $currentCategory->description : 'ค้นหาและเลือกซื้ออุปกรณ์กีฬาของแท้ 100% พร้อมการรับประกันและบริการจัดส่งรวดเร็ว' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <!-- Category Pill Selector -->
        <div class="flex items-center gap-2 overflow-x-auto py-1">
            <a href="{{ route('products.index', ['category' => 'all']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition {{ $categorySlug === 'all' ? 'bg-primary text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                ทั้งหมด (128)
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => strtolower($cat->category_name)]) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition {{ strtolower($cat->category_name) === strtolower($categorySlug) ? 'bg-primary text-white' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                    {{ $cat->category_name }}
                </a>
            @endforeach
        </div>

        <!-- Sort & BOGO -->
        <div class="flex items-center gap-3">
            <a href="{{ request('promo') === 'bogo' ? route('products.index', ['category' => $categorySlug]) : route('products.index', ['category' => $categorySlug, 'promo' => 'bogo']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ request('promo') === 'bogo' ? 'bg-orange-50 border-accentOrange text-accentOrange' : 'bg-white border-slate-300 text-slate-700' }}">
                🎁 ซื้อ 2 แถม 1
            </a>

            <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-1.5 text-xs">
                <input type="hidden" name="category" value="{{ $categorySlug }}">
                @if(request('promo'))
                    <input type="hidden" name="promo" value="{{ request('promo') }}">
                @endif
                <select name="sort" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-medium text-slate-700 focus:outline-none focus:border-accentOrange">
                    <option value="default" {{ request('sort') === 'default' ? 'selected' : '' }}>เรียงลำดับ</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>ราคา: ต่ำ → สูง</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>ราคา: สูง → ต่ำ</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $prod)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col group">
                <a href="{{ route('products.show', $prod->product_id) }}" class="relative block bg-slate-100 aspect-square overflow-hidden">
                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @if($prod->promotions->contains('promo_code', 'BUY2GET1'))
                        <span class="absolute top-2.5 left-2.5 bg-accentOrange text-white font-black text-xs px-2.5 py-1 rounded-md shadow-sm">
                            2 แถม 1
                        </span>
                    @endif
                    <span class="absolute bottom-2 right-2 bg-slate-900/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded">
                        คงเหลือ: {{ $prod->stock_quantity }}
                    </span>
                </a>

                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $prod->brand }}</span>
                        <a href="{{ route('products.show', $prod->product_id) }}" class="block font-bold text-slate-900 hover:text-accentOrange text-sm line-clamp-2 transition">
                            {{ $prod->name }}
                        </a>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400">ราคา</span>
                            <p class="text-lg font-black text-slate-900">฿{{ number_format($prod->price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $prod->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-accentGreen hover:bg-emerald-600 text-white px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1">
                                <span>🛒 เพิ่ม</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500">
                <p class="text-4xl mb-2">🔍</p>
                <p class="font-bold">ไม่พบสินค้าในหมวดหมู่นี้</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="pt-6">
        {{ $products->appends(request()->query())->links() }}
    </div>

</div>
@endsection

