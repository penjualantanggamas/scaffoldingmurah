@extends('layouts.app')

@section('title', 'Tangga Mas | Scaffolding & Formwork')

@section('content')
  <!-- ========== HERO SECTION (CAROUSEL) ========== -->
  <section class="container mx-auto px-4 pt-8 md:pt-10">
    <div class="carousel relative rounded-xl overflow-hidden h-56 md:h-80 group" data-carousel data-autoplay="5000">

      <!-- Track -->
      <div class="carousel-track flex h-full transition-transform duration-700 ease-out">
        <div class="carousel-slide relative min-w-full h-full bg-gray-100 flex items-center justify-center">
          <img src="{{ asset('images/banners/banner1.png') }}" class="w-full h-full object-cover" alt="Banner Tangga Mas Scaffolding 1">
        </div>
        <div class="carousel-slide relative min-w-full h-full bg-gray-100 flex items-center justify-center">
          <img src="{{ asset('images/banners/banner2.png') }}" class="w-full h-full object-cover" alt="Banner Tangga Mas Scaffolding 2">
        </div>
        <div class="carousel-slide relative min-w-full h-full bg-gray-100 flex items-center justify-center">
          <img src="{{ asset('images/banners/banner3.png') }}" class="w-full h-full object-cover" alt="Banner Tangga Mas Scaffolding 3">
        </div>
      </div>

      <!-- Prev / Next buttons -->
      <button type="button" class="carousel-prev absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide sebelumnya">
        <i class="fa-solid fa-chevron-left text-sm md:text-base"></i>
      </button>
      <button type="button" class="carousel-next absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide berikutnya">
        <i class="fa-solid fa-chevron-right text-sm md:text-base"></i>
      </button>

      <!-- Dots -->
      <div class="carousel-dots absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="0" aria-label="Ke slide 1"></button>
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="1" aria-label="Ke slide 2"></button>
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="2" aria-label="Ke slide 3"></button>
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="3" aria-label="Ke slide 4"></button>
      </div>
    </div>
  </section>

  <!-- ========== KATEGORI SISTEM SCAFFOLDING ========== -->
  <section class="container mx-auto px-4 mt-8 md:mt-10">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 md:gap-4">
      <a href="{{ url('/products#frame-system') }}" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all cursor-pointer">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Frame System</span>
      </a>
      <a href="{{ url('/products#ringlock-system') }}" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all cursor-pointer">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Ringlock System</span>
      </a>
      <a href="{{ url('/products#tubular-system') }}" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all cursor-pointer">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Tubular System</span>
      </a>
      <a href="{{ url('/products#kwikstage-system') }}" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all cursor-pointer">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Kwikstage System</span>
      </a>
      <a href="{{ url('/products#bekisting-system') }}" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all cursor-pointer">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Bekisting System</span>
      </a>
    </div>
  </section>

  <!-- ========== SECONDARY BANNER (CAROUSEL) ========== -->
  <section class="container mx-auto px-4 mt-8 md:mt-10">
    <div class="carousel relative rounded-xl overflow-hidden h-44 md:h-56 group" data-carousel data-autoplay="4000">
      <div class="carousel-track flex h-full transition-transform duration-700 ease-out">
        <div class="carousel-slide relative min-w-full h-full bg-brand-green-dark flex items-center justify-center"><i class="fa-regular fa-image text-white/60 text-5xl"></i></div>
        <div class="carousel-slide relative min-w-full h-full bg-brand-green flex items-center justify-center"><i class="fa-regular fa-image text-white/60 text-5xl"></i></div>
        <div class="carousel-slide relative min-w-full h-full bg-brand-green-dark flex items-center justify-center"><i class="fa-regular fa-image text-white/60 text-5xl"></i></div>
      </div>
      <button type="button" class="carousel-prev absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-8 h-8 md:w-9 md:h-9 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide sebelumnya"><i class="fa-solid fa-chevron-left text-sm"></i></button>
      <button type="button" class="carousel-next absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-8 h-8 md:w-9 md:h-9 rounded-full bg-white/20 text-white backdrop-blur-sm hover:bg-white/40 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100" aria-label="Slide berikutnya"><i class="fa-solid fa-chevron-right text-sm"></i></button>
      <div class="carousel-dots absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="0" aria-label="Ke slide 1"></button>
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="1" aria-label="Ke slide 2"></button>
        <button type="button" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition-all" data-index="2" aria-label="Ke slide 3"></button>
      </div>
    </div>
  </section>

  <!-- ========== REKOMENDASI PROMO DISKON TERBESAR (DINAMIS) ========== -->
  <section class="container mx-auto px-4 mt-10 pb-10 md:pb-14">
    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Rekomendasi Promo Terbaik</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @forelse ($recommendedProducts as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-brand-gray-bg rounded-xl p-4 hover:shadow-md transition-all group block border border-transparent hover:border-gray-200">
        <div class="bg-white rounded-lg aspect-square flex items-center justify-center mb-4 overflow-hidden border border-gray-100">
          @if($item->gambar)
            <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
          @else
            <i class="fa-regular fa-image text-gray-300 text-4xl"></i>
          @endif
        </div>
        <p class="text-xs text-gray-500 mb-1 capitalize">{{ $item->kategori }} System</p>
        <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors line-clamp-1">{{ $item->nama_produk }}</h3>
        <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
          <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
          @if($item->harga_coret)
            <span class="text-xs text-red-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
            @if($diskon > 0)
              <span class="text-[10px] font-bold text-white bg-red-500 px-1 rounded">{{ $diskon }}% OFF</span>
            @endif
          @endif
        </div>
      </a>
      @empty
      <div class="col-span-full text-center py-12 text-gray-400 bg-white border border-dashed rounded-xl">
        <i class="fa-solid fa-tags text-3xl text-gray-300 mb-2 block"></i>
        <p class="text-sm">Saat ini belum ada produk scaffolding yang sedang dalam masa promo diskon.</p>
      </div>
      @endforelse
    </div>
  </section>

  <!-- ========== SECTION PRODUK TERLARIS (DINAMIS DARI ADMIN) ========== -->
  <section id="produk" class="bg-brand-gray-bg md:mt-10 py-10 md:py-14">
    <div class="container mx-auto px-4">
      <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Produk Terlaris</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($bestSellerProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar)
              <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else
              <i class="fa-regular fa-image text-gray-300 text-4xl"></i>
            @endif
          </div>
          <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium SNI' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0)
                <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span>
              @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400 bg-white border border-dashed rounded-xl">
          <i class="fa-solid fa-star-half-stroke text-3xl text-gray-300 mb-2 block"></i>
          <p class="text-sm">Belum ada produk terlaris yang dipilih oleh admin.</p>
        </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ========== TRUST BADGES / USP ========== -->
  <section class="container mx-auto px-4 py-10 md:py-14">
    <div class="flex flex-wrap items-center justify-center gap-4">
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Produsen Terbesar</span></div>
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Berkualitas</span></div>
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Produk Lengkap</span></div>
    </div>
  </section>

  <!-- ========== TERTIARY BANNER ========== -->
  <section class="container mx-auto px-4 pb-10 md:pb-14">
    <div class="rounded-xl bg-gradient-to-br from-brand-green to-brand-green-dark h-64 md:h-80 flex items-center justify-center"><i class="fa-regular fa-image text-white/60 text-6xl"></i></div>
  </section>

  <!-- ========== KENAPA PILIH ========== -->
  <section id="kenapa" class="container mx-auto px-4 pb-16 md:pb-20 text-center max-w-3xl">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Kenapa Pilih Scaffolding Tangga Mas</h2>
    <p class="text-gray-500 leading-relaxed">Sebagai produsen perancah terbesar nomor satu di Jawa Timur, PT Tangga Mas Jaya Makmur memproduksi produk berkualitas standar proyek konstruksi besar dengan sertifikasi resmi dan harga yang sangat bersaing.</p>
  </section>
@endsection