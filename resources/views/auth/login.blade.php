@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ — SPORT.CPE')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900">ยินดีต้อนรับกลับสู่ SPORT.CPE</h1>
            <p class="text-slate-500 text-xs">เข้าสู่ระบบเพื่อจัดการคำสั่งซื้อและรับสิทธิพิเศษสำหรับสมาชิก</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">อีเมล:</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', 'kongkai@cpe.eng.cmu.ac.th') }}" 
                    required 
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">รหัสผ่าน:</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    value="password123" 
                    required 
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange"
                >
            </div>

            <button type="submit" class="w-full bg-accentOrange hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition text-sm shadow">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-xs space-y-1">
            <span class="font-bold text-slate-700 block">🔑 บัญชีสำหรับทดสอบระบบ (Demo Accounts):</span>
            <p class="text-slate-600">👤 <strong>ลูกค้า</strong>: <code>kongkai@cpe.eng.cmu.ac.th</code> / <code>password123</code></p>
            <p class="text-slate-600">🛡️ <strong>แอดมิน</strong>: <code>admin@sportcpe.com</code> / <code>admin123</code></p>
        </div>

        <div class="text-center text-xs text-slate-500">
            ยังไม่มีบัญชีสมาชิก? <a href="{{ route('register') }}" class="font-bold text-accentOrange hover:underline">สมัครสมาชิกที่นี่</a>
        </div>
    </div>
</div>
@endsection

