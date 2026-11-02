<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPORT.CPE — ร้านอุปกรณ์กีฬาครบวงจร')</title>

    <!-- Google Fonts: Inter & Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        accentGreen: '#22C55E',
                        accentOrange: '#FF6B00',
                        dangerRed: '#EF4444',
                        bgSlate: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Prompt', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', 'Prompt', sans-serif; }
    </style>
</head>
<body class="bg-bgSlate font-sans text-slate-800 min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-90 transition">
                <img src="/images/logo_sport_cpe.png" alt="SPORT.CPE Logo" class="h-9 w-auto object-contain">
                <span class="font-black text-xl tracking-wider text-white hidden sm:inline">SPORT<span class="text-accentOrange">.CPE</span></span>
            </a>

            <!-- Search Bar -->
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 max-w-md hidden md:block">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="ค้นหาอุปกรณ์กีฬา, แบรนด์, รองเท้า..." 
                        value="{{ request('search') }}"
                        class="w-full bg-slate-800/90 text-white placeholder-slate-400 text-sm rounded-lg pl-10 pr-4 py-2 border border-slate-700 focus:outline-none focus:border-accentOrange transition"
                    >
                    <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>
                </div>
            </form>

            <!-- Navigation Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('vouchers.index') }}" class="text-sm font-semibold text-slate-200 hover:text-accentOrange transition hidden sm:inline-block">
                    🎟️ คูปองส่วนลด
                </a>

                <a href="{{ route('cart.index') }}" class="flex items-center gap-2 bg-accentGreen hover:bg-emerald-600 text-white px-3.5 py-1.5 rounded-lg font-bold text-sm shadow-sm transition">
                    <span>🛒 ตะกร้า</span>
                    <span class="bg-black/20 px-2 py-0.5 rounded-full text-xs font-black">{{ count(session('cart', [])) }}</span>
                </a>

                @if(Auth::check())
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-2 text-sm font-medium hover:text-accentOrange transition border border-slate-700 px-3 py-1.5 rounded-lg">
                        <span class="w-6 h-6 rounded-full bg-accentOrange flex items-center justify-center font-bold text-xs text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                        <span class="hidden lg:inline">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-1.5 border border-slate-600 hover:border-slate-400 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                        <span>👤 เข้าสู่ระบบ</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 8 Sports Categories Bar -->
        <nav class="bg-slate-900 border-t border-slate-800 text-xs md:text-sm font-medium overflow-x-auto">
            <div class="max-w-7xl mx-auto px-4 flex items-center gap-6 h-11 whitespace-nowrap">
                <a href="{{ route('home') }}" class="py-2.5 border-b-2 {{ request()->routeIs('home') ? 'border-accentOrange text-white font-bold' : 'border-transparent text-slate-400 hover:text-white' }}">
                    🏠 หน้าแรก
                </a>
                @php
                    $categoriesList = ['running' => 'Running', 'badminton' => 'Badminton', 'basketball' => 'Basketball', 'football' => 'Football', 'swim' => 'Swim', 'fitness' => 'Fitness', 'camping' => 'Camping', 'volleyball' => 'Volleyball'];
                @endphp
                @foreach($categoriesList as $slug => $cName)
                    <a href="{{ route('products.index', ['category' => $slug]) }}" class="py-2.5 border-b-2 {{ request('category') === $slug ? 'border-accentOrange text-white font-bold' : 'border-transparent text-slate-400 hover:text-white' }}">
                        {{ $cName }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <!-- Notification Alerts (Flash Messages) -->
    <div class="max-w-7xl mx-auto px-4 w-full mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r shadow-sm flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span>✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r shadow-sm flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span>❌</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-slate-400 text-sm py-8 border-t border-slate-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-white font-bold">
                <img src="/images/logo_sport_cpe.png" alt="Logo" class="h-6 w-auto object-contain">
                <span>SPORT.CPE</span>
                <span class="text-xs text-slate-400 font-normal">| 261342 Database Systems Term Project (Laravel Edition)</span>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-400 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-accentOrange font-medium">🛡️ Admin Dashboard</a>
                <span>•</span>
                <a href="{{ route('vouchers.index') }}" class="hover:text-accentOrange">Voucher Center</a>
                <span>•</span>
                <span class="text-slate-300">กลุ่มสมาชิก: 1. ณภัทร จิตเจริญ (670610686), 2. ภูรินท์ อินทจักร์ (670610723), 3. วรภพ เกิดทองสุก (670610729)</span>
            </div>
        </div>
    </footer>

</body>
</html>
