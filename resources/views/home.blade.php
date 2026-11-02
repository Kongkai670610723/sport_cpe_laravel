@extends('layouts.app')

@section('title', 'SPORT.CPE — ร้านอุปกรณ์กีฬาครบวงจร (Laravel Edition)')

@section('content')
<div class="space-y-8">

    <!-- Hero Promotional Banner -->
    <div class="relative bg-gradient-to-r from-slate-900 via-primary to-slate-800 rounded-2xl overflow-hidden shadow-xl text-white p-6 sm:p-10 border border-slate-700">
        <div class="max-w-2xl space-y-4 relative z-10">
            <span class="inline-block bg-accentOrange/90 text-white font-bold text-xs uppercase px-3 py-1 rounded-full tracking-wider">
                🔥 ซุปเปอร์โปรโมชันประจำเดือน
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                โปรสุดคุ้ม! <span class="text-accentOrange">ซื้อ 2 แถม 1</span> อุปกรณ์กีฬาแท้
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                ยกระดับการฝึกซ้อมของคุณด้วยอุปกรณ์กีฬาระดับมืออาชีพ ไม้แบดมินตัน ลูกบาส รองเท้าวิ่ง และเต็นท์แคมป์ปิ้ง พร้อมรับส่วนลดเพิ่ม 10% เมื่อช้อปครบ ฿500 ด้วยโค้ด <span class="text-accentOrange font-mono font-bold bg-black/40 px-2 py-0.5 rounded">SPORT10</span>
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="{{ route('home', ['promo' => 'bogo']) }}" class="bg-accentOrange hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center gap-2">
                    <span>🛍️ ดูสินค้า 2 แถม 1</span>
                </a>
                <a href="{{ route('vouchers.index') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2.5 rounded-xl font-semibold text-sm transition">
                    🎟️ เก็บโค้ดส่วนลด
                </a>
            </div>
        </div>
    </div>

    <!-- 8 Sports Category Quick Links -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-extrabold text-slate-900">หมวดหมู่กีฬายอดนิยม</h2>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-accentOrange hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @php
                $catIcons = [
                    'Running' => '👟',
                    'Badminton' => '🏸',
                    'Basketball' => '🏀',
                    'Football' => '⚽',
                    'Swim' => '🏊',
                    'Fitness' => '🏋️',
                    'Camping' => '⛺',
                    'Volleyball' => '🏐',
                ];
            @endphp
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => strtolower($cat->category_name)]) }}" class="bg-white p-3.5 rounded-xl border border-slate-200 hover:border-accentOrange hover:shadow-md transition text-center group">
                    <span class="text-3xl block mb-1 group-hover:scale-110 transition">{{ $catIcons[$cat->category_name] ?? '🏅' }}</span>
                    <span class="text-xs font-bold text-slate-800 block truncate">{{ $cat->category_name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Product Showcase with Filter and Sort -->
    <section class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    @if(request('promo') === 'bogo')
                        🎁 สินค้าร่วมรายการ "ซื้อ 2 แถม 1"
                    @else
                        ⚡ สินค้าแนะนำ (Featured Products)
                    @endif
                </h2>
                <p class="text-xs text-slate-500">แสดง {{ $products->count() }} จากทั้งหมด {{ $products->total() }} รายการ</p>
            </div>

            <!-- Controls: Filter & Sort -->
            <div class="flex items-center gap-3 flex-wrap">
                <!-- BOGO Toggle -->
                <a href="{{ request('promo') === 'bogo' ? route('home') : route('home', ['promo' => 'bogo']) }}" 
                   class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ request('promo') === 'bogo' ? 'bg-orange-50 border-accentOrange text-accentOrange' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                    <span>🎁 ซื้อ 2 แถม 1</span>
                    @if(request('promo') === 'bogo')
                        <span class="text-xs">✕ ล้างตัวกรอง</span>
                    @endif
                </a>

                <!-- Sort Dropdown Form -->
                <form action="{{ route('home') }}" method="GET" class="flex items-center gap-1.5 text-xs">
                    @if(request('promo'))
                        <input type="hidden" name="promo" value="{{ request('promo') }}">
                    @endif
                    <label for="sort" class="font-bold text-slate-600">จัดเรียง:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-medium text-slate-700 focus:outline-none focus:border-accentOrange">
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>สินค้าแนะนำ</option>
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
                    <!-- Product Image -->
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

                    <!-- Product Info -->
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

                            <!-- Pure Laravel Form to Add to Cart -->
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
                <div class="col-span-full py-12 text-center text-slate-500">
                    <p class="text-4xl mb-2">🔍</p>
                    <p class="font-bold">ไม่พบสินค้าตามเงื่อนไขที่เลือก</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-6">
            {{ $products->links() }}
        </div>
    </section>

</div>
@endsection
