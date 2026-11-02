@extends('layouts.app')

@section('title', 'สั่งซื้อสำเร็จ — SPORT.CPE')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 py-6">

    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm text-center space-y-4">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto">
            ✓
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            สั่งซื้อสินค้าสำเร็จเรียบร้อย!
        </h1>
        <p class="text-slate-500 text-sm">
            ขอบคุณสำหรับการสั่งซื้อกับ SPORT.CPE ระบบได้บันทึกคำสั่งซื้อลงฐานข้อมูล MySQL เรียบร้อยแล้ว
        </p>

        <!-- Order Meta Card -->
        <div class="bg-slate-50 rounded-2xl p-4 sm:p-6 text-left border border-slate-100 space-y-3 text-xs sm:text-sm">
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">หมายเลขคำสั่งซื้อ (Order ID)</span>
                <span class="font-mono font-bold text-slate-900">#{{ $order->order_id }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">วันที่สั่งซื้อ</span>
                <span class="font-medium text-slate-800">{{ $order->order_date }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">สถานะคำสั่งซื้อ</span>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    {{ $order->order_status }}
                </span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">วิธีการชำระเงิน</span>
                <span class="font-medium text-slate-800">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between items-baseline pt-1">
                <span class="font-bold text-slate-900">ยอดชำระสุทธิ (Net Amount)</span>
                <span class="text-xl font-black text-slate-900">฿{{ number_format($order->net_amount, 2) }}</span>
            </div>
        </div>

        <!-- Line Items -->
        <div class="text-left space-y-3 pt-2">
            <h3 class="font-bold text-sm text-slate-900">รายการสินค้าในคำสั่งซื้อ:</h3>
            <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                @foreach($order->orderItems as $item)
                    <div class="p-3 bg-white flex justify-between items-center text-xs">
                        <div>
                            <p class="font-bold text-slate-800">{{ $item->product->name ?? 'สินค้าอุปกรณ์กีฬา' }}</p>
                            <span class="text-slate-400">จำนวน: {{ $item->quantity }} ชิ้น @ ฿{{ number_format($item->unit_price, 2) }}</span>
                        </div>
                        <span class="font-black text-slate-900">฿{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="bg-primary hover:bg-slate-800 text-white font-bold py-3 px-6 rounded-xl transition text-sm">
                กลับสู่หน้าหลัก
            </a>
            <a href="{{ route('profile.show') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-3 px-6 rounded-xl transition text-sm">
                ดูประวัติการสั่งซื้อ
            </a>
        </div>
    </div>

</div>
@endsection
