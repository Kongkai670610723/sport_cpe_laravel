@extends('layouts.app')

@section('title', 'ตะกร้าสินค้า — SPORT.CPE')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
            <span>🛒 ตะกร้าสินค้า</span>
            <span class="text-sm font-normal text-slate-500">({{ count($cart) }} รายการ)</span>
        </h1>
        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-accentOrange hover:underline">
            ← เลือกซื้อสินค้าต่อ
        </a>
    </div>

    @if(empty($cart))
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-4">
            <span class="text-6xl block">🛍️</span>
            <h2 class="text-xl font-bold text-slate-800">ยังไม่มีสินค้าในตะกร้าของคุณ</h2>
            <p class="text-slate-500 text-sm max-w-md mx-auto">เลือกดูอุปกรณ์กีฬาคุณภาพสูงมากมาย ทั้งวิ่ง แบดมินตัน บาสเกตบอล และแคมป์ปิ้ง</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-accentOrange hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-xl transition shadow">
                เริ่มช้อปสินค้าเลย
            </a>
        </div>
    @else
        <!-- BOGO Notice Banner -->
        @if($bogoDiscount > 0)
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 text-white p-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <div>
                        <p class="font-black text-sm">คุณได้รับสิทธิ์โปรโมชัน "ซื้อ 2 แถม 1 ฟรี!"</p>
                        <p class="text-xs text-orange-100">ระบบหักส่วนลดสินค้าฟรีให้อัตโนมัติ ประหยัดไปได้ ฿{{ number_format($bogoDiscount, 2) }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Cart Items List -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-center gap-4">
                            <!-- Image -->
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-xl border border-slate-100 bg-slate-50 flex-shrink-0">

                            <!-- Info -->
                            <div class="flex-1 space-y-1 w-full text-center sm:text-left">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item['brand'] }}</span>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">{{ $item['name'] }}</h3>
                                <div class="flex flex-wrap items-center gap-2 pt-1 justify-center sm:justify-start">
                                    <span class="font-black text-slate-900">฿{{ number_format($item['price'], 2) }}</span>
                                    @if(!empty($item['promo_eligible']))
                                        <span class="text-[10px] bg-accentOrange/10 border border-accentOrange text-accentOrange px-2 py-0.5 rounded-md font-bold">
                                            ร่วมโปร 2 แถม 1
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($item['free_qty']) && $item['free_qty'] > 0)
                                    <p class="text-xs text-emerald-600 font-bold pt-1">
                                        🎁 ฟรี {{ $item['free_qty'] }} ชิ้น (ลด ฿{{ number_format($item['discount_amount'], 2) }})
                                    </p>
                                @endif
                            </div>

                            <!-- Quantity Form & Total -->
                            <div class="flex items-center gap-4">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-1.5">
                                    @csrf
                                    @method('PUT')
                                    <label class="sr-only">จำนวน</label>
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="{{ $item['quantity'] }}" 
                                        min="1" 
                                        max="50"
                                        onchange="this.form.submit()" 
                                        class="w-16 bg-slate-50 border border-slate-300 rounded-lg px-2 py-1 text-center font-bold text-sm text-slate-800 focus:outline-none focus:border-accentOrange"
                                    >
                                </form>

                                <div class="text-right w-24">
                                    <p class="text-sm font-black text-slate-900">
                                        ฿{{ number_format(($item['price'] * $item['quantity']) - ($item['discount_amount'] ?? 0), 2) }}
                                    </p>
                                </div>

                                <!-- Delete Form -->
                                <form action="{{ route('cart.destroy', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 p-1.5 transition" title="ลบสินค้านี้">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                <h2 class="text-lg font-extrabold text-slate-900 border-b border-slate-100 pb-3">สรุปคำสั่งซื้อ</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>ยอดรวมสินค้า (Subtotal)</span>
                        <span class="font-bold text-slate-900">฿{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($bogoDiscount > 0)
                        <div class="flex justify-between text-accentOrange font-medium">
                            <span>ส่วนลดโปร 2 แถม 1</span>
                            <span>-฿{{ number_format($bogoDiscount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600">
                        <span>ค่าจัดส่ง (Shipping)</span>
                        <span class="text-emerald-600 font-bold">{{ $subtotal >= 1000 ? 'ฟรี (ครบ ฿1,000)' : '฿50.00' }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="font-extrabold text-slate-900">ยอดชำระสุทธิ</span>
                        <span class="text-2xl font-black text-slate-900">฿{{ number_format($total + ($subtotal >= 1000 ? 0 : 50), 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full bg-accentOrange hover:bg-orange-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <span>ดำเนินการชำระเงิน →</span>
                </a>

                <p class="text-[11px] text-slate-400 text-center">
                    🔒 ปลอดภัยด้วยมาตรฐานระบบฐานข้อมูล ACID Transactions
                </p>
            </div>
        </div>
    @endif

</div>
@endsection

