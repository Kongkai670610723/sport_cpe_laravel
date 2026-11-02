@extends('layouts.app')

@section('title', 'Admin Dashboard — SPORT.CPE')

@section('content')
<div class="space-y-8">

    <!-- Admin Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>🛡️</span> แผงควบคุมผู้ดูแลระบบ (Admin Dashboard)
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                จัดการฐานข้อมูล MySQL, ควบคุมสต็อกสินค้าคงคลัง, อัปเดตสถานะออเดอร์ และจัดการโปรโมชัน 2 แถม 1
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                MySQL Connected (Port 3306)
            </span>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">รายได้รวมทั้งหมด</span>
                <p class="text-2xl font-black text-slate-900 mt-1">฿{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <span class="text-3xl">💰</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">จำนวนคำสั่งซื้อ</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalOrders) }} บิล</p>
            </div>
            <span class="text-3xl">📦</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">รายการสินค้าในแคตตาล็อก</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalProducts) }} ชิ้น</p>
            </div>
            <span class="text-3xl">🏸</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">สมาชิกลูกค้าที่ลงทะเบียน</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCustomers) }} คน</p>
            </div>
            <span class="text-3xl">👥</span>
        </div>
    </div>

    <!-- Low Stock Alert Section -->
    <div class="bg-white rounded-2xl border border-amber-200 overflow-hidden shadow-sm">
        <div class="bg-amber-500/10 border-b border-amber-200 p-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">⚠️</span>
                <h3 class="font-extrabold text-amber-900 text-sm sm:text-base">
                    การแจ้งเตือนสต็อกสินค้าต่ำ (Low Stock Alert: ≤ 10 ชิ้น)
                </h3>
            </div>
            <span class="text-xs font-bold bg-amber-200 text-amber-900 px-2.5 py-0.5 rounded-full">
                {{ $lowStockProducts->count() }} รายการ
            </span>
        </div>

        <div class="p-4 sm:p-6 overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100">
                        <th class="pb-2 font-bold">ID</th>
                        <th class="pb-2 font-bold">ชื่อสินค้า</th>
                        <th class="pb-2 font-bold">ราคา</th>
                        <th class="pb-2 font-bold">คงเหลือ</th>
                        <th class="pb-2 font-bold text-right">เติมสต็อก</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($lowStockProducts as $low)
                        <tr>
                            <td class="py-3 font-mono font-bold text-slate-500">#{{ $low->product_id }}</td>
                            <td class="py-3 font-semibold text-slate-800">{{ $low->name }}</td>
                            <td class="py-3 font-bold text-slate-900">฿{{ number_format($low->price, 2) }}</td>
                            <td class="py-3">
                                <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded text-xs">
                                    {{ $low->stock_quantity }} ชิ้น
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <form action="{{ route('admin.products.restock', $low->product_id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="add_quantity" value="10">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1 rounded-lg text-xs shadow-sm transition">
                                        + เติม 10 ชิ้น
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Buy 2 Get 1 Free Management Section -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <span>🎁</span> จัดการสินค้าโปรโมชัน "ซื้อ 2 แถม 1" (M:N promotion_products)
                </h3>
                <p class="text-xs text-slate-500">เลือกเพิ่มหรือนำสินค้าออกจากรายการโปรโมชัน 2 แถม 1 ได้โดยตรง</p>
            </div>
        </div>

        <!-- Add Product to BOGO Form -->
        <form action="{{ route('admin.promotions.bogo') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <select name="product_id" required class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-accentOrange">
                <option value="">-- เลือกสินค้าที่ต้องการเพิ่ม/ลบ ในโปร 2 แถม 1 --</option>
                @foreach($allProducts as $p)
                    <option value="{{ $p->product_id }}">
                        [{{ $bogoProducts->contains('product_id', $p->product_id) ? 'ร่วมรายการอยู่' : 'ยังไม่ร่วม' }}] #{{ $p->product_id }} {{ $p->name }} (฿{{ number_format($p->price, 2) }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-accentOrange hover:bg-orange-600 text-white font-bold px-5 py-2 rounded-xl text-xs shadow transition">
                สลับสถานะ (Toggle)
            </button>
        </form>

        <!-- Current BOGO List -->
        <div class="pt-2">
            <span class="text-xs font-bold text-slate-500 mb-2 block">สินค้าที่ร่วมรายการอยู่ในปัจจุบัน ({{ $bogoProducts->count() }} ชิ้น):</span>
            <div class="flex flex-wrap gap-2">
                @foreach($bogoProducts as $bp)
                    <span class="inline-flex items-center gap-1.5 bg-orange-50 border border-accentOrange/30 text-accentOrange px-3 py-1 rounded-lg text-xs font-bold">
                        <span>#{{ $bp->product_id }} {{ $bp->name }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <span>📋</span> รายการคำสั่งซื้อทั้งหมด ({{ $recentOrders->count() }} ออเดอร์)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="text-slate-400 bg-slate-50/70 border-b border-slate-100">
                        <th class="py-3 px-4 font-bold">Order ID</th>
                        <th class="py-3 px-4 font-bold">ลูกค้า</th>
                        <th class="py-3 px-4 font-bold">วันที่</th>
                        <th class="py-3 px-4 font-bold">ยอดสุทธิ</th>
                        <th class="py-3 px-4 font-bold">สถานะ</th>
                        <th class="py-3 px-4 font-bold text-right">เปลี่ยนสถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">#{{ $order->order_id }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $order->user->name ?? 'ลูกค้าทั่วไป' }}</td>
                            <td class="py-3 px-4 text-slate-500 text-xs">{{ $order->order_date }}</td>
                            <td class="py-3 px-4 font-black text-slate-900">฿{{ number_format($order->net_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $stClass = [
                                        'Paid' => 'bg-emerald-100 text-emerald-800',
                                        'Shipped' => 'bg-blue-100 text-blue-800',
                                        'Delivered' => 'bg-slate-100 text-slate-800',
                                        'Pending' => 'bg-amber-100 text-amber-800',
                                        'Cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $stClass[$order->order_status] ?? 'bg-slate-100 text-slate-800' }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.orders.status', $order->order_id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="order_status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-300 rounded-lg px-2 py-1 text-xs font-bold text-slate-700 focus:outline-none focus:border-accentOrange">
                                        <option value="Pending" {{ $order->order_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Paid" {{ $order->order_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Shipped" {{ $order->order_status === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->order_status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Cancelled" {{ $order->order_status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

