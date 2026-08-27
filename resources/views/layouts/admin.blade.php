<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel | Tangga Mas Scaffolding')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js untuk Toggle Mobile Sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-green': '#1BBC9A',
              'brand-green-dark': '#0C5646',
              'brand-gray-bg': '#f8fafc',
              'brand-price': '#16a34a'
            },
            fontFamily: { 'sans': ['"Inter"', 'sans-serif'] }
          }
        }
      }
    </script>
    <style>
        /* Custom Scrollbar untuk Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body class="font-sans text-gray-800 bg-[#f5f5f5] antialiased min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- ========================================================================= -->
    <!-- 1. HEADER TOP BAR (FIXED Z-INDEX 50) -->
    <!-- ========================================================================= -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 z-50 flex items-center justify-between px-4 lg:px-6 shadow-sm">
        <div class="flex items-center gap-3">
            <!-- Mobile Toggle Button -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 focus:outline-none rounded-lg hover:bg-gray-100 cursor-pointer">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <!-- Brand Logo & Subtitle -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logotm.png') }}" class="h-8 w-auto object-contain" alt="Logo Tangga Mas" onerror="this.src='{{ asset('images/logotmputih.png') }}'">
                <div class="hidden sm:block border-l border-gray-300 pl-2.5">
                    <span class="text-sm font-bold text-gray-800 tracking-tight block leading-none">Seller Centre Tangga Mas</span>
                </div>
            </a>
        </div>

        <!-- User Profile & Action Right -->
        <div class="flex items-center gap-3 md:gap-5">
            <!-- Shortcut Lihat Toko Live -->
            <a href="{{ url('/') }}" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-brand-green bg-gray-50 hover:bg-emerald-50/50 border border-gray-200 hover:border-brand-green px-3 py-1.5 rounded-lg transition-all">
                <i class="fa-solid fa-store text-xs"></i> Lihat Toko
            </a>

            <!-- User Info -->
            <div class="flex items-center gap-2 border-l border-gray-200 pl-3 md:pl-5">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-[#1BBC9A] font-bold flex items-center justify-center text-xs border border-emerald-200">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="hidden md:block">
                    <span class="text-xs font-bold text-gray-800 block leading-tight">{{ Auth::user()->name ?? 'Admin Tangga Mas' }}</span>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Log Out">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- OVERLAY MOBILE -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

    <!-- ========================================================================= -->
    <!-- 2. NAVIGASI SIDEBAR KIRI (FIXED TOP-16 TEPAT DI BAWAH HEADER / Z-INDEX 40) -->
    <!-- ========================================================================= -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed top-16 bottom-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0">
        
        <div class="p-4 sidebar-scroll overflow-y-auto flex-1 space-y-6">

            <!-- KELOMPOK 1: UTAMA -->
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-3 mb-2">Utama</span>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/dashboard') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center text-sm"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <!-- KELOMPOK 2: PESANAN & LOGISTIK ARMADA -->
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-3 mb-2">Pesanan & Logistik</span>
                <nav class="space-y-1">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/orders*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-boxes-packing w-4 text-center text-sm"></i>
                        <span>Pesanan Masuk</span>
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/vehicles*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-truck-front w-4 text-center text-sm"></i>
                        <span>Master Data Armada</span>
                    </a>
                    <a href="{{ route('admin.shipping.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/shipping-rates*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-truck-ramp-box w-4 text-center text-sm"></i>
                        <span>Tarif Ongkir Armada</span>
                    </a>
                </nav>
            </div>

            <!-- KELOMPOK 3: PRODUK KATALOG -->
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-3 mb-2">Produk</span>
                <nav class="space-y-1">
                    <a href="{{ route('produk.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/produk') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-boxes-stacked w-4 text-center text-sm"></i>
                        <span>Produk Saya</span>
                    </a>
                    <a href="{{ route('produk.create') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/produk/create') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-plus-circle w-4 text-center text-sm"></i>
                        <span>Tambah Produk Baru</span>
                    </a>
                </nav>
            </div>

            <!-- KELOMPOK 4: PROMOSI & DEKORASI TOKO -->
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-3 mb-2">Konten Artikel</span>
                <nav class="space-y-1">
                    <a href="{{ route('artikel.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/artikel') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-newspaper w-4 text-center text-sm"></i>
                        <span>Edukasi K3 & Artikel</span>
                    </a>
                    <a href="{{ route('artikel.create') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/artikel/create') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-pen-nib w-4 text-center text-sm"></i>
                        <span>Tulis Artikel Baru</span>
                    </a>
                </nav>
            </div>

            <!-- KELOMPOK 5: PENGATURAN TOKO -->
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-3 mb-2">Pengaturan & Dekorasi</span>
                <nav class="space-y-1">
                    <a href="{{ route('admin.settings.transaction') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/settings*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-toggle-on w-4 text-center text-sm"></i>
                        <span>Mode Transaksi Toko</span>
                    </a>
                    <a href="{{ route('admin.dekorasi.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('admin/dekorasi*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-paintbrush w-4 text-center text-sm"></i>
                        <span>Dekorasi Beranda Toko</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ Request::is('profile*') ? 'bg-emerald-50 text-[#1BBC9A] font-bold border-r-4 border-[#1BBC9A]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-gear w-4 text-center text-sm"></i>
                        <span>Profil Akun Admin</span>
                    </a>
                </nav>
            </div>

        </div>
    </aside>

    <!-- ========================================================================= -->
    <!-- 3. AREA KONTEN UTAMA -->
    <!-- ========================================================================= -->
    <main class="pt-16 lg:pl-64 min-h-screen">
        <div class="p-4 md:p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>