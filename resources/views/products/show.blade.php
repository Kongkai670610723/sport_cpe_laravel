@extends('layouts.app')

@section('title', $product->name . ' — SPORT.CPE')

@section('content')
<div class="space-y-10">

    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-accentOrange">หน้าแรก</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => strtolower($product->category->category_name)]) }}" class="hover:text-accentOrange">
            {{ $product->category->category_name }}
        </a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-sm">
        <!-- Main Image -->
        <div class="space-y-4">
            <div class="bg-slate-100 rounded-2xl aspect-square overflow-hidden border border-slate-200">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Info & Actions -->
        <div class="space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $product->brand }} • {{ $product->category->category_name }}</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price & BOGO Badge -->
                <div class="flex items-baseline gap-4 pt-2 border-t border-slate-100">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900">฿{{ number_format($product->price, 2) }}</span>
                    @if($product->promotions->contains('promo_code', 'BUY2GET1'))
                        <span class="bg-accentOrange/10 border border-accentOrange text-accentOrange text-xs font-bold px-3 py-1 rounded-full">
                            🎁 โปรซื้อ 2 แถม 1 อัตโนมัติ
                        </span>
                    @endif
                </div>

                <!-- Stock Quantity -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold text-slate-700">สถานะสต็อก:</span>
                    @if($product->stock_quantity > 10)
                        <span class="text-emerald-600 font-bold">✅ พร้อมส่ง (คงเหลือ {{ $product->stock_quantity }} ชิ้น)</span>
                    @elseif($product->stock_quantity > 0)
                        <span class="text-amber-600 font-bold">⚠️ เหลือเพียง {{ $product->stock_quantity }} ชิ้นสุดท้าย!</span>
                    @else
                        <span class="text-red-500 font-bold">❌ สินค้าหมดชั่วคราว</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 mb-2">รายละเอียดสินค้า</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $product->description ?? 'สินค้าอุปกรณ์กีฬาของแท้ 100% รับประกันคุณภาพจากศูนย์ตัวแทนจำหน่ายอย่างเป็นทางการ ผ่านการทดสอบตามมาตรฐานสากล' }}
                    </p>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.store') }}" method="POST" class="space-y-4 pt-6 border-t border-slate-200">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                <div class="flex items-center gap-4">
                    <div class="w-32">
                        <label for="quantity" class="block text-xs font-bold text-slate-700 mb-1">จำนวน:</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            value="1" 
                            min="1" 
                            max="{{ max(1, $product->stock_quantity) }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-center font-bold text-slate-800 focus:outline-none focus:border-accentOrange"
                        >
                    </div>

                    <div class="flex-1 pt-5">
                        <button 
                            type="submit" 
                            {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                            class="w-full bg-accentGreen hover:bg-emerald-600 disabled:bg-slate-300 text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center justify-center gap-2"
                        >
                            <span class="text-lg">🛒</span>
                            <span>เพิ่มลงในตะกร้า</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="space-y-4">
            <h2 class="text-xl font-extrabold text-slate-900">สินค้าอื่นในหมวดหมู่นี้</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($relatedProducts as $rel)
                    <a href="{{ route('products.show', $rel->product_id) }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:shadow-md transition group">
                        <div class="aspect-square bg-slate-100 rounded-lg overflow-hidden mb-2">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 truncate">{{ $rel->name }}</h4>
                        <p class="text-sm font-black text-slate-900 mt-1">฿{{ number_format($rel->price, 2) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
