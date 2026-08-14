@extends('layouts.frontend')

@section('title', 'Tangga Mas | Scaffolding & Formwork')

@section('content')

@if(isset($decorations) && $decorations->count() > 0)
    
    {{-- LOOPING KOMPONEN DEKORASI BERDASARKAN URUTAN DARI ADMIN --}}
    @foreach($decorations as $block)

        {{-- ==================== 1. HERO BANNER SLIDER ==================== --}}
        @if($block->type === 'banner_slider' && is_array($block->content) && count($block->content) > 0)
        <section class="container mx-auto px-4 pt-4 md:pt-5">
          <div class="carousel relative rounded-xl overflow-hidden aspect-[16/9] md:aspect-[2.4/1] w-full bg-gray-100 group" data-carousel data-autoplay="5000">
            
            <!-- Track Carousel -->
            <div class="carousel-track flex h-full transition-transform duration-700 ease-out">
              @foreach($block->content as $slide)
              @php
                  $targetUrl = !empty($slide['link']) ? $slide['link'] : '#';
                  $isExternal = str_starts_with($targetUrl, 'http://') || str_starts_with($targetUrl, 'https://');
              @endphp
              <div class="carousel-slide relative min-w-full h-full bg-white flex items-center justify-center">
                <a href="{{ $targetUrl }}" 
                   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                   class="w-full h-full block relative z-10 hover:opacity-95 transition-opacity cursor-pointer" 
                   title="{{ $slide['alt'] ?? $block->judul }}">
                  <img src="{{ asset('images/banners/webp/' . $slide['image']) }}" 
                       class="w-full h-full object-cover md:object-contain" 
                       alt="{{ $slide['alt'] ?? $block->judul }}" 
                       onerror="this.src='{{ asset('images/logotm.png') }}'">
                </a>
              </div>
              @endforeach
            </div>

            <!-- Buttons -->
            <button type="button" class="carousel-prev absolute left-3 top-1/2 -translate-y-1/2 z-20 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/30 text-white backdrop-blur-sm hover:bg-black/50 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100 cursor-pointer" aria-label="Slide sebelumnya">
              <i class="fa-solid fa-chevron-left text-xs md:text-base"></i>
            </button>
            <button type="button" class="carousel-next absolute right-3 top-1/2 -translate-y-1/2 z-20 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/30 text-white backdrop-blur-sm hover:bg-black/50 transition-colors opacity-0 group-hover:opacity-100 focus-visible:opacity-100 cursor-pointer" aria-label="Slide berikutnya">
              <i class="fa-solid fa-chevron-right text-xs md:text-base"></i>
            </button>

            <!-- Dots -->
            <div class="carousel-dots absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 md:gap-2 z-20">
              @foreach($block->content as $idx => $slide)
              <button type="button" class="carousel-dot w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-white/50 transition-all cursor-pointer" data-index="{{ $idx }}" aria-label="Ke slide {{ $idx + 1 }}"></button>
              @endforeach
            </div>

          </div>
        </section>


        {{-- ==================== 2. KATEGORI SISTEM SCAFFOLDING ==================== --}}
        @elseif($block->type === 'category_pills')
        @php
            $allCategories = [
                'frame'     => 'Frame System',
                'ringlock'  => 'Ringlock System',
                'tubular'   => 'Tubular System',
                'kwikstage' => 'Kwikstage System',
                'bekisting' => 'Bekisting System',
            ];
            $selectedCategories = $block->content['categories'] ?? array_keys($allCategories);
            if (empty($selectedCategories)) {
                $selectedCategories = array_keys($allCategories);
            }
        @endphp
        <section class="container mx-auto px-4 mt-6 md:mt-8">
          <div class="flex md:justify-center items-center gap-2 overflow-x-auto no-scrollbar pb-2 md:pb-0 whitespace-nowrap">
            @foreach($selectedCategories as $catKey)
              @if(isset($allCategories[$catKey]))
              <a href="{{ url('/products#' . $catKey . '-system') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-brand-green hover:text-brand-green hover:shadow-sm transition-all cursor-pointer shrink-0">
                <span class="text-xs font-semibold text-gray-600 tracking-wide">{{ $allCategories[$catKey] }}</span>
              </a>
              @endif
            @endforeach
          </div>
        </section>


        {{-- ==================== 3. REKOMENDASI PROMO DISKON ==================== --}}
        @elseif($block->type === 'product_promo')
        @php
            $promoProductIds = $block->content['product_ids'] ?? [];
            if (!empty($promoProductIds)) {
                $promoItems = \App\Models\Produk::whereIn('id', $promoProductIds)->get();
            } else {
                $promoItems = $recommendedProducts;
            }
        @endphp
        <section class="container mx-auto px-4 mt-8 md:mt-12 pb-4 md:pb-10">
          <div class="flex justify-between items-center mb-4 md:mb-6">
            <h2 class="text-base md:text-2xl font-bold text-gray-900">{{ $block->judul ?? 'Rekomendasi Promo Terbaik' }}</h2>
          </div>
          <div class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 overflow-x-auto no-scrollbar pb-4 md:pb-0 whitespace-nowrap md:whitespace-normal -mx-4 px-4 md:mx-0 md:px-0">
            @forelse ($promoItems as $item)
            <a href="{{ url('/products/detail/' . $item->slug) }}" class="w-[150px] sm:w-[190px] md:w-full bg-white rounded-xl overflow-hidden hover:shadow-md transition-all group block border border-gray-100 hover:border-gray-200 shrink-0 flex flex-col justify-between">
              <div>
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


        {{-- ==================== 4. PRODUK TERLARIS (HOT) ==================== --}}
        @elseif($block->type === 'product_hot')
        @php
            $hotProductIds = $block->content['product_ids'] ?? [];
            if (!empty($hotProductIds)) {
                $hotItems = \App\Models\Produk::whereIn('id', $hotProductIds)->get();
            } else {
                $hotItems = $bestSellerProducts;
            }
        @endphp
        <section id="produk" class="bg-brand-gray-bg py-8 md:py-14">
          <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-4 md:mb-6">
              <h2 class="text-base md:text-2xl font-bold text-gray-900">{{ $block->judul ?? 'Produk Terlaris' }}</h2>
            </div>
            <div class="flex md:grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 overflow-x-auto no-scrollbar pb-4 md:pb-0 whitespace-nowrap md:whitespace-normal -mx-4 px-4 md:mx-0 md:px-0">
              @forelse ($hotItems as $item)
              <a href="{{ url('/products/detail/' . $item->slug) }}" class="w-[150px] sm:w-[190px] md:w-full bg-white rounded-xl overflow-hidden hover:shadow-md transition-shadow group cursor-pointer block shrink-0 flex flex-col justify-between border border-gray-100">
                <div>
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


        {{-- ==================== 5. BANNER IKLAN TUNGGAL ==================== --}}
        @elseif($block->type === 'single_banner' && !empty($block->content['image']))
        @php
            $singleTargetUrl = !empty($block->content['link']) ? $block->content['link'] : '#';
            $isSingleExternal = str_starts_with($singleTargetUrl, 'http://') || str_starts_with($singleTargetUrl, 'https://');
        @endphp
        <section class="container mx-auto px-4 pb-10 md:pb-14 md:pt-10">
          <a href="{{ $singleTargetUrl }}" 
             @if($isSingleExternal) target="_blank" rel="noopener noreferrer" @endif
             class="block rounded-xl overflow-hidden aspect-[2.8/1] w-full bg-white flex items-center justify-center shadow-sm border border-gray-100 hover:opacity-95 transition-opacity"> 
            <img 
              src="{{ asset('images/banners/webp/' . $block->content['image']) }}" 
              alt="{{ $block->content['alt'] ?? 'Banner Promosi Tangga Mas Scaffolding' }}" 
              class="w-full h-full object-contain"
              onerror="this.src='{{ asset('images/logotm.png') }}'">
          </a>
        </section>


        {{-- ==================== 6. KENAPA PILIH TANGGA MAS (USP) ==================== --}}
        @elseif($block->type === 'usp_text')
        <section id="kenapa" class="container mx-auto px-4 pb-16 md:pb-20 text-center max-w-3xl">
          <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-3 md:mb-4">{{ $block->judul ?? 'Kenapa Pilih Tangga Mas' }}</h2>
          <p class="text-xs md:text-sm text-gray-500 leading-relaxed md:leading-xl">
            {{ $block->content['deskripsi'] ?? 'Sebagai produsen perancah terbesar nomor satu di Jawa Timur, PT Tangga Mas Jaya Makmur memproduksi produk berkualitas standar proyek konstruksi besar dengan sertifikasi resmi dan harga yang sangat bersaing.' }}
          </p>
        </section>
        @endif

    @endforeach

@endif

@endsection