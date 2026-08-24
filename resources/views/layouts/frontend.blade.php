<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Tangga Mas | Scaffolding & Formwork')</title>

<!-- INJEKSI META TAGS SEO DINAMIS -->
@hasSection('meta')
    @yield('meta')
@else
    <!-- Default Meta Tags Fallback (Untuk Beranda, Tentang Kami, dll) -->
    <meta name="title" content="Tangga Mas | Scaffolding & Formwork Terpercaya di Indonesia">
    <meta name="description" content="PT. Tangga Mas Jaya Makmur - Produsen dan supplier utama scaffolding, perancah besi tubular, ringlock, dan bekisting berkualitas standar K3 &amp; SNI.">
    <meta name="keywords" content="Jual Scaffolding, Produsen Scaffolding, Supplier Scaffolding, Sewa Scaffolding, Tangga Mas, Bekisting, K3 Scaffolding">
    <meta name="author" content="PT. Tangga Mas Jaya Makmur">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Open Graph Default (WhatsApp / Facebook / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Tangga Mas | Scaffolding & Formwork Terpercaya di Indonesia">
    <meta property="og:description" content="PT. Tangga Mas Jaya Makmur - Produsen dan supplier utama scaffolding, perancah besi tubular, ringlock, dan bekisting berkualitas standar K3 &amp; SNI.">
    <meta property="og:image" content="{{ asset('images/logotm.png') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Tangga Mas Scaffolding">
@endif

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

<!-- STYLING PENETRAL & DISENBUNYIKANNYA BANNER GOOGLE TRANSLATE -->
<style>
  .goog-te-banner-frame, 
  .skiptranslate, 
  #goog-gt-tt,
  .goog-te-balloon-frame {
    display: none !important;
  }
  
  body {
    top: 0px !important;
  }
</style>

<!-- SCRIPT GOOGLE ANALYTICS & EVENT HELPER TANGGA MAS -->
<script>
  window.trackGAEvent = function(eventName, eventParams = {}) {
    if (typeof gtag === 'function') {
      gtag('event', eventName, eventParams);
    } else {
      console.log('[GA4 MOCK] Event Dicuplik:', eventName, eventParams);
    }
  };

  function trackGAEvent(eventName, eventParams = {}) {
    window.trackGAEvent(eventName, eventParams);
  }
</script>
</head>
<body class="font-sans text-gray-800 bg-white antialiased">

@php
    $cartCount = 0;

    if (Auth::guard('customer')->check()) {
        $cartCount = (int) \App\Models\Cart::where('customer_id', Auth::guard('customer')->id())->sum('jumlah');
    } else {
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
        <div id="desktopSearchResults" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden max-h-80 overflow-y-auto"></div>
      </div>

      <!-- Right icons (Language Switcher, Search HP, Cart, & Profile) -->
      <div class="flex items-center gap-2 md:gap-4 text-gray-600 shrink-0">
        
        <!-- CUSTOM LANGUAGE SWITCHER (IDN | ENG | CHN) -->
        <div class="relative group shrink-0">
          <button type="button" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 text-xs font-bold text-gray-700 transition-all cursor-pointer">
            <span id="activeLangText">IDN</span>
            <span class="text-gray-300">|</span>
            <span id="activeFlag" class="text-sm">🇮🇩</span>
            <i class="fa-solid fa-chevron-down text-[9px] text-gray-400 ml-0.5"></i>
          </button>

          <!-- Dropdown Pilihan Bahasa -->
          <div class="hidden group-hover:block absolute right-0 mt-1 w-36 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden py-1">
            <button type="button" onclick="setLanguage('id')" class="flex items-center justify-between w-full px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1BBC9A] transition-colors cursor-pointer text-left">
              <span>IDN (Indonesia)</span>
              <span>🇮🇩</span>
            </button>
            <button type="button" onclick="setLanguage('en')" class="flex items-center justify-between w-full px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1BBC9A] transition-colors cursor-pointer text-left">
              <span>ENG (English)</span>
              <span>🇬🇧</span>
            </button>
            <button type="button" onclick="setLanguage('zh-CN')" class="flex items-center justify-between w-full px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1BBC9A] transition-colors cursor-pointer text-left">
              <span>CHN (China)</span>
              <span>🇨🇳</span>
            </button>
          </div>
        </div>

        <!-- Container Tersembunyi Mesin Google Translate -->
        <div id="google_translate_element" style="display: none !important;"></div>

        <!-- Tombol Search Khusus HP -->
        <button onclick="toggleMobileSearch()" aria-label="Search" class="block sm:hidden hover:text-brand-green transition-colors p-1">
          <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>

        <!-- KERANJANG -->
        <a href="{{ route('cart.index') }}" aria-label="Cart" class="relative hover:text-brand-green text-gray-700 transition-colors p-1">
          <i class="fa-solid fa-cart-shopping text-lg"></i>
          @if($cartCount > 0)
            <span id="cartBadgeCount" class="absolute -top-1 -right-1.5 flex items-center justify-center w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-bold shadow-sm">
              {{ $cartCount }}
            </span>
          @endif
        </a>

        <!-- AUTENTIKASI CUSTOMER DESKTOP -->
        <div class="hidden md:block relative dropdown-profile-container">
          @if(Auth::guard('customer')->check())
            <div class="relative inline-block text-left">
              <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-3 py-2 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-user-tie text-brand-green text-sm"></i>
                <span class="max-w-[120px] truncate">{{ Auth::guard('customer')->user()->nama_lengkap }}</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
              </button>
              
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
            <div class="flex items-center gap-2 text-xs font-medium">
              <a href="{{ route('customer.register') }}" class="hover:text-brand-green transition-colors">Daftar</a>
              <span class="text-gray-300">|</span>
              <a href="{{ route('customer.login') }}" class="font-bold text-brand-green hover:text-brand-green-dark transition-colors">Log In</a>
            </div>
          @endif
        </div>

      </div>
    </div>

    <!-- MENU BOTTOM TAUTAN DESKTOP -->
    <div id="desktopBottomNav" class="hidden md:flex items-center justify-center border-t border-gray-50 transition-all duration-500 ease-in-out opacity-100 h-11 overflow-hidden shrink-0">
      <nav class="flex items-center gap-10 text-xs md:text-sm text-gray-600 font-semibold tracking-wide py-3">
        <a href="{{ url('/') }}" class="hover:text-brand-green transition-colors {{ Request::is('/') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Beranda</a>
        <a href="{{ url('/products') }}" class="hover:text-brand-green transition-colors {{ Request::is('products*') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Produk</a>
        <a href="{{ route('customer.orders.index') }}" class="hover:text-brand-green transition-colors {{ Request::is('*orders*') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Pesanan</a>
        <a href="{{ url('/about') }}" class="hover:text-brand-green transition-colors {{ Request::is('about') ? 'text-brand-green font-bold border-b-2 border-brand-green pb-1' : '' }}">Tentang Kami</a>
      </nav>
    </div>

    <!-- MOBILE SEARCH BAR -->
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
      <div id="mobileSearchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-100 rounded-lg shadow-xl z-50 overflow-hidden max-h-64 overflow-y-auto"></div>
    </div>

  </div>
</header>

<!-- ========== MOBILE BOTTOM NAVIGATION BAR ========== -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.06)]">
  <div class="flex items-center justify-around h-14 px-1">
    <a href="{{ url('/') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('/') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-house text-base"></i>
      <span class="text-[10px] leading-tight">Beranda</span>
    </a>
    <a href="{{ url('/products') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('products*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-box-open text-base"></i>
      <span class="text-[10px] leading-tight">Produk</span>
    </a>
    <a href="{{ route('customer.orders.index') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('*orders*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-receipt text-base"></i>
      <span class="text-[10px] leading-tight">Pesanan</span>
    </a>
    <a href="{{ url('/about') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('about') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
      <i class="fa-solid fa-building text-base"></i>
      <span class="text-[10px] leading-tight">Tentang</span>
    </a>
    @if(Auth::guard('customer')->check())
      <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center gap-0.5 w-16 py-1 {{ Request::is('customer/profile*') ? 'text-brand-green font-bold' : 'text-gray-400' }}">
        <i class="fa-solid fa-user-circle text-base"></i>
        <span class="text-[10px] leading-tight truncate max-w-[55px]">Saya</span>
      </a>
    @else
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

      <div class="text-left border-l border-white/10 pl-3 md:pl-6">
        <h3 class="text-white font-bold text-xs md:text-base mb-2 tracking-wide">Menu</h3>
        <ul class="space-y-1 text-[10px] md:text-xs text-gray-300 font-medium">
          <li><a href="{{ url('/') }}" class="hover:text-brand-green transition-colors block py-0.5">Beranda</a></li>
          <li><a href="{{ url('/products') }}" class="hover:text-brand-green transition-colors block py-0.5">Produk</a></li>
          <li><a href="{{ url('/about') }}" class="hover:text-brand-green transition-colors block py-0.5">Tentang Kami</a></li>
        </ul>
      </div>

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

  <div class="border-t border-white/5 bg-black/20">
    <p class="text-center text-[9px] md:text-xs text-gray-400 py-3 tracking-wide">
      &copy; 2026 Tangga Mas Scaffolding & Formwork. All rights 
      <a href="{{ route('login') }}" class="hover:text-gray-300 transition-colors select-none">reserved.</a>
    </p>
  </div>
