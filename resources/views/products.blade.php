@extends('layouts.frontend')

@section('title', 'Products | Tangga Mas Scaffolding')

@section('content')

<!-- ========== TOP BANNER HEADER (DINAMIS & AMAN) ========== -->
<section class="container mx-auto px-4 pt-6 md:pt-10">
@php
    // Pengaman jika variabel tidak dikirim dari controller
    $catalogHeaderBanner = $catalogHeaderBanner ?? null;
    
    $headerRaw = $catalogHeaderBanner?->content;
    $headerContent = is_array($headerRaw) ? $headerRaw : (json_decode($headerRaw ?? '[]', true) ?? []);
    
    $headerImg = (!empty($headerContent['image']) && file_exists(public_path('images/banners/webp/' . $headerContent['image'])))
        ? asset('images/banners/webp/' . $headerContent['image']) 
        : asset('images/buildringlock1.svg');
        
    $headerJudul = $headerContent['judul_banner'] ?? 'Semua Produk';
    $headerSub   = $headerContent['subjudul_banner'] ?? 'Katalog lengkap Scaffolding & Bekisting berkualitas industri';
@endphp
<div class="relative rounded-xl overflow-hidden aspect-[2.4/1] md:aspect-[4/1] w-full flex flex-col items-center justify-center text-center px-4 shadow-sm bg-gray-100">
  <img src="{{ $headerImg }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $headerJudul }}" onerror="this.src='{{ asset('images/buildringlock1.svg') }}'">
  <div class="absolute inset-0 bg-black/45"></div>
  <div class="relative z-10 px-2">
    <h1 class="text-xl md:text-4xl font-bold text-white tracking-wide drop-shadow-sm">{{ $headerJudul }}</h1>
    <p class="text-white/80 text-[10px] md:text-sm mt-0.5 md:mt-1 max-w-md mx-auto">{{ $headerSub }}</p>
  </div>
</div>
</section>

<!-- ========== KATEGORI SISTEM SCAFFOLDING (MODEL PILLS BERGAMBAR) ========== -->
<section class="container mx-auto px-4 mt-5 md:mt-8">
  @php
      $categoryDetails = [
          'frame' => [
              'label' => 'Frame System',
              'image' => asset('images/categories/1 Set Main Frame.png'),
              'icon'  => 'fa-border-all',
          ],
          'ringlock' => [
              'label' => 'Ringlock System',
              'image' => asset('images/categories/1 set ringlock.png'),
              'icon'  => 'fa-dharmachakra',
          ],
          'tubular' => [
              'label' => 'Tubular System',
              'image' => asset('images/categories/1 set tubular.png'),
              'icon'  => 'fa-boxes-stacked',
          ],
          'kwikstage' => [
              'label' => 'Kwikstage System',
              'image' => asset('images/categories/1 set kwikstage.png'),
              'icon'  => 'fa-cubes',
          ],
          'bekisting' => [
              'label' => 'Bekisting System',
              'image' => asset('images/categories/1 Set Bekisting.png'),
              'icon'  => 'fa-layer-group',
          ],
      ];
  @endphp

  <div class="flex items-center md:justify-center gap-2.5 md:gap-3 overflow-x-auto no-scrollbar pb-3 md:pb-0 whitespace-nowrap -mx-4 px-4 md:mx-0 md:px-0">
    @foreach($categoryDetails as $catKey => $cat)
      <a href="{{ url('/products#' . $catKey . '-system') }}" 
         onclick="trackGAEvent('click_category_pill', { category_name: '{{ $cat['label'] }}', source_page: 'katalog_produk' })"
         class="inline-flex items-center gap-2 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full border border-gray-200 bg-white hover:border-brand-green hover:bg-emerald-50/40 hover:shadow-sm transition-all duration-200 shrink-0 group cursor-pointer">
          
          <!-- Gambar Mini Kategori / Fallback Ikon -->
          <div class="w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shrink-0">
              <img src="{{ $cat['image'] }}" 
                   alt="{{ $cat['label'] }}" 
                   class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-200"
                   onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
              <i class="fa-solid {{ $cat['icon'] }} text-brand-green text-xs sm:text-sm hidden"></i>
          </div>

          <!-- Teks Nama Kategori -->
          <span class="text-[11px] md:text-xs font-semibold text-gray-700 group-hover:text-brand-green transition-colors">
              {{ $cat['label'] }}
          </span>
      </a>
    @endforeach
  </div>
</section>


