@extends('layouts.app')

@section('title', 'Vouchers & Promotions — SPORT.CPE')

@section('content')
<main class="max-w-[1040px] w-full mx-auto px-6 py-8 flex-1">
    <!-- Title & Collect All Button (Matching Figma Frame 11) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">VOUCHER / คูปองส่วนลด</h1>
            <p class="text-xs text-slate-600 mt-1 font-medium">กดเก็บคูปองส่วนลดไปใช้ในหน้า Checkout ลดสูงสุด 20% (สามารถเลือกใช้ 1 คูปอง ต่อ 1 คำสั่งซื้อ)</p>
        </div>
        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="coupon_code" value="PERKS20">
            <button type="submit" class="bg-[#ff6b00] hover:bg-orange-600 text-white font-bold px-5 py-2.5 rounded-xl text-xs flex items-center gap-1.5 shadow-md transition">
                <span>[ เก็บโค้ดลดสูงสุด 20% (PERKS20) 🎟️ ]</span>
            </button>
        </form>
    </div>

    <!-- Section 1: Discount Vouchers (3 Perforated Scalloped Tickets) -->
    <div class="mb-10">
        <h2 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2">
            <span>🎟️</span> คูปองส่วนลดพิเศษ (Discount Vouchers)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Ticket 1: SAVE15 (Orange) -->
            <div class="relative bg-gradient-to-br from-[#ff6b00] to-[#ea580c] text-white rounded-xl py-6 px-7 flex flex-col justify-between shadow-md">
                <div class="absolute -left-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>

                <div class="text-center space-y-1.5">
                    <div class="text-3xl font-black tracking-tight">ลด 15%</div>
                    <div>
                        <span class="inline-block bg-black/20 text-white font-bold text-xs px-3 py-1 rounded">
                            โค้ด: SAVE15
                        </span>
                    </div>
                    <div class="text-xs font-medium opacity-95">ขั้นต่ำ ฿1,000</div>
                </div>
                <div class="pt-5 text-center">
                    <form action="{{ route('cart.coupon.apply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="coupon_code" value="SAVE15">
                        <button type="submit" class="px-8 bg-white text-[#ea580c] hover:bg-orange-50 font-extrabold py-2 rounded-lg text-xs shadow-sm transition">
                            [ เก็บและใช้โค้ด ]
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ticket 2: PERKS20 (Royal Blue) -->
            <div class="relative bg-gradient-to-br from-[#1e40af] to-[#1e3a8a] text-white rounded-xl py-6 px-7 flex flex-col justify-between shadow-md">
                <div class="absolute -left-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>

                <div class="text-center space-y-1.5">
                    <div class="text-3xl font-black tracking-tight">ลด 20%</div>
                    <div>
                        <span class="inline-block bg-black/25 text-white font-bold text-xs px-3 py-1 rounded">
                            โค้ด: PERKS20
                        </span>
                    </div>
                    <div class="text-xs font-medium opacity-95">ขั้นต่ำ ฿2,000</div>
                </div>
                <div class="pt-5 text-center">
                    <form action="{{ route('cart.coupon.apply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="coupon_code" value="PERKS20">
                        <button type="submit" class="px-8 bg-white text-[#1e40af] hover:bg-blue-50 font-extrabold py-2 rounded-lg text-xs shadow-sm transition">
                            [ เก็บและใช้โค้ด ]
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ticket 3: SPORT10 (Teal) -->
            <div class="relative bg-gradient-to-br from-[#0d9488] to-[#0f766e] text-white rounded-xl py-6 px-7 flex flex-col justify-between shadow-md">
                <div class="absolute -left-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -left-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 top-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-8 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>
                <div class="absolute -right-1.5 bottom-3 w-3 h-3 bg-[#f1f5f9] rounded-full"></div>

                <div class="text-center space-y-1.5">
                    <div class="text-3xl font-black tracking-tight">ลด 10%</div>
                    <div>
                        <span class="inline-block bg-black/25 text-white font-bold text-xs px-3 py-1 rounded">
                            โค้ด: SPORT10
                        </span>
                    </div>
                    <div class="text-xs font-medium opacity-95">ขั้นต่ำ ฿500</div>
                </div>
                <div class="pt-5 text-center">
                    <form action="{{ route('cart.coupon.apply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="coupon_code" value="SPORT10">
                        <button type="submit" class="px-8 bg-white text-[#0f766e] hover:bg-teal-50 font-extrabold py-2 rounded-lg text-xs shadow-sm transition">
                            [ เก็บและใช้โค้ด ]
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Store Promotions - Buy X Get Y with Decorative Crossed Badminton Rackets Graphic -->
    <div>
        <h2 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2">
            <span>🏸</span> โปรโมชันร้านค้า (Store Promotions — Buy X Get Y)
        </h2>

        <div class="bg-[#e2e8f0] rounded-2xl overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-300/80 shadow-sm pr-6">
            <div class="flex items-center gap-6">
                <div class="w-48 h-28 shrink-0 overflow-hidden flex items-center justify-center bg-[#e2e8f0]">
                    <img src="/images/figma/voucher_rackets_banner.png" alt="Badminton Rackets Promotion" class="w-full h-full object-cover">
                </div>
                <div class="py-4 px-2">
                    <h3 class="text-xl font-black text-slate-900">โปรโมชันซื้อ 2 แถม 1 ฟรี (BUY 2 GET 1 FREE)</h3>
                    <p class="text-xs text-slate-600 font-semibold mt-1">เฉพาะสินค้าที่ร่วมรายการ (มีป้ายกำกับ <strong>Buy 2 Get 1 Free</strong>) เมื่อซื้อสินค้ารายการเดียวกันครบทุกๆ 2 ชิ้น รับชิ้นแถมเพิ่มอีก 1 ชิ้นฟรีทันที (ลดมูลค่าเต็มจำนวน)</p>
                    <div class="mt-2">
                        <span class="bg-[#22c55e] text-white text-xs font-bold px-3.5 py-1 rounded-full inline-block">
                            เฉพาะสินค้าที่ร่วมรายการ (promotion_products) • คำนวณอัตโนมัติใน Cart &amp; Checkout
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('home', ['promo' => 'bogo']) }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 font-bold px-5 py-2.5 rounded-xl text-xs whitespace-nowrap shadow-sm transition mb-4 md:mb-0">
                [ ดูรายการสินค้าที่ร่วมรายการ → ]
            </a>
        </div>
    </div>
</main>
@endsection
