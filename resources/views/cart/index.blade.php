@extends('layouts.app')

@section('title', 'Your Cart — SPORT.CPE')

@section('content')
<main class="max-w-[1140px] w-full mx-auto px-6 py-6 flex-1">
    <!-- Breadcrumb -->
    <div class="text-xs text-slate-400 font-medium mb-4 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-slate-700">Home</a> <span>&gt;</span> <span class="text-slate-700 font-semibold">Cart Page</span>
    </div>

    <h1 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">YOUR CART</h1>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        <!-- Left: Dynamic Cart Items inside ONE White Card (Matching Figma Frame 7) -->
        <div class="md:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm divide-y divide-slate-200/80">
            @if(empty($cart))
                <div class="py-12 text-center">
                    <div class="text-4xl mb-3">🛒</div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">ยังไม่มีสินค้าในตะกร้าของคุณ</h3>
                    <p class="text-xs text-slate-500 mb-5">เลือกดูอุปกรณ์กีฬาคุณภาพสูงมากมาย ทั้งวิ่ง แบดมินตัน บาสเกตบอล และแคมป์ปิ้ง</p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('home') }}" class="bg-[#0b1329] text-white font-bold px-5 py-2.5 rounded-xl text-xs hover:bg-slate-800 transition">ไปเลือกซื้อสินค้า →</a>
                    </div>
                </div>
            @else
                @foreach($cart as $id => $item)
                    @php
                        $isBogo = !empty($item['promo_eligible']);
                        $freeQty = $item['free_qty'] ?? 0;
                        $lineTotal = ($item['price'] * $item['quantity']) - ($item['discount_amount'] ?? 0);
                        $img = Str::startsWith($item['image_url'], '/') ? $item['image_url'] : '/' . $item['image_url'];
                    @endphp
                    <div class="py-5 first:pt-1 last:pb-1 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('products.show', $id) }}" class="w-24 h-24 rounded-2xl bg-[#f1f5f9] flex items-center justify-center p-2.5 shrink-0 border border-slate-200/60 hover:opacity-90 transition">
                                <img src="{{ $img }}" alt="{{ $item['name'] }}" class="max-h-full max-w-full object-contain">
                            </a>
                            <div>
                                <a href="{{ route('products.show', $id) }}" class="font-bold text-slate-900 text-base hover:text-[#ff6b00] transition">{{ $item['name'] }}</a>
                                <p class="text-xs text-slate-500 mt-0.5">Brand: {{ $item['brand'] }}</p>

                                @if($isBogo)
                                    <div class="mt-1.5">
                                        @if($freeQty > 0)
                                            <span class="inline-block bg-[#dcfce7] text-[#166534] border border-green-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full">
                                                🏷️ Buy 2 Get 1 Free Eligible • ซื้อ {{ $item['quantity'] }} ชิ้น แถมฟรี {{ $freeQty }} ชิ้นทันที! (รับทั้งหมด {{ $item['quantity'] + $freeQty }} ชิ้น • ประหยัด ฿{{ number_format($item['discount_amount'] ?? 0) }})
                                            </span>
                                        @else
                                            <span class="inline-block bg-[#dcfce7] text-[#166534] border border-green-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full">
                                                🏷️ Buy 2 Get 1 Free Eligible (ซื้อชิ้นนี้เพิ่มอีก {{ 2 - ($item['quantity'] % 2) }} ชิ้น ครบ 2 ชิ้น รับแถมฟรี 1 ชิ้นทันที!)
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-baseline gap-2 mt-2">
                                    <span class="font-extrabold text-slate-900 text-lg">฿{{ number_format($item['price']) }}</span>
                                    @if($freeQty > 0)
                                        <span class="text-xs text-slate-500 font-medium">(ซื้อ {{ $item['quantity'] }} ชิ้น + <strong class="text-[#16a34a]">แถมฟรี {{ $freeQty }} ชิ้น</strong> = รับรวม {{ $item['quantity'] + $freeQty }} ชิ้น | ชำระเพียง <strong class="text-[#166534]">฿{{ number_format($lineTotal) }}</strong>)</span>
                                    @elseif($item['quantity'] > 1)
                                        <span class="text-xs text-slate-500 font-medium">(รวม {{ $item['quantity'] }} ชิ้น: ฿{{ number_format($lineTotal) }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end justify-between self-stretch py-0.5">
                            <form action="{{ route('cart.destroy', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="ลบสินค้านี้" class="w-7 h-7 rounded-full bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-700 flex items-center justify-center text-xs transition">
                                    🗑️
                                </button>
                            </form>

                            <div class="flex items-center border border-slate-300 rounded-full px-3 py-1 text-xs font-bold bg-white shadow-sm gap-3 select-none">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                                    <button type="submit" class="text-slate-500 hover:text-black text-sm px-1 font-extrabold">−</button>
                                </form>
                                <span class="text-slate-900 min-w-[16px] text-center">{{ $item['quantity'] }}</span>
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button type="submit" class="text-slate-500 hover:text-black text-sm px-1 font-extrabold">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Right: Dynamic Order Summary Card (Matching Figma Frame 7) -->
        <div class="md:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
            <h2 class="font-bold text-slate-900 text-lg mb-4">Order Summary</h2>

            <div class="space-y-3 text-sm border-b border-slate-200 pb-4 mb-4">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900">฿{{ number_format($subtotal) }}</span>
                </div>
                <div class="flex justify-between text-[#e11d48] font-medium">
                    <span>Discount</span>
                    <span class="font-bold">-฿{{ number_format($totalDiscount) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Delivery Fee</span>
                    <span class="font-bold text-slate-900">Free</span>
                </div>
            </div>

            <div class="flex justify-between items-center mb-5">
                <span class="font-bold text-slate-900 text-base">Total</span>
                <span class="text-2xl font-black text-slate-900">฿{{ number_format($netTotal) }}</span>
            </div>

            <!-- Promo Code Input & Apply Button -->
            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex items-center gap-2 mb-4">
                @csrf
                <div class="relative flex-1">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🏷️</span>
                    <input 
                        type="text" 
                        name="coupon_code"
                        placeholder="Add promo code" 
                        value="{{ session('coupon_code') }}"
                        class="w-full border border-slate-300 rounded-xl pl-8 pr-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-slate-800 uppercase"
                    >
                </div>
                <button type="submit" class="bg-[#0b1329] hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-xs transition shrink-0">
                    Apply
                </button>
            </form>

            @if(session('coupon_code'))
                <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 text-xs text-emerald-800 mb-4">
                    <span>โค้ด <strong>{{ session('coupon_code') }}</strong> ถูกใช้งานแล้ว</span>
                    <form action="{{ route('cart.coupon.remove') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-rose-600 hover:underline font-bold">ยกเลิก</button>
                    </form>
                </div>
            @endif

            @if(!empty($cart))
                <a href="{{ route('checkout.index') }}" class="w-full bg-[#0b1329] hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-sm">
                    <span>Go to Checkout →</span>
                </a>
            @else
                <button disabled class="w-full bg-slate-300 text-white font-bold py-3.5 rounded-xl text-xs flex items-center justify-center gap-2 cursor-not-allowed">
                    <span>Go to Checkout →</span>
                </button>
            @endif
        </div>
    </div>
</main>
@endsection
