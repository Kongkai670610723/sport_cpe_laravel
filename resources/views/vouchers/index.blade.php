@extends('layouts.app')

@section('title', 'ศูนย์รวมคูปองและโปรโมชัน — SPORT.CPE')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 text-white p-8 sm:p-10 rounded-3xl shadow-lg relative overflow-hidden">
        <div class="max-w-xl space-y-2 relative z-10">
            <span class="inline-block bg-black/30 text-white font-bold text-xs uppercase px-3 py-1 rounded-full">
                🎟️ Voucher Center
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                ศูนย์รวมโค้ดส่วนลด และโปรโมชันสุดพิเศษ
            </h1>
            <p class="text-orange-100 text-sm">
                เก็บโค้ดส่วนลดและเลือกใช้โปรโมชันที่ดีที่สุดสำหรับคุณ ช้อปคุ้มค่ากับสินค้าคุณภาพแท้ 100%
            </p>
        </div>
    </div>

    <!-- Voucher Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($promotions as $promo)
            <div class="bg-white rounded-2xl border-2 {{ $promo->promo_code === 'BUY2GET1' ? 'border-accentOrange' : 'border-slate-200' }} p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4 relative overflow-hidden">
                <!-- Ribbon if BOGO -->
                @if($promo->promo_code === 'BUY2GET1')
                    <div class="absolute -right-12 top-6 bg-accentOrange text-white text-[10px] font-black uppercase tracking-wider py-1 px-12 rotate-45 shadow">
                        HOT DEAL
                    </div>
                @endif

                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">
                            {{ $promo->promo_code === 'BUY2GET1' ? '🎁' : '🏷️' }}
                        </span>
                        <div>
                            <span class="text-xs font-bold text-slate-400">
                                รหัสคูปอง: <strong class="text-slate-800 font-mono text-sm">{{ $promo->promo_code }}</strong>
                            </span>
                            <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">
                                {{ $promo->promo_name }}
                            </h3>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        @if($promo->discount_type === 'PERCENT')
                            รับส่วนลดทันที {{ (int)$promo->discount_percent }}% เมื่อมียอดสั่งซื้อขั้นต่ำ ฿{{ number_format($promo->min_order_amount, 2) }}
                        @elseif($promo->discount_type === 'BUY_X_GET_Y')
                            ซื้อสินค้าที่ร่วมรายการ 2 ชิ้น รับฟรีทันที 1 ชิ้นอัตโนมัติในตะกร้า (ไม่ต้องกรอกโค้ด)
                        @endif
                    </p>

                    <div class="flex items-center gap-4 text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                        <span>⏳ ใช้ได้ถึง: {{ $promo->end_date }}</span>
                        <span>•</span>
                        <span>สิทธิ์คงเหลือ: {{ $promo->usage_limit }} ครั้ง</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2">
                    @if($promo->promo_code === 'BUY2GET1')
                        <a href="{{ route('home', ['promo' => 'bogo']) }}" class="w-full bg-accentOrange hover:bg-orange-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2 shadow">
                            <span>ไปช้อปสินค้าที่ร่วมรายการ 2 แถม 1 →</span>
                        </a>
                    @else
                        <form action="{{ route('vouchers.apply') }}" method="POST">
                            @csrf
                            <input type="hidden" name="promo_code" value="{{ $promo->promo_code }}">
                            <button type="submit" class="w-full bg-primary hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2">
                                <span>คัดลอกและใช้โค้ด '{{ $promo->promo_code }}' ในการสั่งซื้อ</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