<!-- ========== FRAME SYSTEM ========== -->
<section id="frame-system" class="bg-brand-gray-bg mt-8 md:mt-8 py-8 md:py-12 scroll-mt-24 overflow-hidden">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Frame System</h2>
      <a href="{{ url('/products/frame-system') }}" class="text-xs md:text-sm text-brand-green-dark font-semibold underline hover:no-underline">Selengkapnya</a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar md:grid md:grid-cols-4 gap-3 md:gap-5 -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($frameProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group cursor-pointer block w-[155px] sm:w-[180px] md:w-full shrink-0 border border-gray-100">
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-500 mb-0.5 truncate">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="text-xs md:text-sm font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] whitespace-normal">{{ $item->nama_produk }}</h3>
          <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-x-1.5 gap-y-0.5">
            <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
            <div class="flex items-center gap-1">
              <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full w-full text-center py-8 text-gray-400 bg-white border border-dashed rounded-xl"><p class="text-xs md:text-sm">Belum ada produk untuk kategori ini.</p></div>
      @endforelse
    </div>
  </div>
</section>


<!-- ========== RINGLOCK SYSTEM ========== -->
<section id="ringlock-system" class="bg-white py-8 md:py-12 scroll-mt-24 overflow-hidden">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Ringlock System</h2>
      <a href="{{ url('/products/ringlock-system') }}" class="text-xs md:text-sm text-brand-green-dark font-semibold underline hover:no-underline">Selengkapnya</a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar md:grid md:grid-cols-4 gap-3 md:gap-5 -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($ringlockProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-shadow group cursor-pointer block w-[155px] sm:w-[180px] md:w-full shrink-0">
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-500 mb-0.5 truncate">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="text-xs md:text-sm font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] whitespace-normal">{{ $item->nama_produk }}</h3>
          <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-x-1.5 gap-y-0.5">
            <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
            <div class="flex items-center gap-1">
              <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full w-full text-center py-8 text-gray-400 bg-white border border-dashed rounded-xl"><p class="text-xs md:text-sm">Belum ada produk untuk kategori ini.</p></div>
      @endforelse
    </div>
  </div>
</section>


<!-- ========== MINI USP / TRUST BADGES ========== -->
<section class="container mx-auto px-4 py-6 md:py-10 overflow-hidden">
  <div class="flex sm:grid overflow-x-auto no-scrollbar sm:grid-cols-3 gap-2.5 max-w-xl mx-auto -mx-4 px-4 sm:mx-auto">
    <div class="flex items-center justify-center gap-2 bg-white border border-gray-150 rounded-full px-5 py-2.5 shadow-sm text-center shrink-0 min-w-[140px] sm:min-w-0 w-auto sm:w-full">
      <span class="text-[11px] md:text-xs font-semibold text-gray-600 whitespace-nowrap">Produsen Terbesar</span>
    </div>
    <div class="flex items-center justify-center gap-2 bg-white border border-gray-150 rounded-full px-5 py-2.5 shadow-sm text-center shrink-0 min-w-[140px] sm:min-w-0 w-auto sm:w-full">
      <span class="text-[11px] md:text-xs font-semibold text-gray-600 whitespace-nowrap">Berkualitas Tinggi</span>
    </div>
    <div class="flex items-center justify-center gap-2 bg-white border border-gray-150 rounded-full px-5 py-2.5 shadow-sm text-center shrink-0 min-w-[140px] sm:min-w-0 w-auto sm:w-full">
      <span class="text-[11px] md:text-xs font-semibold text-gray-600 whitespace-nowrap">Produk Lengkap</span>
    </div>
  </div>
</section>


<!-- ========== TUBULAR SYSTEM ========== -->
<section id="tubular-system" class="bg-brand-gray-bg py-8 md:py-12 scroll-mt-24 overflow-hidden">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Tubular System</h2>
      <a href="{{ url('/products/tubular-system') }}" class="text-xs md:text-sm text-brand-green-dark font-semibold underline hover:no-underline">Selengkapnya</a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar md:grid md:grid-cols-4 gap-3 md:gap-5 -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($tubularProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group cursor-pointer block w-[155px] sm:w-[180px] md:w-full shrink-0 border border-gray-100">
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-500 mb-0.5 truncate">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="text-xs md:text-sm font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] whitespace-normal">{{ $item->nama_produk }}</h3>
          <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-x-1.5 gap-y-0.5">
            <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
            <div class="flex items-center gap-1">
              <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full w-full text-center py-8 text-gray-400 bg-white border border-dashed rounded-xl"><p class="text-xs md:text-sm">Belum ada produk untuk kategori ini.</p></div>
      @endforelse
    </div>
  </div>
</section>


<!-- ========== MIDDLE ADVERTISING BANNER (DINAMIS & AMAN) ========== -->
<section class="container mx-auto px-4 py-6 md:py-10">
@php
    // Pengaman jika variabel tidak dikirim dari controller
    $catalogMiddleBanner = $catalogMiddleBanner ?? null;

    $middleRaw = $catalogMiddleBanner?->content;
    $middleContent = is_array($middleRaw) ? $middleRaw : (json_decode($middleRaw ?? '[]', true) ?? []);
    
    $middleImg = (!empty($middleContent['image']) && file_exists(public_path('images/banners/webp/' . $middleContent['image'])))
        ? asset('images/banners/webp/' . $middleContent['image']) 
        : asset('images/banners/banner_tertiary.png');
        
    $middleLink = $middleContent['link'] ?? '#';
    $middleAlt  = $middleContent['alt'] ?? 'Promosi K3 Tangga Mas Scaffolding';
@endphp
<!-- EVENT GA4: Click Promotion -->
<a href="{{ $middleLink }}" 
   onclick="trackGAEvent('click_promotion', { promotion_name: '{{ $middleAlt }}', source_page: 'katalog_produk' })"
   class="block rounded-xl overflow-hidden aspect-[2.8/1] w-full bg-white flex items-center justify-center shadow-sm border border-gray-150 hover:opacity-95 transition-opacity">
  <img src="{{ $middleImg }}" alt="{{ $middleAlt }}" class="w-full h-full object-contain" onerror="this.src='{{ asset('images/logotm.png') }}'">
</a>
</section>


<!-- ========== KWIKSTAGE SYSTEM ========== -->
<section id="kwikstage-system" class="bg-brand-gray-bg py-8 md:py-12 scroll-mt-24 overflow-hidden">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Kwikstage System</h2>
      <a href="{{ url('/products/kwikstage-system') }}" class="text-xs md:text-sm text-brand-green-dark font-semibold underline hover:no-underline">Selengkapnya</a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar md:grid md:grid-cols-4 gap-3 md:gap-5 -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($kwikstageProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group cursor-pointer block w-[155px] sm:w-[180px] md:w-full shrink-0 border border-gray-100">
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-500 mb-0.5 truncate">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="text-xs md:text-sm font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] whitespace-normal">{{ $item->nama_produk }}</h3>
          <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-x-1.5 gap-y-0.5">
            <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
            <div class="flex items-center gap-1">
              <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full w-full text-center py-8 text-gray-400 bg-white border border-dashed rounded-xl"><p class="text-xs md:text-sm">Belum ada produk untuk kategori ini.</p></div>
      @endforelse
    </div>
  </div>
</section>


<!-- ========== BEKISTING SYSTEM ========== -->
<section id="bekisting-system" class="bg-white py-8 md:py-12 scroll-mt-24 overflow-hidden">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Bekisting System</h2>
      <a href="{{ url('/products/bekisting-system') }}" class="text-xs md:text-sm text-brand-green-dark font-semibold underline hover:no-underline">Selengkapnya</a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar md:grid md:grid-cols-4 gap-3 md:gap-5 -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($bekistingProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-shadow group cursor-pointer block w-[155px] sm:w-[180px] md:w-full shrink-0">
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-500 mb-0.5 truncate">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="text-xs md:text-sm font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] whitespace-normal">{{ $item->nama_produk }}</h3>
          <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-x-1.5 gap-y-0.5">
            <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
            <div class="flex items-center gap-1">
              <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full w-full text-center py-8 text-gray-400 bg-white border border-dashed rounded-xl"><p class="text-xs md:text-sm">Belum ada produk untuk kategori ini.</p></div>
      @endforelse
    </div>
  </div>
</section>

<!-- ========== GA4 EVENT: FILTER KATALOG & SEARCH QUERY ========== -->
@if(request()->has('kategori') || request()->has('search') || Request::segment(2))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Pelacak Filter Kategori Katalog
        @if(request()->has('kategori') || Request::segment(2))
            trackGAEvent('filter_katalog', {
                filter_category: '{{ request('kategori') ?? Request::segment(2) }}',
                source_page: 'katalog_produk'
            });
        @endif

        // 2. Pelacak Kata Kunci Pencarian (Search Query)
        @if(request()->has('search') && !empty(request('search')))
            trackGAEvent('search_query', {
                search_term: '{{ request('search') }}',
                source_page: 'katalog_produk'
            });
        @endif
    });
</script>
@endif

@endsection