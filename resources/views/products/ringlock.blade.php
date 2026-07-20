@extends('layouts.frontend')

@section('title', 'Ringlock System | Tangga Mas Scaffolding')

@section('content')
  <section class="container mx-auto px-4 pt-8 md:pt-10">
    <div class="rounded-xl bg-gradient-to-br from-brand-green-dark to-brand-green h-44 md:h-64 flex items-center justify-center">
      <i class="fa-regular fa-image text-white/60 text-6xl"></i>
    </div>
  </section>

  <section class="container mx-auto mt-5 px-4 pb-16">
    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-8">Ringlock System</h2>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10">
      
      @forelse ($produks as $item)
      <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg group cursor-pointer block">
        
        <div class="bg-brand-gray-bg border border-gray-100 rounded-lg aspect-square flex items-center justify-center mb-4 transition-shadow group-hover:shadow-md overflow-hidden relative">
          @if($item->gambar)
            <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
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

      </a> @empty
      <div class="col-span-full text-center py-16 text-gray-400 bg-brand-gray-bg rounded-xl border border-dashed border-gray-200">
          <i class="fa-solid fa-box-open text-4xl mb-3 block text-gray-300"></i>
          <p class="text-sm">Belum ada produk untuk kategori Ringlock System.</p>
          <a href="{{ route('produk.create') }}" class="text-brand-green-dark text-xs underline font-medium mt-1 inline-block hover:text-brand-green">Tambah produk sekarang</a>
      </div>
      @endforelse

    </div>
  </section>
@endsection