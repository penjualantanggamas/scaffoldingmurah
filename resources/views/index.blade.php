@extends('layouts.frontend')

@section('title', 'Tangga Mas | Scaffolding & Formwork')

@section('content')
<!-- ========== HERO SECTION (CAROUSEL) ========== -->
<section class="container mx-auto px-4 pt-4 md:pt-5">
  <div class="carousel relative rounded-xl overflow-hidden aspect-[16/9] md:aspect-[2.4/1] w-full bg-gray-100 group" data-carousel data-autoplay="5000">
    <!-- Track -->
    <div class="carousel-track flex h-full transition-transform duration-700 ease-out">
      <div class="carousel-slide relative min-w-full h-full bg-white flex items-center justify-center">
        <img src="{{ asset('images/banners/webp/mainbanner2.webp') }}" class="w-full h-full object-contain" alt="Banner Tangga Mas Scaffolding 1">
      </div>
      <div class="carousel-slide relative min-w-full h-full bg-white flex items-center justify-center">
        <img src="{{ asset('images/banners/webp/mainbanner1.webp') }}" class="w-full h-full object-contain" alt="Banner Tangga Mas Scaffolding 2">
      </div>
      <div class="carousel-slide relative min-w-full h-full bg-white flex items-center justify-center">
        <img src="{{ asset('images/banners/webp/mainbanner3.webp') }}" class="w-full h-full object-contain" alt="Banner Tangga Mas Scaffolding 3">
      </div>
    </div>

    <!-- Prev / Next buttons -->
    <button type="button" class="carousel-prev absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide sebelumnya">
      <i class="fa-solid fa-chevron-left text-xs md:text-base"></i>
    </button>
    <button type="button" class="carousel-next absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide berikutnya">
      <i class="fa-solid fa-chevron-right text-xs md:text-base"></i>
    </button>

    <!-- Dots -->
    <div class="carousel-dots absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 md:gap-2 z-10">
      <button type="button" class="carousel-dot w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-white/50 transition-all" data-index="0" aria-label="Ke slide 1"></button>
      <button type="button" class="carousel-dot w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-white/50 transition-all" data-index="1" aria-label="Ke slide 2"></button>
      <button type="button" class="carousel-dot w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-white/50 transition-all" data-index="2" aria-label="Ke slide 3"></button>
    </div>
  </div>
</section>


<!-- ========== KATEGORI SISTEM SCAFFOLDING ========== -->
<section class="container mx-auto px-4 mt-6 md:mt-8">
  <div class="flex md:justify-center items-center gap-2 overflow-x-auto no-scrollbar pb-2 md:pb-0 whitespace-nowrap">
    <a href="{{ url('/products#frame-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
      <span class="text-xs font-semibold text-gray-600 tracking-wide">Frame System</span>
    </a>
    <a href="{{ url('/products#ringlock-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
      <span class="text-xs font-semibold text-gray-600 tracking-wide">Ringlock System</span>
    </a>
    <a href="{{ url('/products#tubular-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
      <span class="text-xs font-semibold text-gray-600 tracking-wide">Tubular System</span>
    </a>
    <a href="{{ url('/products#kwikstage-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
      <span class="text-xs font-semibold text-gray-600 tracking-wide">Kwikstage System</span>
    </a>
    <a href="{{ url('/products#bekisting-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
      <span class="text-xs font-semibold text-gray-600 tracking-wide">Bekisting System</span>
    </a>
  </div>
</section>


<!-- ========== REKOMENDASI PROMO DISKON TERBESAR (DINAMIS) ========== -->
<section class="container mx-auto px-4 mt-8 md:mt-12 pb-4 md:pb-10">
  <div class="flex justify-between items-center mb-4 md:mb-6">
    <h2 class="text-base md:text-2xl font-bold text-gray-900">Rekomendasi Promo Terbaik</h2>
  </div>
  <div class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 overflow-x-auto no-scrollbar pb-4 md:pb-0 whitespace-nowrap md:whitespace-normal -mx-4 px-4 md:mx-0 md:px-0">
    @forelse ($recommendedProducts as $item)
    <a href="{{ url('/products/detail/' . $item->slug) }}" class="w-[150px] sm:w-[190px] md:w-full bg-white rounded-xl overflow-hidden hover:shadow-md transition-all group block border border-gray-100 hover:border-gray-200 shrink-0 flex flex-col justify-between">
      <div>
        <!-- Gambar Full tanpa padding -->
        <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
          @if($item->gambar)
          <img src="{{ asset('images/products/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
          @else
          <i class="fa-regular fa-image text-gray-300 text-3xl md:text-4xl"></i>
          @endif
          @if($item->harga_coret)
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="absolute top-2 right-2 text-[9px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
        <!-- Konten teks dengan padding -->
        <div class="p-3 md:p-4">
          <p class="text-[10px] md:text-xs text-gray-400 mb-0.5 uppercase tracking-wide font-medium capitalize">{{ $item->kategori }} System</p>
          <h3 class="font-semibold text-gray-900 text-xs md:text-sm mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] whitespace-normal leading-snug" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h3>
        </div>
      </div>
      <div class="flex flex-col text-left px-3 md:px-4 pb-3 md:pb-4">
        <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
        @if($item->harga_coret)
        <span class="text-[10px] text-red-400 line-through mt-0.5">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
        @endif
      </div>
    </a>
    @empty
    <div class="w-full text-center py-12 text-gray-400 bg-white border border-dashed rounded-xl md:col-span-2 lg:col-span-4">
      <i class="fa-solid fa-tags text-3xl text-gray-300 mb-2 block"></i>
      <p class="text-sm">Saat ini belum ada produk scaffolding yang sedang dalam masa promo diskon.</p>
    </div>
    @endforelse
  </div>