</footer>

<!-- SCRIPT GOOGLE TRANSLATE ENGINE & KONTROL MULTI-BAHASA -->
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'id',
      includedLanguages: 'id,en,zh-CN',
      autoDisplay: false
    }, 'google_translate_element');
  }

  function setLanguage(lang) {
    document.cookie = "googtrans=/id/" + lang + "; path=/;";
    document.cookie = "googtrans=/id/" + lang + "; domain=" + window.location.hostname + "; path=/;";
    location.reload();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const cookies = document.cookie.split(';');
    let currentLang = 'id';
    
    for (let c of cookies) {
      c = c.trim();
      if (c.startsWith('googtrans=')) {
        const val = c.substring('googtrans='.length);
        if (val.includes('/zh-CN')) currentLang = 'zh-CN';
        else if (val.includes('/en')) currentLang = 'en';
        else currentLang = 'id';
        break;
      }
    }

    const flagElem = document.getElementById('activeFlag');
    const textElem = document.getElementById('activeLangText');

    if (currentLang === 'zh-CN') {
      if (flagElem) flagElem.textContent = '🇨🇳';
      if (textElem) textElem.textContent = 'CHN';
    } else if (currentLang === 'en') {
      if (flagElem) flagElem.textContent = '🇬🇧';
      if (textElem) textElem.textContent = 'ENG';
    } else {
      if (flagElem) flagElem.textContent = '🇮🇩';
      if (textElem) textElem.textContent = 'IDN';
    }
  });
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- JAVASCRIPT NAVIGASI & SEARCH CONTROL -->
<script>
function toggleMobileSearch() {
  const searchInput = document.getElementById('mobileSearchInput');
  if (searchInput) searchInput.classList.toggle('hidden');
}

