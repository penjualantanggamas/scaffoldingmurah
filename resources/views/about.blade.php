@extends('layouts.frontend')

@section('title', 'About Us | Tangga Mas Scaffolding')

@section('content')
  <!-- ========== PAGE HEADER / BREADCRUMB BANNER ========== -->
  <section class="container mx-auto px-4 pt-6 md:pt-10">
    <div class="relative rounded-xl overflow-hidden aspect-[2.4/1] md:aspect-[4/1] w-full flex flex-col items-center justify-center text-center px-4 shadow-sm bg-gray-100">
      <img src="{{ asset('images/banners/webp/bannerbawahblur.webp') }}" class="absolute inset-0 w-full h-full object-cover" alt="Banner Semua Produk Tangga Mas">
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="relative z-10 px-2">
        <h1 class="text-xl md:text-4xl font-bold text-white tracking-wide drop-shadow-sm">Tentang Kami</h1>
      </div>
    </div>
  </section>

  <!-- ========== COMPANY INTRO ========== -->
  <section class="container mx-auto px-4 py-12 md:py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">

      <!-- Image placeholder -->
      <div class="rounded-xl bg-brand-gray-bg border border-gray-200 aspect-[4/3] flex items-center justify-center order-1 md:order-none">
        <img src="{{ asset('images/banners/webp/mianbannerabout.webp') }}" class="w-full h-full object-cover rounded-xl"> 
      </div>

      <!-- Text -->
      <div>
        <span class="inline-block text-xs font-semibold tracking-wide uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-4">Profil Perusahaan</span>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">PT Tangga Mas Jaya Makmur</h2>
        <p class="text-gray-500 leading-relaxed mb-4">
          Tangga Mas Jaya Makmur adalah produsen perancah (scaffolding) dan bekisting formwork terbesar
          di Jawa Timur, melayani kebutuhan konstruksi berskala besar di seluruh Indonesia. Sejak awal
          berdiri, kami berkomitmen menghadirkan produk baja berkualitas tinggi dengan standar keamanan
          proyek konstruksi nasional.
        </p>
        <p class="text-gray-500 leading-relaxed">
          Dengan fasilitas produksi sendiri, tim teknis berpengalaman, dan jaringan distribusi yang luas,
          kami memastikan setiap produk yang sampai ke tangan pelanggan telah melalui proses quality
          control ketat dan siap digunakan untuk proyek skala kecil hingga besar.
        </p>
      </div>
    </div>
  </section>

  <!-- ========== STATISTIK / ANGKA PENCAPAIAN ========== -->
  <section class="bg-brand-gray-bg py-12 md:py-16">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-center">

        <div class="bg-white rounded-lg p-6">
          <p class="text-3xl md:text-4xl font-bold text-brand-green-dark mb-1">10+</p>
          <p class="text-sm text-gray-500">Tahun Pengalaman</p>
        </div>
        <div class="bg-white rounded-lg p-6">
          <p class="text-3xl md:text-4xl font-bold text-brand-green-dark mb-1">500+</p>
          <p class="text-sm text-gray-500">Proyek Terselesaikan</p>
        </div>
        <div class="bg-white rounded-lg p-6">
          <p class="text-3xl md:text-4xl font-bold text-brand-green-dark mb-1">50+</p>
          <p class="text-sm text-gray-500">Varian Produk</p>
        </div>
        <div class="bg-white rounded-lg p-6">
          <p class="text-3xl md:text-4xl font-bold text-brand-green-dark mb-1">1000+</p>
          <p class="text-sm text-gray-500">Klien Terpercaya</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ========== SCAFFOLDING BERSERTIFIKASI ========== -->
  <section class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto text-center">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Scaffolding Bersertifikasi & Teruji</h2>
      <p class="text-gray-500 leading-relaxed text-sm md:text-base mb-6">
        Sebagai produsen scaffolding, kami memastikan setiap produk memiliki standar kualitas yang tinggi sehingga mampu digunakan dalam berbagai kondisi proyek konstruksi.
        Dengan sistem produksi yang terkontrol dan pengawasan kualitas yang ketat, scaffolding yang kami hasilkan memiliki daya tahan yang kuat serta umur penggunaan yang lebih lama.
        Scaffolding dengan Standart yang sangat baik. Telah lolos Uji Beban, Tersertifikasi Japanese Industrial Standards (JIS), Tersertifikasi British Standards (BS)
      </p>
      <div class="flex flex-wrap justify-center gap-4 md:gap-6 text-sm text-gray-600">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-check-circle text-brand-green"></i>
          <span>SNI Certified</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-check-circle text-brand-green"></i>
          <span>ISO 9001 Quality</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-check-circle text-brand-green"></i>
          <span>Uji Load Test</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-check-circle text-brand-green"></i>
          <span>Baja Grade Tinggi</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== SECTION: LITERASI & EDUKASI K3 TERBARU ========== -->
  <section class="bg-white py-12 md:py-16 border-t border-gray-100">
    <div class="container mx-auto px-4">
      
      <!-- Header Konten -->
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
          <span class="inline-block text-xs font-semibold tracking-wide uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-3">Pusat Edukasi</span>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Artikel & Informasi Terbaru</h2>
          <p class="text-gray-500 text-sm mt-1">Ikuti panduan keselamatan kerja perancah dan pembaruan informasi industri konstruksi kami.</p>
        </div>
        <div>
          <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 border border-gray-200 hover:border-brand-green hover:text-brand-green text-gray-700 font-semibold text-xs px-5 py-2.5 rounded-full transition-all shadow-sm bg-white">
            Lihat Semua Artikel <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>
      </div>

      <!-- Grid Daftar Artikel (Seluruh Card Bisa Diklik) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @forelse($artikels as $item)
        <a href="{{ route('blog.show', $item->slug) }}" class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group cursor-pointer block">
          <!-- Thumbnail Gambar -->
          <div class="aspect-[16/10] bg-gray-100 overflow-hidden relative">
            @if($item->gambar)
              <img src="{{ asset('images/blog/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $item->judul }}">
            @else
              <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
            @endif
            <span class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm border text-[10px] font-bold text-gray-600 px-2.5 py-1 rounded-md shadow-sm">
              {{ $item->kategori }}
            </span>
          </div>

          <!-- Deskripsi & Info -->
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 text-[11px] text-gray-400 mb-2">
                <span><i class="fa-regular fa-calendar mr-1"></i> {{ $item->created_at->format('d M Y') }}</span>
                <span><i class="fa-regular fa-eye mr-1"></i> {{ $item->views }}x dibaca</span>
              </div>
              <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-brand-green transition-colors line-clamp-2">
                {{ $item->judul }}
              </h3>
              <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $item->ringkasan }}</p>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-end">
              <span class="text-xs font-bold text-brand-green group-hover:text-brand-green-dark flex items-center gap-1 transition-all">
                Baca Detail <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
              </span>
            </div>
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
          <i class="fa-solid fa-newspaper text-3xl text-gray-300 mb-2 block"></i>
          <p class="text-xs text-gray-400">Belum ada rilis artikel panduan K3 saat ini.</p>
        </div>
        @endforelse
      </div>

    </div>
  </section>

  <!-- ========== KENAPA PILIH KAMI ========== -->
  <section class="bg-brand-gray-bg py-12 md:py-16">
    <div class="container mx-auto px-4">
      <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-block text-xs font-semibold tracking-wide uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-4">Keunggulan</span>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Kenapa Pilih Tangga Mas</h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-lg p-6 text-center">
          <span class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-green/10 text-brand-green-dark mx-auto mb-4">
            <i class="fa-solid fa-industry text-lg"></i>
          </span>
          <h3 class="font-semibold text-gray-900 mb-1 text-sm">Produsen Langsung</h3>
          <p class="text-gray-500 text-xs leading-relaxed">Diproduksi sendiri tanpa perantara, harga lebih bersaing</p>
        </div>

        <div class="bg-white rounded-lg p-6 text-center">
          <span class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-green/10 text-brand-green-dark mx-auto mb-4">
            <i class="fa-solid fa-shield-halved text-lg"></i>
          </span>
          <h3 class="font-semibold text-gray-900 mb-1 text-sm">Bersertifikat</h3>
          <p class="text-gray-500 text-xs leading-relaxed">Sesuai standar keamanan proyek konstruksi nasional</p>
        </div>

        <div class="bg-white rounded-lg p-6 text-center">
          <span class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-green/10 text-brand-green-dark mx-auto mb-4">
            <i class="fa-solid fa-truck-fast text-lg"></i>
          </span>
          <h3 class="font-semibold text-gray-900 mb-1 text-sm">Pengiriman Luas</h3>
          <p class="text-gray-500 text-xs leading-relaxed">Distribusi pengiriman ke seluruh wilayah Indonesia</p>
        </div>

        <div class="bg-white rounded-lg p-6 text-center">
          <span class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-green/10 text-brand-green-dark mx-auto mb-4">
            <i class="fa-solid fa-headset text-lg"></i>
          </span>
          <h3 class="font-semibold text-gray-900 mb-1 text-sm">Dukungan Teknis</h3>
          <p class="text-gray-500 text-xs leading-relaxed">Tim berpengalaman siap membantu kebutuhan proyek Anda</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ========== CTA BANNER ========== -->
  <section class="container mx-auto px-4 py-6 md:py-10">
    <a href="https://wa.me/628123651717?text=Halo%20Tangga%20Mas,%20saya%20mau%20konsultasi%20terkait%20kebutuhan%20scaffolding" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="relative block rounded-xl overflow-hidden aspect-[1.8/1] md:aspect-[3.5/1] w-full bg-brand-green shadow-sm border border-gray-150 transition-transform duration-300 hover:scale-[1.003]" 
       title="Hubungi Kami via WhatsApp">
      
      <img 
        src="{{ asset('images/banners/webp/bannerbawahblur1.webp') }}" 
        alt="Background Proyek Tangga Mas Scaffolding" 
        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105"
        onerror="this.src='{{ asset('images/logotm.png') }}'"
      >
      
      <div class="absolute inset-0 bg-black/40"></div>

      <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center p-4 md:p-8">
        <h2 class="text-white font-bold text-lg md:text-3xl tracking-wide leading-tight drop-shadow-md max-w-2xl">
          Siap Memulai Proyek Anda? 
          <br class="block md:hidden"> Hubungi Tangga Mas Scaffolding
        </h2>
        <p class="text-white/90 text-[11px] md:text-sm mt-1.5 md:mt-2 max-w-md drop-shadow-sm font-medium">
          Konsultasikan kebutuhan scaffolding & bekisting sekarang.
        </p>
        
        <span class="inline-flex items-center gap-1.5 bg-white text-gray-900 font-bold text-[10px] md:text-xs px-4 py-2 rounded-full shadow-md mt-3 md:mt-4 group-hover:bg-brand-green group-hover:text-white transition-colors">
          <i class="fa-brands fa-whatsapp text-emerald-650 text-xs md:text-sm"></i>
          Hubungi WhatsApp
        </span>
      </div>

    </a>
  </section>
  
@endsection