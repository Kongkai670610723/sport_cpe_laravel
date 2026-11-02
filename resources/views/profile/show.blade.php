@extends('layouts.app')

@section('title', 'ข้อมูลสมาชิกและประวัติการสั่งซื้อ — SPORT.CPE')

@section('content')
<div class="space-y-8">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
            <span>👤 บัญชีสมาชิก</span>
            <span class="text-xs bg-slate-200 text-slate-700 font-bold px-2.5 py-1 rounded-full uppercase">{{ $user->role }}</span>
        </h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-lg transition">
                🚪 ออกจากระบบ
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- User Information Card & Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span>📝</span> ข้อมูลส่วนตัว
            </h2>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อ - นามสกุล:</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-accentOrange">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">อีเมล (ใช้เข้าสู่ระบบ):</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">เบอร์โทรศัพท์:</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-accentOrange">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ที่อยู่จัดส่งเริ่มต้น:</label>
                    <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:border-accentOrange">{{ old('address', $user->address) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl text-xs transition">
                    บันทึกการเปลี่ยนแปลง
                </button>
            </form>
        </div>

        <!-- Past Orders History -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <span>📜</span> ประวัติคำสั่งซื้อของฉัน ({{ $orders->count() }} ออเดอร์)
            </h2>

            @forelse($orders as $ord)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-slate-900 text-sm">#{{ $ord->order_id }}</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-slate-500">{{ $ord->order_date }}</span>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'Paid' => 'bg-emerald-100 text-emerald-800',
                                    'Shipped' => 'bg-blue-100 text-blue-800',
                                    'Delivered' => 'bg-slate-100 text-slate-800',
                                    'Pending' => 'bg-amber-100 text-amber-800',
                                    'Cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusColors[$ord->order_status] ?? 'bg-slate-100 text-slate-800' }}">
                                {{ $ord->order_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Line Items Summary -->
                    <div class="space-y-1.5 text-xs">
                        @foreach($ord->orderItems as $item)
                            <div class="flex justify-between text-slate-600">
                                <span>{{ $item->product->name ?? 'อุปกรณ์กีฬา' }} <strong class="text-slate-800">× {{ $item->quantity }}</strong></span>
                                <span class="font-bold text-slate-900">฿{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="flex justify-between items-baseline pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-500">ยอดชำระสุทธิ:</span>
                        <span class="text-base font-black text-slate-900">฿{{ number_format($ord->net_amount, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 text-sm">
                    ยังไม่มีประวัติการสั่งซื้อ
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection

