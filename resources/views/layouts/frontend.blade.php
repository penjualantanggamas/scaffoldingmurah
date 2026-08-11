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

@php
    // LOGIKA HITUNG BADGE KERANJANG AMAN (Bebas Eror Null given & Sinkron DB/Session)
    $cartCount = 0;

    if (Auth::guard('customer')->check()) {
        // Jika sudah login: Ambil total kuantitas dari Database MySQL
        $cartCount = (int) \App\Models\Cart::where('customer_id', Auth::guard('customer')->id())->sum('jumlah');
    } else {
        // Jika guest/belum login: Ambil dari Session dan pastikan nilainya berupa array aman
        $sessionCart = session('cart', []);
        
        if (is_array($sessionCart) && !empty($sessionCart)) {
            foreach ($sessionCart as $item) {
                if (is_array($item)) {
                    $cartCount += (int) ($item['quantity'] ?? $item['jumlah'] ?? 1);
                } else {
                    $cartCount += 1;
                }
            }
        }
    }
@endphp

<!-- ========== NAVBAR ========== -->
<header class="sticky top-0 z-50 bg-white border-b border-gray-100">
  <div class="container mx-auto px-4">
    <!-- BARIS ATAS: Logo, Search Bar, & Ikon Aksi -->
    <div class="flex items-center justify-between h-14 md:h-20 gap-4 md:gap-6">

      <!-- Logo -->
      <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
        <img src="{{ asset('images/logotm.png') }}" class="h-6 md:h-10 w-auto object-contain" alt="Logo Tangga Mas Scaffolding">
      </a>

      <!-- SEARCH BAR DESKTOP -->
      <div class="hidden sm:flex relative items-center w-full max-w-xl md:max-w-2xl mx-auto dropdown-search-container">
        <form action="{{ url('/products') }}" method="GET" class="relative flex items-center w-full">
          <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-400 text-sm"></i>
          <input 
            type="text" 
            id="desktopSearchInput"
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Cari produk Scaffolding Tangga Mas..." 
            class="w-full bg-gray-50 border border-gray-200 rounded-full pl-10 pr-4 py-2.5 text-xs md:text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all shadow-inner"
            autocomplete="off"
          >
        </form>
        <!-- Dropdown Hasil Desktop -->
        <div id="desktopSearchResults" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden max-h-80 overflow-y-auto"></div>
      </div>

      <!-- Right icons (Search HP, Cart, & Profile) -->
      <div class="flex items-center gap-3 md:gap-4 text-gray-600 shrink-0">
        
        <!-- Tombol Search Khusus HP -->
        <button onclick="toggleMobileSearch()" aria-label="Search" class="block sm:hidden hover:text-brand-green transition-colors p-1">
          <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>

        <!-- KERANJANG DI ATAS SEBELAH KANAN SEARCH BAR (Aktif untuk Mobile & Desktop) -->
        <a href="{{ route('cart.index') }}" aria-label="Cart" class="relative hover:text-brand-green text-gray-700 transition-colors p-1">
          <i class="fa-solid fa-cart-shopping text-lg"></i>
          @if($cartCount > 0)
            <span id="cartBadgeCount" class="absolute -top-1 -right-1.5 flex items-center justify-center w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-bold shadow-sm">
              {{ $cartCount }}
            </span>
          @endif
        </a>

        <!-- HANYA TAMPIL DI DESKTOP: Fitur Autentikasi Customer -->
        <div class="hidden md:block relative dropdown-profile-container">
          @if(Auth::guard('customer')->check())
            <!-- Kondisi Sudah Login Customer -->
            <div class="relative inline-block text-left">
              <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-3 py-2 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-user-tie text-brand-green text-sm"></i>
                <span class="max-w-[120px] truncate">{{ Auth::guard('customer')->user()->nama_lengkap }}</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
              </button>
              
              <!-- Menu Dropdown Desktop -->
              <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">    
                <a href="{{ route('customer.profile') }}" class="block px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-brand-green transition-colors">
                  <i class="fa-solid fa-user-gear mr-2 text-gray-400"></i> Pengaturan Profil
                </a>
                <hr class="border-gray-50">
                <form method="POST" action="{{ route('customer.logout') }}" class="block w-full">
                  @csrf
                  <button type="submit" class="block w-full text-left px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar Akun
                  </button>
                </form>
              </div>
            </div>
          @else
            <!-- Kondisi Belum Login Customer -->
            <div class="flex items-center gap-2 text-xs font-medium">
              <a href="{{ route('customer.register') }}" class="hover:text-brand-green transition-colors">Daftar</a>
              <span class="text-gray-300">|</span>
              <a href="{{ route('customer.login') }}" class="font-bold text-brand-green hover:text-brand-green-dark transition-colors">Log In</a>
            </div>
          @endif
        </div>

      </div>
    </div>

    <!-- BARIS BAWAH (KHUSUS DESKTOP): Menu Tautan Halaman -->
    <div id="desktopBottomNav" class="hidden md:flex items-center justify-center border-t border-gray-50 transition-all duration-500 ease-in-out opacity-100 h-11 overflow-hidden shrink-0">
      <nav class="flex items-center gap-10 text-xs md:text-sm text-gray-600 font-semibold tracking-wide py-3">
        <a href="{{ url('/') }}" class="hover:text-brand-green transition-colors {{ Request::is('/') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Beranda</a>
        <a href="{{ url('/products') }}" class="hover:text-brand-green transition-colors {{ Request::is('products*') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Produk</a>
        <a href="{{ route('customer.orders.index') }}" class="hover:text-brand-green transition-colors {{ Request::is('*orders*') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Pesanan</a>
        <a href="{{ url('/about') }}" class="hover:text-brand-green transition-colors {{ Request::is('about') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Tentang Kami</a>
      </nav>
    </div>

    <!-- FITUR MOBILE SEARCH BAR DENGAN DROPDOWN -->
    <div id="mobileSearchInput" class="hidden pb-3 relative dropdown-search-container">
      <form action="{{ url('/products') }}" method="GET" class="relative flex items-center w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-gray-400 text-xs"></i>
        <input 
          type="text" 
          id="mobileSearchField"
          name="search" 
          value="{{ request('search') }}" 
          placeholder="Cari produk Tangga Mas..." 
          class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-xs focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all"
          autocomplete="off"
        >
      </form>
      <!-- Dropdown Hasil Mobile -->
      <div id="mobileSearchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-100 rounded-lg shadow-xl z-50 overflow-hidden max-h-64 overflow-y-auto"></div>
    </div>

  </div>
