@extends('layouts.app')

@section('title', 'ประวัติการสั่งซื้อ — SPORT.CPE')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">📦 ประวัติการสั่งซื้อของฉัน</h1>
            <p class="text-xs text-slate-500">ตรวจสอบสถานะและรายละเอียดคำสั่งซื้ออุปกรณ์กีฬาของคุณ</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-accentOrange hover:underline">
            ← เลือกซื้อสินค้าต่อ
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-4">
            <span class="text-5xl block">🛍️</span>
            <h2 class="text-lg font-bold text-slate-800">ยังไม่มีประวัติการสั่งซื้อ</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">คุณยังไม่ได้ทำรายการสั่งซื้อสินค้าอุปกรณ์กีฬา เริ่มช้อปสินค้าพร้อมโปรโมชัน 2 แถม 1 ได้เลย</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-accentOrange hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md transition">
                ช้อปปิ้งเลย
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 font-semibold">เลขออเดอร์</span>
                            <h3 class="font-extrabold text-slate-900 text-sm">#ORD-{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</h3>
                            <span class="text-[11px] text-slate-400">{{ $order->order_date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $statusColors = [
                                    'Pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'Paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Shipped' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'Delivered' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'Cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColors[$order->order_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $order->order_status }}
                            </span>
                            <a href="{{ route('orders.show', $order->order_id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-3 py-1 rounded-lg text-xs font-semibold transition">
                                ดูใบเสร็จ
                            </a>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="divide-y divide-slate-50">
                        @foreach($order->orderItems as $item)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                        <img src="{{ $item->product->image_url ?? '/images/figma/prod_nanoflare_1000z.png' }}" alt="{{ $item->product->name ?? 'สินค้า' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $item->product->name ?? 'สินค้าอุปกรณ์กีฬา' }}</p>
                                        <p class="text-slate-400">จำนวน: {{ $item->quantity }} ชิ้น × ฿{{ number_format($item->unit_price, 2) }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-900">฿{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">ยอดชำระสุทธิ ({{ $order->payment_method }})</span>
                        <span class="text-base font-black text-accentOrange">฿{{ number_format($order->net_amount, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
