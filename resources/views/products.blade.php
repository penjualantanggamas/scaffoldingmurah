@extends('layouts.app')

@section('title', 'Products | Tangga Mas Scaffolding')

@section('content')
  <section class="container mx-auto px-4 pt-8 md:pt-10">
    <div class="relative rounded-xl h-36 md:h-48 flex flex-col items-center justify-center text-center px-4 overflow-hidden shadow-sm bg-gray-100">
      
      <!-- 1. Elemen Gambar Latar Belakang -->
      <img src="{{ asset('images/buildringlock1.svg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Banner Semua Produk Tangga Mas">
      
      <!-- 2. Overlay Hitam Transparan (Supaya teks putih tetap kontras & terbaca tajam) -->
      <div class="absolute inset-0 bg-black/40"></div>

      <!-- 3. Konten Teks (Ditumpuk di paling atas menggunakan z-10) -->
      <div class="relative z-10">
        <h1 class="text-2xl md:text-4xl font-bold text-white tracking-wide drop-shadow-sm">Semua Produk</h1>
        <p class="text-white/80 text-xs md:text-sm mt-1 max-w-md">Katalog lengkap Scaffolding & Bekisting</p>
      </div>
  </section>

  <section class="container mx-auto px-4 mt-8 md:mt-10">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 md:gap-4">
      <a href="#frame-system" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Frame System</span>
      </a>
      <a href="#ringlock-system" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Ringlock System</span>
      </a>
      <a href="#tubular-system" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Tubular System</span>
      </a>
      <a href="#kwikstage-system" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Kwikstage System</span>
      </a>
      <a href="#bekisting-system" class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 hover:border-brand-green hover:shadow-sm transition-all">
        <span class="flex items-center justify-center w-9 h-9 rounded-md bg-brand-gray-bg shrink-0"><i class="fa-regular fa-image text-gray-400"></i></span>
        <span class="text-sm font-medium text-gray-700">Bekisting System</span>
      </a>
    </div>
  </section>

  <section id="frame-system" class="bg-brand-gray-bg mt-10 md:mt-14 py-10 md:py-12 scroll-mt-24">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Frame System</h2>
        <a href="{{ url('/products/frame-system') }}" class="text-sm text-brand-green-dark font-medium underline hover:no-underline">Selengkapnya</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($frameProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar) <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else <i class="fa-regular fa-image text-gray-300 text-4xl"></i> @endif
          </div>
          <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0) <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span> @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400 bg-white border border-dashed rounded-lg"><p class="text-sm">Belum ada produk yang ditambahakan untuk kategori ini.</p></div>
        @endforelse
      </div>
    </div>
  </section>

  <section id="ringlock-system" class="bg-brand-mint py-10 md:py-12 scroll-mt-24">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Ringlock System</h2>
        <a href="{{ url('/products/ringlock-system') }}" class="text-sm text-brand-green-dark font-medium underline hover:no-underline">Selengkapnya</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($ringlockProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar) <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else <i class="fa-regular fa-image text-gray-300 text-4xl"></i> @endif
          </div>
          <p class="text-xs text-gray-500 mb-1 Ramah">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0) <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span> @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400 bg-white border border-dashed rounded-lg"><p class="text-sm">Belum ada produk yang ditambahakan untuk kategori ini.</p></div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 py-10 md:py-12">
    <div class="flex flex-wrap items-center justify-center gap-4">
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Produsen Terbesar</span></div>
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Berkualitas</span></div>
      <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5"><i class="fa-regular fa-image text-gray-400 text-sm"></i><span class="text-sm font-medium text-gray-700">Produk Lengkap</span></div>
    </div>
  </section>

  <section id="tubular-system" class="bg-brand-gray-bg py-10 md:py-12 scroll-mt-24">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Tubular System</h2>
        <a href="{{ url('/products/tubular-system') }}" class="text-sm text-brand-green-dark font-medium underline hover:no-underline">Selengkapnya</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($tubularProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar) <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else <i class="fa-regular fa-image text-gray-300 text-4xl"></i> @endif
          </div>
          <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0) <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span> @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400 bg-white border border-dashed rounded-lg"><p class="text-sm">Belum ada produk yang ditambahakan untuk kategori ini.</p></div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 py-10 md:py-12">
    <div class="rounded-xl bg-brand-green-dark h-40 md:h-56 flex items-center justify-center"><i class="fa-regular fa-image text-white/60 text-5xl"></i></div>
  </section>

  <section id="kwikstage-system" class="bg-brand-gray-bg py-10 md:py-12 scroll-mt-24">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Kwikstage System</h2>
        <a href="{{ url('/products/kwikstage-system') }}" class="text-sm text-brand-green-dark font-medium underline hover:no-underline">Selengkapnya</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($kwikstageProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar) <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else <i class="fa-regular fa-image text-gray-300 text-4xl"></i> @endif
          </div>
          <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0) <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span> @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400 bg-white border border-dashed rounded-lg"><p class="text-sm">Belum ada produk yang ditambahakan untuk kategori ini.</p></div>
        @endforelse
      </div>
    </div>
  </section>

  <section id="bekisting-system" class="bg-brand-mint py-10 md:py-12 scroll-mt-24">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Bekisting System</h2>
        <a href="{{ url('/products/bekisting-system') }}" class="text-sm text-brand-green-dark font-medium underline hover:no-underline">Selengkapnya</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($bekistingProducts as $item)
        <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg p-4 hover:shadow-md transition-shadow group cursor-pointer block">
          <div class="bg-white border border-gray-100 rounded-md aspect-square flex items-center justify-center mb-4 overflow-hidden relative">
            @if($item->gambar) <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
            @else <i class="fa-regular fa-image text-gray-300 text-4xl"></i> @endif
          </div>
          <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
          <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
          <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
            <span class="text-brand-price font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            @if($item->harga_coret)
              <span class="text-xs text-red-300 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
              @php $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100); @endphp
              @if($diskon > 0) <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}% OFF</span> @endif
            @endif
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400 bg-white border border-dashed rounded-lg"><p class="text-sm">Belum ada produk yang ditambahakan untuk kategori ini.</p></div>
        @endforelse
      </div>
    </div>
  </section>
@endsection