</header>


<!-- ========== MOBILE BOTTOM NAVIGATION BAR ========== -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.06)]">
  <div class="flex items-center justify-around h-14 px-1">

    <!-- 1. Beranda -->
    <a href="{{ url('/') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('/') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-house text-base"></i>
      <span class="text-[10px] leading-tight">Beranda</span>
    </a>

    <!-- 2. Produk -->
    <a href="{{ url('/products') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('products*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-box-open text-base"></i>
      <span class="text-[10px] leading-tight">Produk</span>
    </a>

    <!-- 3. MENU PESANAN -->
    <a href="{{ route('customer.orders.index') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('*orders*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-receipt text-base"></i>
      <span class="text-[10px] leading-tight">Pesanan</span>
    </a>

    <!-- 4. Tentang Kami -->
    <a href="{{ url('/about') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('about') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-building text-base"></i>
      <span class="text-[10px] leading-tight">Tentang</span>
    </a>

    <!-- 5. MENU PROFIL / SAYA (LANGSUNG MENGARAH KE HALAMAN PENGATURAN AKUN) -->
    @if(Auth::guard('customer')->check())
      <!-- Kondisi Sudah Login: Diarahkan langsung ke route customer.profile -->
      <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('customer/profile*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
        <i class="fa-solid fa-user-circle text-base"></i>
        <span class="text-[10px] leading-tight truncate max-w-[55px]">Saya</span>
      </a>
    @else
      <!-- Kondisi Belum Login -->
      <a href="{{ route('customer.login') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('customer/login*') || Request::is('customer/register*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
        <i class="fa-solid fa-user text-base"></i>
        <span class="text-[10px] leading-tight">Akun</span>
      </a>
    @endif

  </div>
</nav>


<!-- ========== MAIN CONTENT ========== -->
<main class="pb-20 md:pb-0">
  @yield('content')
</main>


<!-- ========== FOOTER ========== -->
<footer id="footer" class="bg-brand-green-dark text-gray-250 border-t-2 border-brand-green overflow-hidden mb-14 md:mb-0">
  <div class="container mx-auto px-4 py-6 md:py-12">
    <div class="grid grid-cols-3 gap-3 md:gap-6 items-start w-full">
      <!-- Kolom 1: Informasi Usaha -->
      <div class="text-left break-words">
        <h3 class="text-white font-bold text-xs md:text-base mb-2 tracking-wide flex items-center gap-1">Tangga Mas</h3>
        <ul class="space-y-1 text-[10px] md:text-xs text-white leading-normal">
          <li class="flex items-start gap-1">
            <i class="fa-solid fa-location-dot text-white mt-0.5 shrink-0"></i>
            <span class="break-words whitespace-normal">Jl Raya Bangkingan No 16, Gresik, Jawa Timur</span>
          </li>
          <li class="flex items-center gap-1 mt-1">
            <i class="fa-solid fa-phone text-white shrink-0"></i>
            <span>08123651818</span>
          </li>
        </ul>
      </div>

      <!-- Kolom 2: Menu Cepat -->
      <div class="text-left border-l border-white/10 pl-3 md:pl-6">
        <h3 class="text-white font-bold text-xs md:text-base mb-2 tracking-wide">Menu</h3>
        <ul class="space-y-1 text-[10px] md:text-xs text-gray-300 font-medium">
          <li><a href="{{ url('/') }}" class="hover:text-brand-green transition-colors block py-0.5">Beranda</a></li>
          <li><a href="{{ url('/products') }}" class="hover:text-brand-green transition-colors block py-0.5">Produk</a></li>
          <li><a href="{{ url('/about') }}" class="hover:text-brand-green transition-colors block py-0.5">Tentang Kami</a></li>
        </ul>
      </div>

      <!-- Kolom 3: Media Sosial -->
      <div class="text-left border-l border-white/10 pl-3 md:pl-6">
        <h3 class="text-white font-bold text-xs md:text-base mb-2 tracking-wide">Sosmed</h3>
        <div class="flex flex-wrap items-center gap-1.5 md:gap-2.5">
          <a href="https://tanggamasjayamakmur.com" aria-label="Website" class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full bg-white/10 hover:bg-brand-green text-white transition-all">
            <i class="fa-solid fa-globe text-[10px] md:text-xs"></i>
          </a>
          <a href="https://www.instagram.com/scaffoldingtanggamas" aria-label="Instagram" class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full bg-white/10 hover:bg-brand-green text-white transition-all">
            <i class="fa-brands fa-instagram text-[10px] md:text-xs"></i>
          </a>
          <a href="https://www.tiktok.com/@tanggamasjayamakmur" aria-label="Tiktok" class="flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full bg-white/10 hover:bg-brand-green text-white transition-all">
            <i class="fa-brands fa-tiktok text-[10px] md:text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom bar (Dengan Secret Link Login Admin pada kata 'reserved.') -->
  <div class="border-t border-white/5 bg-black/20">
    <p class="text-center text-[9px] md:text-xs text-gray-400 py-3 tracking-wide">
      &copy; 2026 Tangga Mas Scaffolding & Formwork. All rights 
      <a href="{{ route('login') }}" class="hover:text-gray-300 transition-colors select-none">reserved.</a>
    </p>
  </div>
</footer>


<!-- JavaScript Control -->
<script>
function toggleMobileSearch() {
  const searchInput = document.getElementById('mobileSearchInput');
  if (searchInput) searchInput.classList.toggle('hidden');
}

// Toggle Menu Profil Desktop
function toggleProfileDropdown() {
  const dropdown = document.getElementById('profileDropdown');
  if (dropdown) dropdown.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
  // DESKTOP NAV: HILANG SAAT SCROLL
  const bottomNav = document.getElementById('desktopBottomNav');

  if (bottomNav) {
    function handleScroll() {
      if (window.scrollY > 20) {
        bottomNav.classList.remove('opacity-100', 'h-11');
        bottomNav.classList.add('opacity-0', 'h-0', 'pointer-events-none');
      } else {
        bottomNav.classList.remove('opacity-0', 'h-0', 'pointer-events-none');
        bottomNav.classList.add('opacity-100', 'h-11');
      }
    }
    handleScroll();
    window.addEventListener('scroll', handleScroll);
  }
});