function toggleProfileDropdown() {
  const dropdown = document.getElementById('profileDropdown');
  if (dropdown) dropdown.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
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

    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', function() {
        const query = input.value.trim();
        if (query.length >= 2) {
          trackGAEvent('search_query', {
            search_term: query,
            search_type: 'submit_enter',
            source_page: 'header_searchbar'
          });
        }
      });
    }

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
              
              item.addEventListener('click', function() {
                trackGAEvent('search_query', {
                  search_term: query,
                  search_type: 'live_search_click',
                  clicked_product: produk.nama,
                  source_page: 'header_searchbar'
                });
              });

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
            
            footer.addEventListener('click', function() {
              trackGAEvent('search_query', {
                search_term: query,
                search_type: 'live_search_view_all',
                source_page: 'header_searchbar'
              });
            });

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

<!-- GA4 TIMER MONITORING -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let interestedTimer = setTimeout(function() {
            if (!document.hidden) {
                trackGAEvent('user_interested_30s', {
                    page_path: window.location.pathname,
                    page_title: document.title,
                    status: 'interested'
                });
            }
        }, 30000);

        let idleTimer = setTimeout(function() {
            trackGAEvent('page_idle_abandoned_4m', {
                page_path: window.location.pathname,
                page_title: document.title,
                status: 'tab_left_open'
            });
        }, 240000);

        window.addEventListener('beforeunload', function() {
            clearTimeout(interestedTimer);
            clearTimeout(idleTimer);
        });
    });
</script>

<script src="{{ asset('script.js') }}"></script>
</body>
</html>