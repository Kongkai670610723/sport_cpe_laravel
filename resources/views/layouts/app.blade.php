<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HomePage — SPORT.CPE')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body { font-family: 'Inter', sans-serif; background-color: #0b1329; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#f1f5f9]">

    <!-- Top Dark Navy Section Containing Navbar -->
    <header class="bg-[#0b1329] text-white sticky top-0 z-50">
        <div class="max-w-[1280px] mx-auto px-6 h-[72px] flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-black text-xl tracking-wider text-white hover:opacity-90 italic">
                <img src="/images/logo_sport_cpe.png" alt="SPORT.CPE Logo" class="w-9 h-9 object-contain shrink-0"> SPORT.CPE
            </a>

            <div class="flex-1 max-w-[500px] hidden md:block">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Search equipment, shoes, gear..." 
                        value="{{ request('search') }}"
                        class="w-full bg-[#1e293b] text-white placeholder-slate-400 text-xs rounded-lg pl-9 pr-4 py-2.5 border border-slate-700 focus:outline-none focus:border-[#ff6b00]"
                    >
                    <span class="absolute left-3 top-2.5 text-xs text-slate-400">🔍</span>
                </form>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('vouchers.index') }}" class="flex items-center gap-1.5 text-xs font-semibold text-slate-300 hover:text-white px-2 py-1.5 rounded-lg transition">
                    <span>🎟️ Vouchers</span>
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-2 bg-[#22c55e] hover:bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-xs transition shadow-sm">
                    <span>🛒 Cart ({{ count(session('cart', [])) }})</span>
                </a>
                @auth
                    <div class="flex items-center gap-2">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-1.5 border border-slate-400 hover:border-white px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition">
                            <span>👤 {{ Auth::user()->name }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-rose-400 text-xs transition">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-1.5 border border-slate-400 hover:border-white px-4 py-1.5 rounded-lg text-xs font-semibold text-white transition">
                        <span>👤 Login</span>
                    </a>
                @endauth
            </div>
        </div>

        <nav class="border-t border-slate-800 text-xs font-medium">
            <div class="max-w-[1280px] mx-auto px-6 flex items-center gap-8 h-11 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('home') }}" class="py-2.5 transition {{ request()->routeIs('home') && !request('category') ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Home</a>
                <a href="{{ route('products.index', ['category' => 'running']) }}" class="py-2.5 transition {{ request('category') === 'running' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Running</a>
                <a href="{{ route('products.index', ['category' => 'badminton']) }}" class="py-2.5 transition {{ request('category') === 'badminton' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Badminton</a>
                <a href="{{ route('products.index', ['category' => 'basketball']) }}" class="py-2.5 transition {{ request('category') === 'basketball' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Basketball</a>
                <a href="{{ route('products.index', ['category' => 'football']) }}" class="py-2.5 transition {{ request('category') === 'football' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Football</a>
                <a href="{{ route('products.index', ['category' => 'swim']) }}" class="py-2.5 transition {{ request('category') === 'swim' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Swim</a>
                <a href="{{ route('products.index', ['category' => 'fitness']) }}" class="py-2.5 transition {{ request('category') === 'fitness' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Fitness</a>
                <a href="{{ route('products.index', ['category' => 'camping']) }}" class="py-2.5 transition {{ request('category') === 'camping' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Camping</a>
                <a href="{{ route('products.index', ['category' => 'volleyball']) }}" class="py-2.5 transition {{ request('category') === 'volleyball' ? 'text-[#ff6b00] font-bold border-b-2 border-[#ff6b00]' : 'text-slate-300 hover:text-white' }}">Volleyball</a>
            </div>
        </nav>
    </header>

    @if(session('success'))
        <div class="bg-emerald-500 text-white text-xs font-bold py-2.5 px-6 text-center shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500 text-white text-xs font-bold py-2.5 px-6 text-center shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer Matching Original Figma -->
    <footer class="bg-[#0b1329] text-white border-t border-slate-800 py-8 text-xs">
        <div class="max-w-[1280px] mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <img src="/images/logo_sport_cpe.png" alt="SPORT.CPE Logo" class="w-6 h-6 object-contain">
                <span class="font-black italic text-sm">SPORT.CPE</span>
                <span class="text-slate-400">| 261342 Database Systems Term Project (Laravel Edition)</span>
            </div>
            <div class="flex items-center gap-6 text-slate-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Admin Dashboard</a>
                <a href="{{ route('vouchers.index') }}" class="hover:text-white transition">Voucher Center</a>
                <span>กลุ่มสมาชิก: 1. ณภัทร, 2. ภูรินท์, 3. วรภพ</span>
            </div>
        </div>
    </footer>

</body>
</html>