document.addEventListener('DOMContentLoaded', function() {
  setupLiveSearch('desktopSearchInput', 'desktopSearchResults');
  setupLiveSearch('mobileSearchField', 'mobileSearchResults');

  function setupLiveSearch(inputId, resultsId) {
    const input = document.getElementById(inputId);
    const results = document.getElementById(resultsId);

    if (!input || !results) return;

    input.addEventListener('input', function() {
      const query = this.value.trim();

      if (query.length < 2) {
        results.innerHTML = '';
        results.classList.add('hidden');
        return;
      }

      fetch(`/api/search-products?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          results.innerHTML = '';
          results.classList.remove('hidden');

          if (data.length > 0) {
            let header = document.createElement('div');
            header.className = "bg-gray-50 text-[10px] uppercase font-bold text-gray-400 px-4 py-2 border-b border-gray-100 tracking-wider";
            header.innerText = "Produk";
            results.appendChild(header);

            data.forEach(produk => {
              let item = document.createElement('a');
              item.href = `/products/detail/${produk.slug}`;
              item.className = "flex items-center gap-4 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group text-left block w-full";
              let imgSrc = '/images/logotm.png';
              if (produk.gambar) {
                if (produk.gambar.startsWith('http') || produk.gambar.startsWith('images/')) {
                  imgSrc = `/${produk.gambar}`;
                } else {
                  imgSrc = `/images/products/${produk.gambar}`;
                }
              }
              item.innerHTML = `
                <img src="${imgSrc}" class="w-10 h-10 object-contain rounded border border-gray-100 shrink-0" alt="${produk.nama}" onerror="this.src='/images/logotm.png'">
                <span class="text-xs font-semibold text-gray-700 group-hover:text-[#1BBC9A] transition-colors line-clamp-2">${produk.nama}</span>
              `;
              results.appendChild(item);
            });

            let footer = document.createElement('a');
            footer.href = `/products?search=${encodeURIComponent(query)}`;
            footer.className = "block text-center text-[11px] font-bold text-[#1BBC9A] bg-gray-50/50 hover:bg-gray-50 py-2.5 transition-colors border-t border-gray-100";
            footer.innerText = `Lihat seluruh hasil pencarian`;
            results.appendChild(footer);

          } else {
            results.innerHTML = `
              <div class="text-center py-6 text-xs text-gray-400 font-medium">
                <i class="fa-solid fa-box-open text-base mb-1 block"></i>
                Produk tidak ditemukan
              </div>
            `;
          }
        })
        .catch(error => console.error('Error fetching search data:', error));
    });
  }

  // Klik di luar untuk menutup Dropdown Search & Profile
  document.addEventListener('click', function(e) {
    const searchContainers = document.querySelectorAll('.dropdown-search-container');
    searchContainers.forEach(container => {
      if (!container.contains(e.target)) {
        const dropdown = container.querySelector('[id$="SearchResults"]');
        if (dropdown) dropdown.classList.add('hidden');
      }
    });

    const profileContainer = document.querySelector('.dropdown-profile-container');
    if (profileContainer && !profileContainer.contains(e.target)) {
      const profileDropdown = document.getElementById('profileDropdown');
      if (profileDropdown) profileDropdown.classList.add('hidden');
    }
  });
});
</script>
<script src="{{ asset('script.js') }}"></script>
</body>
</html>