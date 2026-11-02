@extends('layouts.app')

@section('title', 'สมัครสมาชิก — SPORT.CPE')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900">สมัครสมาชิกใหม่</h1>
            <p class="text-slate-500 text-xs">ร่วมเป็นสมาชิก SPORT.CPE รับสิทธิประโยชน์และโค้ดส่วนลด 10% ทันที</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">ชื่อ - นามสกุล:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">อีเมล:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange">
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">เบอร์โทรศัพท์:</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1">ที่อยู่:</label>
                <textarea name="address" id="address" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:border-accentOrange">{{ old('address') }}</textarea>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">รหัสผ่าน (ขั้นต่ำ 6 ตัวอักษร):</label>
                <input type="password" name="password" id="password" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">ยืนยันรหัสผ่าน:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accentOrange">
            </div>

            <button type="submit" class="w-full bg-accentOrange hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition text-sm shadow">
                สร้างบัญชีสมาชิก
            </button>
        </form>

        <div class="text-center text-xs text-slate-500">
            มีบัญชีสมาชิกอยู่แล้ว? <a href="{{ route('login') }}" class="font-bold text-accentOrange hover:underline">เข้าสู่ระบบที่นี่</a>
        </div>
    </div>
</div>
@endsection