</section>


<!-- ========== SECTION PRODUK TERLARIS (DINAMIS DARI ADMIN) ========== -->
<section id="produk" class="bg-brand-gray-bg py-8 md:py-14">
  <div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4 md:mb-6">
      <h2 class="text-base md:text-2xl font-bold text-gray-900">Produk Terlaris</h2>
    </div>
    <div class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 overflow-x-auto no-scrollbar pb-4 md:pb-0 whitespace-nowrap md:whitespace-normal -mx-4 px-4 md:mx-0 md:px-0">
      @forelse ($bestSellerProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="w-[150px] sm:w-[190px] md:w-full bg-white rounded-xl overflow-hidden hover:shadow-md transition-shadow group cursor-pointer block shrink-0 flex flex-col justify-between border border-gray-100">
        <div>
          <!-- Gambar Full tanpa padding -->
          <div class="bg-white aspect-square flex items-center justify-center overflow-hidden relative">
            @if($item->gambar)
            <img src="{{ asset('images/products/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logotm.png') }}'">
            @else
            <i class="fa-regular fa-image text-gray-300 text-3xl md:text-4xl"></i>
            @endif
            <span class="absolute top-2 left-2 bg-amber-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm flex items-center gap-0.5">
              <i class="fa-solid fa-fire text-[8px]"></i> HOT
            </span>
          </div>
          <!-- Konten teks dengan padding -->
          <div class="p-3 md:p-4">
            <p class="text-[10px] md:text-xs text-gray-400 mb-0.5 truncate whitespace-normal leading-tight" title="{{ $item->spesifikasi ?? 'Scaffolding Premium SNI' }}">
              {{ $item->spesifikasi ?? 'Scaffolding Premium SNI' }}
            </p>
            <h3 class="font-semibold text-gray-900 text-xs md:text-sm mb-1 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] whitespace-normal leading-snug" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h3>
          </div>
        </div>
        <div class="flex flex-col text-left px-3 md:px-4 pb-3 md:pb-4">
          <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
          @if($item->harga_coret)
          <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
            <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
            <span class="text-[9px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}%</span>
            @endif
          </div>
          @endif
        </div>
      </a>
      @empty
      <div class="w-full text-center py-12 text-gray-400 bg-white border border-dashed rounded-xl md:col-span-2 lg:col-span-4">
        <i class="fa-solid fa-star-half-stroke text-3xl text-gray-300 mb-2 block"></i>
        <p class="text-sm">Belum ada produk terlaris yang dipilih oleh admin.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>


<!-- ========== TERTIARY BANNER ========== -->
<section class="container mx-auto px-4 pb-10 md:pb-14 md:pt-10">
  <div class="rounded-xl overflow-hidden aspect-[2.8/1] w-full bg-white flex items-center justify-center shadow-sm border border-gray-100"> 
    <img 
      src="{{ asset('images/banners/webp/bannerbawah.webp') }}" 
      alt="Banner Promosi Tangga Mas Scaffolding" 
      class="w-full h-full object-contain"
      onerror="this.src='{{ asset('images/logotm.png') }}'">
  </div>
</section>


<!-- ========== KENAPA PILIH ========== -->
<section id="kenapa" class="container mx-auto px-4 pb-16 md:pb-20 text-center max-w-3xl">
  <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-3 md:mb-4">Kenapa Pilih Tangga Mas</h2>
  <p class="text-xs md:text-sm text-gray-500 leading-relaxed md:leading-xl">Sebagai produsen perancah terbesar nomor satu di Jawa Timur, PT Tangga Mas Jaya Makmur memproduksi produk berkualitas standar proyek konstruksi besar dengan sertifikasi resmi dan harga yang sangat bersaing.</p>
</section>
@endsection
