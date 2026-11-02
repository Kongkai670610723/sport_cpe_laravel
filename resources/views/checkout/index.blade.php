@extends('layouts.app')

@section('title', 'ชำระเงิน — SPORT.CPE')

@section('content')
<div class="space-y-6">

    <div class="flex items-center gap-2">
        <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-slate-500 hover:text-accentOrange">← กลับไปตะกร้าสินค้า</a>
    </div>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
        📦 ตรวจสอบข้อมูลและชำระเงิน (Checkout)
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left Column: Checkout Details Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Voucher Input Box -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>🎟️</span> โค้ดส่วนลดโปรโมชัน (Voucher Code)
                </h3>
                <form action="{{ route('checkout.index') }}" method="GET" class="flex gap-2">
                    <input 
                        type="text" 
                        name="code" 
                        value="{{ $appliedCode }}" 
                        placeholder="กรอกรหัสส่วนลด เช่น SPORT10, SAVE15" 
                        class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm uppercase font-mono font-bold focus:outline-none focus:border-accentOrange"
                    >
                    <button type="submit" class="bg-primary hover:bg-slate-800 text-white font-bold text-xs px-5 py-2 rounded-xl transition">
                        ใช้โค้ด
                    </button>
                </form>

                @if($activePromotion)
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-3 rounded-xl text-xs flex items-center justify-between">
                        <span>✅ ใช้งานโค้ด <strong>{{ $activePromotion->promo_code }}</strong> ({{ $activePromotion->promo_name }}) สำเร็จ!</span>
                        <a href="{{ route('checkout.index') }}" class="text-red-500 hover:underline font-bold">ยกเลิก</a>
                    </div>
                @elseif(!empty($appliedCode))
                    <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-xs">
                        ⚠️ โค้ดส่วนลดไม่ถูกต้อง หรือยอดสั่งซื้อไม่ถึงขั้นต่ำ ฿{{ $subtotal }}
                    </div>
                @endif
            </div>

            <!-- Shipping & Payment Form -->
            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form" class="space-y-6">
                @csrf
                <input type="hidden" name="promo_code" value="{{ $appliedCode }}">

                <!-- Shipping Address -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>📍</span> ที่อยู่ในการจัดส่งสินค้า
                    </h3>
                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-slate-700 mb-1">ที่อยู่จัดส่ง:</label>
                        <textarea 
                            name="shipping_address" 
                            id="shipping_address" 
                            rows="3" 
                            required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:border-accentOrange"
                        >{{ Auth::check() ? Auth::user()->address : 'ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มหาวิทยาลัยเชียงใหม่ 239 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200' }}</textarea>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>💳</span> ช่องทางการชำระเงิน
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="border-2 border-accentOrange bg-orange-50/50 p-4 rounded-xl cursor-pointer flex flex-col justify-between space-y-2">
                            <input type="radio" name="payment_method" value="PromptPay QR" checked class="text-accentOrange focus:ring-accentOrange">
                            <div>
                                <p class="font-bold text-xs text-slate-900">📱 PromptPay QR</p>
                                <p class="text-[11px] text-slate-500">สแกนจ่ายทันที ไม่มีค่าธรรมเนียม</p>
                            </div>
                        </label>
                        <label class="border border-slate-200 p-4 rounded-xl cursor-pointer flex flex-col justify-between space-y-2 hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="Credit Card" class="text-accentOrange focus:ring-accentOrange">
                            <div>
                                <p class="font-bold text-xs text-slate-900">💳 บัตรเครดิต / เดบิต</p>
                                <p class="text-[11px] text-slate-500">Visa, Mastercard, JCB</p>
                            </div>
                        </label>
                        <label class="border border-slate-200 p-4 rounded-xl cursor-pointer flex flex-col justify-between space-y-2 hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="Cash on Delivery" class="text-accentOrange focus:ring-accentOrange">
                            <div>
                                <p class="font-bold text-xs text-slate-900">💵 เก็บเงินปลายทาง (COD)</p>
                                <p class="text-[11px] text-slate-500">ชำระเมื่อรับพัสดุ</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Confirm Order Button -->
                <button type="submit" class="w-full bg-accentOrange hover:bg-orange-600 text-white font-extrabold text-base py-4 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                    <span>ยืนยันคำสั่งซื้อและชำระเงิน (฿{{ number_format($netTotal, 2) }})</span>
                    <span>→</span>
                </button>
            </form>
        </div>

        <!-- Right Column: Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <h2 class="text-lg font-extrabold text-slate-900 border-b border-slate-100 pb-3">สรุปรายการคำสั่งซื้อ</h2>

            <!-- Items mini-list -->
            <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                @foreach($cart as $item)
                    <div class="flex items-center justify-between text-xs gap-2">
                        <div class="truncate flex-1">
                            <p class="font-bold text-slate-800 truncate">{{ $item['name'] }}</p>
                            <span class="text-slate-400">฿{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</span>
                        </div>
                        <span class="font-bold text-slate-900">
                            ฿{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Price Breakdown -->
            <div class="border-t border-slate-100 pt-3 space-y-2.5 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>ราคารวมสินค้า (Subtotal)</span>
                    <span class="font-bold text-slate-900">฿{{ number_format($subtotal, 2) }}</span>
                </div>

                @if($bogoDiscount > 0)
                    <div class="flex justify-between text-accentOrange font-medium">
                        <span>ส่วนลดโปรโมชัน 2 แถม 1</span>
                        <span>-฿{{ number_format($bogoDiscount, 2) }}</span>
                    </div>
                @endif

                @if($voucherDiscount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>ส่วนลดคูปอง ({{ $appliedCode }})</span>
                        <span>-฿{{ number_format($voucherDiscount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-slate-600">
                    <span>ค่าจัดส่ง</span>
                    <span>{{ $shippingFee == 0 ? 'ฟรี' : '฿' . number_format($shippingFee, 2) }}</span>
                </div>

                <div class="border-t border-slate-200 pt-3 flex justify-between items-baseline">
                    <span class="text-sm font-extrabold text-slate-900">ยอดชำระสุทธิ</span>
                    <span class="text-xl font-black text-slate-900">฿{{ number_format($netTotal, 2) }}</span>
                </div>
            </div>

            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
                <p class="font-bold text-slate-700">🛡️ ข้อกำหนดวิศวกรรมฐานข้อมูล (ACID Safe):</p>
                <p>คำสั่งซื้อนี้จะรันผ่าน <code>DB::transaction()</code> ของ Laravel ตัดสต็อกสินค้าใน MySQL และสร้างตาราง <code>orders</code> และ <code>order_items</code> แบบอะตอมิก (Atomic) 100%</p>
            </div>
        </div>

    </div>

</div>
@endsection
