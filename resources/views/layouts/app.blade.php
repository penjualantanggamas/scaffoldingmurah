<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tangga Mas | Scaffolding & Formwork')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-green': '#1BBC9A',
              'brand-green-dark': '#0C5646',
              'brand-gray-bg': '#f9fafb',
              'brand-price': '#16a34a'
            },
            fontFamily: {
              'sans': ['"Inter"', 'sans-serif'],
            }
          }
        }
      }
    </script>

    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="font-sans text-gray-800 bg-white antialiased">

    <!-- ========== NAVBAR ========== -->
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 md:h-20 gap-4">

          <!-- Logo -->
          <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('images/logotm.png') }}" class="h-8 md:h-10 w-auto object-contain" alt="Logo Tangga Mas Scaffolding">
          </a>

          <!-- Desktop Nav -->
          <nav class="hidden md:flex items-center gap-8 text-sm text-gray-600 font-medium shrink-0">
            <a href="{{ url('/') }}" class="hover:text-brand-green-dark transition-colors {{ Request::is('/') ? 'text-brand-green-dark font-semibold' : '' }}">Home</a>
            <a href="{{ url('/products') }}" class="hover:text-brand-green-dark transition-colors {{ Request::is('products') ? 'text-brand-green-dark font-semibold' : '' }}">Products</a>
            <a href="{{ url('/about') }}" class="hover:text-brand-green-dark transition-colors {{ Request::is('about') ? 'text-brand-green-dark font-semibold' : '' }}">About Us</a>
          </nav>

          <!-- FITUR SEARCH BAR BARU (Tampil di Desktop & Tablet) -->
          <form action="{{ url('/products') }}" method="GET" class="hidden sm:flex relative items-center max-w-xs w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-gray-400 text-xs"></i>
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              placeholder="Cari produk Scaffolding..." 
              class="w-full bg-gray-50 border border-gray-200 rounded-full pl-9 pr-4 py-2 text-xs focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all"
            >
          </form>

          <!-- Right icons -->
          <div class="flex items-center gap-5 text-gray-600 shrink-0">
            
            <!-- Tombol Search Khusus HP (Hanya muncul jika layar di bawah ukuran 'sm') -->
            <button onclick="toggleMobileSearch()" aria-label="Search" class="block sm:hidden hover:text-brand-green-dark transition-colors">
              <i class="fa-solid fa-magnifying-glass text-lg"></i>
            </button>

            <button aria-label="Wishlist" class="relative hover:text-brand-green-dark transition-colors">
              <i class="fa-regular fa-heart text-lg"></i>
              <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-brand-green"></span>
            </button>
            <button aria-label="Cart" class="relative hover:text-brand-green-dark transition-colors">
              <i class="fa-solid fa-cart-shopping text-lg"></i>
              <span class="absolute -top-2 -right-2 flex items-center justify-center w-4 h-4 rounded-full bg-brand-green text-white text-[10px] font-semibold">0</span>
            </button>

            <!-- Mobile hamburger -->
            <button id="menuBtn" aria-label="Menu" class="md:hidden text-xl">
              <i class="fa-solid fa-bars"></i>
            </button>
          </div>
        </div>

        <!-- FITUR MOBILE SEARCH BAR (Tersembunyi, muncul saat tombol search HP diklik) -->
        <div id="mobileSearchInput" class="hidden pb-3">
          <form action="{{ url('/products') }}" method="GET" class="relative flex items-center w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-gray-400 text-xs"></i>
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              placeholder="Cari produk Tangga Mas..." 
              class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-xs focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all"
            >
          </form>
        </div>

        <!-- Mobile Nav Panel -->
        <nav id="mobileNav" class="hidden md:hidden pb-4 flex flex-col gap-3 text-sm text-gray-600 font-medium">
          <a href="{{ url('/') }}" class="py-1">Home</a>
          <a href="{{ url('/products') }}" class="py-1">Products</a>
          <a href="{{ url('/about') }}" class="py-1">About Us</a>
        </nav>
      </div>
    </header>

    <!-- ========== MAIN CONTENT ========== -->
    <main>
        @yield('content')
    </main>

    <!-- ========== FOOTER ========== -->
    <footer id="footer" class="bg-brand-green-dark text-gray-200">
      <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <!-- Kiri: Company info -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Tangga Mas Jaya Makmur</h3>
            <ul class="space-y-3 text-sm text-gray-300">
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot mt-1 text-brand-green"></i>
                <span>Jl Raya Bangkingan No 16, Driyorejo, Kabupaten Gresik, Jawa Timur</span>
              </li>
              <li class="flex items-center gap-3">
                <i class="fa-solid fa-phone text-brand-green"></i>
                <span>08123851717</span>
              </li>
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-envelope text-brand-green mt-0.5"></i>
                <span>kontak@scaffoldingtm.id</span>
              </li>
            </ul>
          </div>
          <!-- Tengah: Menu Cepat -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Menu Cepat</h3>
            <ul class="space-y-2 text-sm text-gray-300">
              <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a></li>
              <li><a href="{{ url('/about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
              <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors">Produk Scaffolding</a></li>
            </ul>
          </div>
          <!-- Kanan: Social -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Social</h3>
            <div class="flex items-center gap-3">
              <a href="#" aria-label="Website" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/10 hover:bg-brand-green transition-colors">
                <i class="fa-solid fa-globe text-sm"></i>
              </a>
              <a href="#" aria-label="Share" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/10 hover:bg-brand-green transition-colors">
                <i class="fa-solid fa-share-nodes text-sm"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Bottom bar -->
      <div class="border-t border-white/10">
        <p class="text-center text-xs text-gray-400 py-4">© 2026 Tangga Mas Scaffolding & Formwork. All rights reserved.</p>
      </div>
    </footer>

    <!-- JavaScript tambahan untuk interaksi pencarian di Mobile -->
    <script>
      function toggleMobileSearch() {
        const searchInput = document.getElementById('mobileSearchInput');
        searchInput.classList.toggle('hidden');
      }
    </script>
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>