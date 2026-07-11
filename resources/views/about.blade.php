@extends('layouts.app')

@section('title', 'About Us | Tangga Mas Scaffolding')

@section('content')
  <!-- ========== PAGE HEADER / BREADCRUMB BANNER ========== -->
  <section class="container mx-auto px-4 pt-8 md:pt-10">
    <div class="rounded-xl bg-gradient-to-br from-brand-green-dark to-brand-green h-40 md:h-56 flex flex-col items-center justify-center text-center px-4">
      <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">Tentang Kami</h1>
    </div>
  </section>

  <!-- ========== COMPANY INTRO ========== -->
  <section class="container mx-auto px-4 py-12 md:py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">

      <!-- Image placeholder -->
      <div class="rounded-xl bg-brand-gray-bg border border-gray-200 aspect-[4/3] flex items-center justify-center order-1 md:order-none">
        <!-- Ganti div ini dengan <img src="{{ asset('images/pabrik.jpg') }}" class="w-full h-full object-cover rounded-xl"> jika ada aset gambar -->
        <i class="fa-regular fa-image text-gray-300 text-6xl"></i>
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

  <!-- ========== VISI & MISI ========== -->
  <section class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center max-w-2xl mx-auto mb-10">
      <span class="inline-block text-xs font-semibold tracking-wide uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-4">Arah Perusahaan</span>
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Visi & Misi</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

      <!-- Visi -->
      <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8">
        <span class="flex items-center justify-center w-11 h-11 rounded-lg bg-brand-green/10 text-brand-green-dark mb-4">
          <i class="fa-solid fa-eye text-lg"></i>
        </span>
        <h3 class="font-bold text-lg text-gray-900 mb-2">Visi</h3>
        <p class="text-gray-500 leading-relaxed text-sm">
          Menjadi produsen scaffolding dan formwork nomor satu di Indonesia yang dipercaya
          atas kualitas, keamanan, dan pelayanan terbaik bagi seluruh mitra konstruksi.
        </p>
      </div>

      <!-- Misi -->
      <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8">
        <span class="flex items-center justify-center w-11 h-11 rounded-lg bg-brand-green/10 text-brand-green-dark mb-4">
          <i class="fa-solid fa-bullseye text-lg"></i>
        </span>
        <h3 class="font-bold text-lg text-gray-900 mb-2">Misi</h3>
        <ul class="text-gray-500 leading-relaxed text-sm space-y-2">
          <li class="flex gap-2"><i class="fa-solid fa-check text-brand-green mt-1 text-xs"></i> Memproduksi scaffolding berkualitas dengan sertifikasi resmi</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-brand-green mt-1 text-xs"></i> Memberikan harga yang kompetitif tanpa mengurangi kualitas</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-brand-green mt-1 text-xs"></i> Menjaga kepuasan dan kepercayaan pelanggan jangka panjang</li>
        </ul>
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

      <!-- Grid Daftar Artikel -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @forelse($artikels as $item)
        <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
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
                <a href="{{ route('blog.show', $item->slug) }}">{{ $item->judul }}</a>
              </h3>
              <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $item->ringkasan }}</p>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-end">
              <a href="{{ route('blog.show', $item->slug) }}" class="text-xs font-bold text-brand-green hover:text-brand-green-dark flex items-center gap-1 transition-all">
                Baca Detail <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
              </a>
            </div>
          </div>
        </article>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
          <i class="fa-solid fa-newspaper text-3xl text-gray-300 mb-2 block"></i>
          <p class="text-xs text-gray-400">Belum ada rilis artikel panduan K3 saat ini.</p>
        </div>
        @endforelse
      </div>

    </div>
  </section>

  <!-- ========== CTA BANNER ========== -->
  <section class="container mx-auto px-4 py-12 md:py-16">
    <div class="rounded-xl bg-gradient-to-br from-brand-green to-brand-green-dark px-6 py-12 md:py-16 text-center">
      <h2 class="text-xl md:text-2xl font-bold text-white mb-3">Siap Memulai Proyek Anda?</h2>
      <p class="text-white/80 text-sm md:text-base mb-6 max-w-xl mx-auto">
        Konsultasikan kebutuhan scaffolding dan formwork proyek Anda bersama tim kami sekarang juga.
      </p>
      <a href="{{ url('/#footer') }}" class="inline-flex items-center gap-2 bg-white text-brand-green-dark font-semibold text-sm px-6 py-3 rounded-full hover:bg-gray-100 transition-colors">
        Hubungi Kami <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
  </section>
@endsection