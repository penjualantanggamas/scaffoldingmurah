@extends('layouts.frontend')

@php
    // Fallback safe declaration jika dipanggil dari controller/view tunggal
    $categoryBanner = $categoryBanner ?? null;
    $bContent = $categoryBanner?->content ?? [];

    // Safe decode jika tersimpan sebagai string JSON
    while (is_string($bContent)) {
        $bContent = json_decode($bContent, true) ?? [];
    }

    if (!is_array($bContent)) {
        $bContent = [];
    }

    $imgFileName = $bContent['image'] ?? null;
    $bImage = !empty($imgFileName) ? asset('images/banners/webp/' . $imgFileName) : null;

    $catName = isset($kategoriClean) ? ucfirst($kategoriClean) . ' System' : 'Bekisting System';
    $bJudul = !empty($bContent['judul_banner']) ? $bContent['judul_banner'] : $catName;
    $bSubjudul = $bContent['subjudul_banner'] ?? '';
@endphp

@section('title', $bJudul . ' | Tangga Mas Scaffolding')

@section('content')
  <!-- ========== TOP BANNER KATEGORI DINAMIS ========== -->
  <section class="container mx-auto px-4 pt-6 md:pt-10">
    @if($bImage)
      <div class="relative rounded-xl overflow-hidden aspect-[2.4/1] md:aspect-[4/1] w-full flex flex-col items-center justify-center text-center px-4 shadow-sm bg-gray-100">
        <img src="{{ $bImage }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $bJudul }}">
        <div class="absolute inset-0 bg-black/45"></div>
        <div class="relative z-10 px-2">
          <h1 class="text-xl md:text-4xl font-bold text-white tracking-wide drop-shadow-sm">{{ $bJudul }}</h1>
          @if($bSubjudul)
            <p class="text-white/80 text-[10px] md:text-sm mt-0.5 md:mt-1 max-w-md mx-auto">{{ $bSubjudul }}</p>
          @endif
        </div>
      </div>
    @else
      <div class="rounded-xl bg-gradient-to-br from-brand-green-dark to-brand-green h-44 md:h-64 flex flex-col items-center justify-center text-center px-4 shadow-sm">
        <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide drop-shadow-sm mb-1">{{ $bJudul }}</h1>
        @if($bSubjudul)
          <p class="text-white/80 text-xs md:text-sm max-w-md mx-auto">{{ $bSubjudul }}</p>
        @endif
      </div>
    @endif
  </section>

  <!-- ========== DAFTAR PRODUK KATEGORI ========== -->
  <section class="container mx-auto mt-6 px-4 pb-16">
    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-8">{{ $catName }}</h2>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10">
      
      @forelse ($produks as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg group cursor-pointer block">
        
        <div class="bg-brand-gray-bg border border-gray-100 rounded-lg aspect-square flex items-center justify-center mb-4 transition-shadow group-hover:shadow-md overflow-hidden relative">
          @if($item->gambar)
            <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover" alt="{{ $item->nama_produk }}">
          @else
            <i class="fa-regular fa-image text-gray-300 text-5xl"></i>
          @endif
        </div>
        
        <p class="text-xs text-gray-500 mb-1">{{ $item->spesifikasi ?? 'Scaffolding Premium' }}</p>
        <h3 class="font-bold text-gray-900 mb-1 text-sm md:text-base group-hover:text-brand-green transition-colors">{{ $item->nama_produk }}</h3>
        
        <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
          <span class="text-brand-price font-bold text-base md:text-lg">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
          
          @if($item->harga_coret)
            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
            
            @php
              $diskon = round((($item->harga_coret - $item->harga) / $item->harga_coret) * 100);
            @endphp
            @if($diskon > 0)
              <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1 rounded">{{ $diskon }}%</span>
            @endif
          @endif
        </div>

      </a> 
      @empty
      <div class="col-span-full text-center py-16 text-gray-400 bg-brand-gray-bg rounded-xl border border-dashed border-gray-200">
          <i class="fa-solid fa-box-open text-4xl mb-3 block text-gray-300"></i>
          <p class="text-sm">Belum ada produk untuk kategori {{ $catName }}.</p>
          <a href="{{ route('produk.create') }}" class="text-brand-green-dark text-xs underline font-medium mt-1 inline-block hover:text-brand-green">Tambah produk sekarang</a>
      </div>
      @endforelse

    </div>
  </section>
@endsection