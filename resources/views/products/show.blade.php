@extends('layouts.app')

@section('title', $product->name . ' — SPORT.CPE')

@section('content')
<main class="max-w-[1140px] w-full mx-auto px-6 py-6 flex-1">
    <!-- Dynamic Breadcrumb -->
    <div class="text-xs text-slate-400 font-medium mb-5 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-slate-700">Home</a>
        <span>&gt;</span>
        <a href="{{ route('products.index', ['category' => strtolower($product->category->category_name)]) }}" class="hover:text-slate-700">
            {{ $product->category->category_name }}
        </a>
        <span>&gt;</span>
        <span class="text-slate-800 font-semibold truncate">{{ $product->name }}</span>
    </div>

    <!-- 2-Column Product Detail Stage Matching Figma Frame 3 -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start mb-10">
        <!-- Left Column (6 cols): Main Studio Image & 4 Interactive Thumbnails -->
        <div class="md:col-span-6">
            @php
                $mainImg = Str::startsWith($product->image_url, '/') ? $product->image_url : '/' . $product->image_url;
                if ($product->product_id == 17) {
                    $mainImg = '/images/figma/detail_astrox_main.png';
                    $thumbs = [
                        '/images/figma/detail_astrox_main.png',
                        '/images/figma/detail_astrox_thumb1.png',
                        '/images/figma/detail_astrox_thumb2.png',
                        '/images/figma/detail_astrox_thumb3.png',
                    ];
                } else {
                    $thumbs = [$mainImg, $mainImg, $mainImg, $mainImg];
                }
            @endphp
            <div class="bg-white rounded-2xl p-8 h-[390px] flex items-center justify-center border border-slate-200/80 shadow-sm mb-4">
                <img id="detail-main-img" src="{{ $mainImg }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain transition duration-200">
            </div>
            <div class="grid grid-cols-4 gap-4" id="detail-thumbs-row">
                @foreach($thumbs as $idx => $t)
                    <button type="button" onclick="document.getElementById('detail-main-img').src='{{ $t }}'" class="bg-white rounded-xl border border-slate-200 p-2 h-20 flex items-center justify-center hover:border-[#ff6b00] transition">
                        <img src="{{ $t }}" alt="Thumb {{ $idx + 1 }}" class="max-h-full max-w-full object-contain">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Right Column (6 cols): Info, Quantity, Actions & Technical Specifications Table -->
        <div class="md:col-span-6">
            <span class="inline-block bg-[#0b1329] text-white text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider mb-3">
                {{ $product->brand ?? 'SPORT.CPE' }}
            </span>

            <h1 class="text-3xl font-black text-slate-900 leading-tight mb-2">
                {{ $product->name }}
            </h1>

            <div class="text-2xl font-black text-slate-900 mb-3">
                {{ number_format($product->price) }} Baht
            </div>

            <!-- Promo & Stock Badges -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($product->promotions->contains('promo_code', 'BUY2GET1'))
                    <span class="bg-[#dcfce7] text-[#166534] border border-green-300 text-xs font-extrabold px-3.5 py-1 rounded-full">
                        🏷️ Buy 2 Get 1 Free Eligible (ซื้อชิ้นนี้ครบ 2 ชิ้น รับชิ้นที่ 3 ฟรีทันที)
                    </span>
                @endif
                <span class="text-xs font-semibold text-slate-800 flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-[#22c55e] text-white text-[10px] flex items-center justify-center font-bold">✓</span>
                    <span>In Stock: {{ $product->stock_quantity }} items</span>
                </span>
            </div>

            <!-- Product Description -->
            <p class="text-xs text-slate-600 leading-relaxed mb-5">
                {{ $product->description }}
            </p>

            <!-- Quantity & Forms -->
            <form action="{{ route('cart.store') }}" method="POST" id="detail-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                <div class="flex items-center gap-3 mb-5 text-xs">
                    <span class="font-semibold text-slate-700">Quantity:</span>
                    <div class="inline-flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden">
                        <button type="button" onclick="const q = document.getElementById('qty-input'); if(q.value > 1) q.value--;" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold">−</button>
                        <input id="qty-input" type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock_quantity) }}" class="w-12 text-center font-bold text-slate-900 focus:outline-none py-1.5 border-x border-slate-200">
                        <button type="button" onclick="const q = document.getElementById('qty-input'); if(q.value < {{ max(1, $product->stock_quantity) }}) q.value++;" class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold">+</button>
                    </div>
                </div>

                <!-- Action Buttons Row (Green Add to Cart + Orange Buy Now) -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button type="submit" class="bg-[#22c55e] hover:bg-green-600 text-white font-bold py-3 rounded-full text-xs flex items-center justify-center gap-2 shadow-sm transition">
                        <span>🛒 Add to Cart</span>
                    </button>
                    <button type="submit" name="buy_now" value="1" class="bg-[#ff6b00] hover:bg-orange-600 text-white font-bold py-3 rounded-full text-xs flex items-center justify-center gap-2 shadow-sm transition">
                        <span>⚡ Buy Now</span>
                    </button>
                </div>
            </form>

            <!-- Technical Specifications Table (Matching Figma Frame 3) -->
            <div class="border-t border-slate-200 pt-5">
                <h2 class="font-bold text-slate-900 text-base mb-3">Technical Specifications</h2>
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden text-xs text-slate-700 divide-y divide-slate-100">
                    <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Brand</span><span class="w-2/3 font-bold text-slate-900">{{ $product->brand }}</span></div>
                    <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Category</span><span class="w-2/3 font-bold text-slate-900">{{ $product->category->category_name }}</span></div>
                    @if($product->category->category_name === 'Badminton')
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Weight / Grip</span><span class="w-2/3 font-bold text-slate-900">4U (83g) G5</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Balance</span><span class="w-2/3 font-bold text-slate-900">Head-Heavy</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Flex</span><span class="w-2/3 font-bold text-slate-900">Extra Stiff</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Frame Material</span><span class="w-2/3 font-bold text-slate-900">HM Graphite & Namd</span></div>
                    @elseif($product->category->category_name === 'Running')
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Weight / Drop</span><span class="w-2/3 font-bold text-slate-900">245g / 10mm Heel-to-Toe Drop</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Cushioning</span><span class="w-2/3 font-bold text-slate-900">Zoom Air & React Foam</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Surface</span><span class="w-2/3 font-bold text-slate-900">Road & Track Running</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Upper Material</span><span class="w-2/3 font-bold text-slate-900">Engineered Breathable Mesh</span></div>
                    @else
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Material / Quality</span><span class="w-2/3 font-bold text-slate-900">Professional Competition Grade</span></div>
                        <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Certification</span><span class="w-2/3 font-bold text-slate-900">Official International Standard</span></div>
                    @endif
                    <div class="flex py-2.5 px-4"><span class="w-1/3 font-semibold text-slate-500">Warranty</span><span class="w-2/3 font-bold text-emerald-600">Official 6-Month Manufacturer Warranty</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="border-t border-slate-200 pt-8 mt-10">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Related Products in {{ $product->category->category_name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($relatedProducts as $rel)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <a href="{{ route('products.show', $rel->product_id) }}" class="block group">
                            <div class="h-40 rounded-xl bg-white flex items-center justify-center p-2 mb-3 overflow-hidden">
                                <img src="{{ Str::startsWith($rel->image_url, '/') ? $rel->image_url : '/' . $rel->image_url }}" alt="{{ $rel->name }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-200">
                            </div>
                            <h3 class="font-medium text-slate-900 text-sm mb-3 leading-snug min-h-[40px] group-hover:text-[#ff6b00] transition">{{ $rel->name }}</h3>
                        </a>
                        <div class="flex items-center justify-between pt-1">
                            <span class="font-bold text-slate-900 text-sm">{{ number_format($rel->price) }} Baht</span>
                            <form action="{{ route('cart.store') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $rel->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</main>
@endsection
