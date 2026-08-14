@extends('layouts.admin')

@section('title', 'Dekorasi Tampilan Toko | Tangga Mas Admin')

@section('content')
<div class="bg-gray-50 min-h-screen pb-16" x-data="{ activeTab: '{{ session('active_tab', 'home') }}' }">
    <!-- Header Top Bar -->
    <div class="bg-white border-b border-gray-200 py-4 px-6 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-paintbrush text-[#1BBC9A]"></i> Dekorasi Tampilan Toko
                </h1>
                <p class="text-xs text-gray-500 mt-0.5">Kelola tata letak halaman Beranda, banner Katalog, & banner Halaman Kategori.</p>
            </div>
            
            <a href="{{ url('/') }}" target="_blank" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all w-fit shadow-sm">
                <i class="fa-solid fa-eye text-[#1BBC9A]"></i> Pratinjau Live
            </a>
        </div>

        <!-- TAB NAVIGATION BAR -->
        <div class="max-w-7xl mx-auto flex gap-2 mt-4 border-b border-gray-200 overflow-x-auto">
            <button @click="activeTab = 'home'" 
                    :class="activeTab === 'home' ? 'border-[#1BBC9A] text-[#1BBC9A] font-bold bg-emerald-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 transition-all flex items-center gap-2 rounded-t-xl cursor-pointer whitespace-nowrap">
                <i class="fa-solid fa-house"></i>
                <span>Dekorasi Beranda (Home)</span>
            </button>

            <button @click="activeTab = 'products'" 
                    :class="activeTab === 'products' ? 'border-[#1BBC9A] text-[#1BBC9A] font-bold bg-emerald-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 transition-all flex items-center gap-2 rounded-t-xl cursor-pointer whitespace-nowrap">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Banner Katalog Produk (/products)</span>
            </button>

            <!-- TAB BARU: BANNER HALAMAN KATEGORI -->
            <button @click="activeTab = 'categories'" 
                    :class="activeTab === 'categories' ? 'border-[#1BBC9A] text-[#1BBC9A] font-bold bg-emerald-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 transition-all flex items-center gap-2 rounded-t-xl cursor-pointer whitespace-nowrap">
                <i class="fa-solid fa-layer-group"></i>
                <span>Banner Halaman Kategori</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pt-6">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-xl flex items-center gap-2 shadow-sm mb-6">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-xl flex flex-col gap-1 shadow-sm mb-6">
                <div class="font-bold flex items-center gap-1.5 text-rose-700 text-sm">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal Menyimpan Banner:
                </div>
                <ul class="list-disc list-inside pl-1 text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- TAB 1: DEKORASI BERANDA (DRAG & DROP WIDGET BUILDER) -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'home'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- KOLOM KIRI: LIST BLOK DEKORASI BERANDA -->
            <div class="lg:col-span-8 space-y-4" id="decoration-blocks-container">
                
                <!-- Notifikasi Drag & Drop -->
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3.5 rounded-xl font-medium flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-up-down-left-right text-amber-600 text-sm shrink-0"></i>
                        Tarik ikon <strong>Grip (⋮⋮)</strong> untuk menyusun urutan tampilan halaman Beranda.
                    </span>
                    <span id="reorder-status" class="hidden text-[10px] bg-amber-200/80 text-amber-900 px-2 py-0.5 rounded font-bold">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan urutan...
                    </span>
                </div>

                @foreach($blocks as $index => $block)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 border-l-4 {{ $block->is_active ? 'border-l-[#1BBC9A]' : 'border-l-gray-300' }}" id="block-card-{{ $block->id }}" data-id="{{ $block->id }}">
                    
                    <!-- Card Header -->
                    <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="drag-handle cursor-grab active:cursor-grabbing p-1.5 text-gray-400 hover:text-[#1BBC9A] hover:bg-emerald-50 rounded-lg transition-colors" title="Klik & Geser untuk mengubah posisi">
                                <i class="fa-solid fa-grip-vertical text-base"></i>
                            </div>

                            <span class="block-index-badge w-6 h-6 rounded-full bg-gray-200 text-gray-700 text-[11px] font-bold flex items-center justify-center font-mono">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">{{ $block->judul }}</span>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Tipe: {{ str_replace('_', ' ', $block->type) }}</span>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.dekorasi.toggleActive', $block->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-colors cursor-pointer border {{ $block->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                                    <i class="fa-solid {{ $block->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                    {{ $block->is_active ? 'Tampil' : 'Sembunyi' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.dekorasi.destroy', $block->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus blok dekorasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 transition-colors text-xs cursor-pointer" title="Hapus Blok">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 md:p-5">
                        
                        {{-- 1. BLOK BANNER SLIDER --}}
                        @if($block->type === 'banner_slider')
                            <form action="{{ route('admin.dekorasi.updateBlock', $block->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="space-y-3">
                                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Daftar Slide Banner & Edit Link</h4>

                                    @if(is_array($block->content) && count($block->content) > 0)
                                        <div class="space-y-4">
                                            @foreach($block->content as $slide)
                                                @php $slideId = $slide['id'] ?? uniqid(); @endphp
                                                <div class="p-4 bg-gray-200/70 rounded-2xl border border-gray-300/80 space-y-3 shadow-sm">
                                                    <div class="w-full aspect-[2.8/1] sm:aspect-[3.2/1] bg-white rounded-xl border border-gray-300 overflow-hidden flex items-center justify-center shadow-inner">
                                                        <img src="{{ asset('images/banners/webp/' . $slide['image']) }}" class="w-full h-full object-cover" alt="{{ $slide['alt'] ?? 'Banner Preview' }}" onerror="this.src='{{ asset('images/logotm.png') }}'">
                                                    </div>

                                                    <div class="flex flex-col sm:flex-row items-end gap-3">
                                                        <div class="w-full sm:flex-1">
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Url/Link</label>
                                                            <input type="text" name="slides[{{ $slideId }}][link]" value="{{ $slide['link'] ?? '#' }}" placeholder="Contoh: /products/frame-system" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-gray-300/50 text-gray-800 focus:outline-none focus:bg-white focus:border-[#1BBC9A]">
                                                        </div>

                                                        <div class="w-full sm:flex-1">
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Teks Alt Gambar</label>
                                                            <input type="text" name="slides[{{ $slideId }}][alt]" value="{{ $slide['alt'] ?? '' }}" placeholder="Contoh: Banner Frame System" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-gray-300/50 text-gray-800 focus:outline-none focus:bg-white focus:border-[#1BBC9A]">
                                                        </div>

                                                        <a href="{{ url('/admin/dekorasi/slide/delete/' . $block->id . '/' . $slideId) }}" onclick="return confirm('Hapus slide gambar ini?')" class="p-2 text-gray-700 hover:text-rose-600 rounded-xl transition-all shrink-0 flex items-center justify-center" title="Hapus Slide">
                                                            <i class="fa-regular fa-trash-can text-xl"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-6 text-center text-gray-400 border border-dashed rounded-xl text-xs">
                                            Belum ada slide banner yang diunggah.
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-emerald-50/60 p-3.5 rounded-2xl border border-emerald-100 space-y-3 mt-3">
                                    <span class="text-xs font-bold text-gray-700 block">+ Tambah Slide Gambar Baru (Opsional)</span>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Upload File Banner</label>
                                            <input type="file" name="new_banner" accept="image/*" class="w-full text-xs bg-white border border-gray-300 rounded-xl p-1.5">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-600 mb-1">URL / Link Rujukan</label>
                                            <input type="text" name="banner_link" placeholder="Contoh: /products/frame-system" class="w-full border border-gray-300 rounded-xl p-2 text-xs bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Teks Alt Gambar</label>
                                            <input type="text" name="banner_alt" placeholder="Banner Promo Frame" class="w-full border border-gray-300 rounded-xl p-2 text-xs bg-white">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-[#0C5646] transition-colors cursor-pointer shadow-sm">
                                    Simpan Perubahan Slider
                                </button>
                            </form>

                        {{-- 2. BLOK BANNER TUNGGAL --}}
                        @elseif($block->type === 'single_banner')
                            <form action="{{ route('admin.dekorasi.updateBlock', $block->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    <div class="md:col-span-4">
                                        <label class="block text-xs font-bold text-gray-700 mb-2">Pratinjau Banner Saat Ini</label>
                                        <div class="aspect-[2.8/1] rounded-xl border border-gray-200 bg-gray-100 overflow-hidden flex items-center justify-center">
                                            @if(isset($block->content['image']))
                                                <img src="{{ asset('images/banners/webp/' . $block->content['image']) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs text-gray-400">Belum ada gambar</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="md:col-span-8 space-y-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Ganti File Gambar Banner</label>
                                            <input type="file" name="single_banner_image" accept="image/*" class="w-full text-xs bg-white border border-gray-300 rounded-lg p-1.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">URL / Link Tujuan Saat Banner Diklik</label>
                                            <input type="text" name="single_banner_link" value="{{ $block->content['link'] ?? '#' }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#0C5646] transition-colors cursor-pointer">
                                    Simpan Perubahan Banner
                                </button>
                            </form>

                        {{-- 3. BLOK PRODUK (PROMO / HOT) --}}
                        @elseif(in_array($block->type, ['product_promo', 'product_hot']))
                            <form action="{{ route('admin.dekorasi.updateBlock', $block->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Blok Tampilan</label>
                                    <input type="text" name="judul" value="{{ $block->judul }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs mb-3">

                                    <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Produk Spesifik yang Ingin Ditampilkan (Centang Pilihan):</label>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-xl bg-gray-50/50">
                                        @php $selectedIds = $block->content['product_ids'] ?? []; @endphp
                                        @foreach($produks as $p)
                                            <label class="flex items-center gap-2 bg-white p-2 border border-gray-200 rounded-lg cursor-pointer hover:border-[#1BBC9A] transition-colors">
                                                <input type="checkbox" name="product_ids[]" value="{{ $p->id }}" {{ in_array($p->id, $selectedIds) ? 'checked' : '' }} class="rounded text-[#1BBC9A] focus:ring-[#1BBC9A]">
                                                <img src="{{ $p->gambar ? asset('images/products/' . $p->gambar) : asset('images/logotm.png') }}" class="w-7 h-7 object-cover rounded shrink-0">
                                                <div class="truncate">
                                                    <span class="text-xs font-semibold text-gray-800 block truncate">{{ $p->nama_produk }}</span>
                                                    <span class="text-[10px] text-gray-400">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#0C5646] transition-colors cursor-pointer">
                                    Simpan Pilihan Produk
                                </button>
                            </form>

                        {{-- 4. BLOK TEKS USP --}}
                        @elseif($block->type === 'usp_text')
                            <form action="{{ route('admin.dekorasi.updateBlock', $block->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Seksi USP</label>
                                    <input type="text" name="judul" value="{{ $block->judul }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs mb-3">

                                    <label class="block text-xs font-bold text-gray-700 mb-1">Teks Narasi Keunggulan</label>
                                    <textarea name="usp_deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]">{{ $block->content['deskripsi'] ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#0C5646] transition-colors cursor-pointer">
                                    Simpan Teks USP
                                </button>
                            </form>

                        {{-- 5. BLOK KATEGORI PILLS --}}
                        @elseif($block->type === 'category_pills')
                            <form action="{{ route('admin.dekorasi.updateBlock', $block->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Blok (Opsional)</label>
                                    <input type="text" name="judul" value="{{ $block->judul }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs mb-3">

                                    <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Kategori Sistem yang Ingin Ditampilkan:</label>
                                    
                                    @php 
                                        $allCategories = ['frame' => 'Frame System', 'ringlock' => 'Ringlock System', 'tubular' => 'Tubular System', 'kwikstage' => 'Kwikstage System', 'bekisting' => 'Bekisting System'];
                                        $selectedCats = $block->content['categories'] ?? array_keys($allCategories); 
                                    @endphp

                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3 border border-gray-200 rounded-xl bg-gray-50/50">
                                        @foreach($allCategories as $key => $label)
                                            <label class="flex items-center gap-2 bg-white p-2 border border-gray-200 rounded-lg cursor-pointer hover:border-[#1BBC9A] transition-colors">
                                                <input type="checkbox" name="categories[]" value="{{ $key }}" {{ in_array($key, $selectedCats) ? 'checked' : '' }} class="rounded text-[#1BBC9A] focus:ring-[#1BBC9A]">
                                                <span class="text-xs font-semibold text-gray-800">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#0C5646] transition-colors cursor-pointer">
                                    Simpan Pilihan Kategori
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
                @endforeach

            </div>

            <!-- KOLOM KANAN: PANEL TAMBAH KOMPONEN WIDGET BERANDA -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 sticky top-36 space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-[#1BBC9A]"></i> Tambah Widget Beranda
                    </h3>
                    
                    <div class="space-y-2">
                        <form action="{{ route('admin.dekorasi.storeBlock') }}" method="POST">
                            @csrf <input type="hidden" name="type" value="banner_slider">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/50 transition-all cursor-pointer flex items-center gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#1BBC9A] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-images text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 group-hover:text-[#1BBC9A] block">Hero Banner Slider</span>
                                    <span class="text-[10px] text-gray-400 block">Slide banner gambar berputar</span>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('admin.dekorasi.storeBlock') }}" method="POST">
                            @csrf <input type="hidden" name="type" value="single_banner">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/50 transition-all cursor-pointer flex items-center gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-image text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 group-hover:text-[#1BBC9A] block">Banner Iklan Tunggal</span>
                                    <span class="text-[10px] text-gray-400 block">Gambar iklan statis berdiri sendiri</span>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('admin.dekorasi.storeBlock') }}" method="POST">
                            @csrf <input type="hidden" name="type" value="category_pills">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/50 transition-all cursor-pointer flex items-center gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-layer-group text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 group-hover:text-[#1BBC9A] block">Kategori Scaffolding</span>
                                    <span class="text-[10px] text-gray-400 block">Pills navigasi cepat sistem scaffolding</span>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('admin.dekorasi.storeBlock') }}" method="POST">
                            @csrf <input type="hidden" name="type" value="product_promo">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/50 transition-all cursor-pointer flex items-center gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-tags text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 group-hover:text-[#1BBC9A] block">Blok Produk Promo</span>
                                    <span class="text-[10px] text-gray-400 block">Rekomendasi barang diskon</span>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('admin.dekorasi.storeBlock') }}" method="POST">
                            @csrf <input type="hidden" name="type" value="product_hot">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/50 transition-all cursor-pointer flex items-center gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-fire text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 group-hover:text-[#1BBC9A] block">Blok Produk Terlaris</span>
                                    <span class="text-[10px] text-gray-400 block">Daftar produk HOT terlaris</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: EDIT BANNER KATALOG PRODUK (/products) -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'products'" class="max-w-4xl mx-auto space-y-6">
            
            <!-- 1. TOP BANNER HEADER KATALOG -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-4">
                <div class="border-b pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-window-maximize text-teal-600"></i> Top Banner Header Katalog
                        </h3>
                        <p class="text-[11px] text-gray-400">Gambar header utama paling atas pada halaman /products</p>
                    </div>
                </div>

                <form action="{{ route('admin.dekorasi.updateBlock', $catalogHeaderBanner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="p-4 bg-gray-200/70 rounded-2xl border border-gray-300/80 space-y-3 shadow-sm">
                        <div class="w-full aspect-[3.5/1] bg-white rounded-xl border border-gray-300 overflow-hidden flex items-center justify-center shadow-inner relative">
                            @if(isset($catalogHeaderBanner->content['image']))
                                <img src="{{ asset('images/banners/webp/' . $catalogHeaderBanner->content['image']) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-gray-400 italic">Belum ada gambar khusus diunggah (Menggunakan gambar default)</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ganti File Gambar Header Banner</label>
                                <input type="file" name="banner_image" accept="image/*" class="w-full text-xs bg-white border border-gray-300 rounded-xl p-1.5">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Judul Teks Banner</label>
                                <input type="text" name="judul_banner" value="{{ $catalogHeaderBanner->content['judul_banner'] ?? 'Semua Produk' }}" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sub-Judul Pengantar</label>
                                <input type="text" name="subjudul_banner" value="{{ $catalogHeaderBanner->content['subjudul_banner'] ?? 'Katalog lengkap Scaffolding & Bekisting berkualitas industri' }}" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-[#0C5646] transition-colors cursor-pointer shadow-sm">
                        Simpan Top Banner Header
                    </button>
                </form>
            </div>

            <!-- 2. BANNER PENENGAH KATALOG PRODUK -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-4">
                <div class="border-b pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-rectangle-ad text-indigo-600"></i> Banner Penengah Katalog Produk
                        </h3>
                        <p class="text-[11px] text-gray-400">Banner promosi di antara seksi Tubular System dan Kwikstage System</p>
                    </div>
                </div>

                <form action="{{ route('admin.dekorasi.updateBlock', $catalogMiddleBanner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="p-4 bg-gray-200/70 rounded-2xl border border-gray-300/80 space-y-3 shadow-sm">
                        <div class="w-full aspect-[3.5/1] bg-white rounded-xl border border-gray-300 overflow-hidden flex items-center justify-center shadow-inner relative">
                            @if(isset($catalogMiddleBanner->content['image']))
                                <img src="{{ asset('images/banners/webp/' . $catalogMiddleBanner->content['image']) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-gray-400 italic">Belum ada gambar khusus diunggah (Menggunakan gambar default)</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ganti File Gambar Banner</label>
                                <input type="file" name="banner_image" accept="image/*" class="w-full text-xs bg-white border border-gray-300 rounded-xl p-1.5">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Url/Link Rujukan Saat Banner Diklik</label>
                                <input type="text" name="banner_link" value="{{ $catalogMiddleBanner->content['link'] ?? '#' }}" placeholder="Contoh: https://wa.me/..." class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Teks Alt Gambar</label>
                                <input type="text" name="banner_alt" value="{{ $catalogMiddleBanner->content['alt'] ?? '' }}" placeholder="Promosi Scaffolding" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-[#0C5646] transition-colors cursor-pointer shadow-sm">
                        Simpan Banner Penengah
                    </button>
                </form>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: EDIT BANNER HALAMAN KATEGORI PRODUK -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'categories'" class="max-w-4xl mx-auto space-y-6">

            @php
                $catList = [
                    'frame'     => 'Frame System',
                    'ringlock'  => 'Ringlock System',
                    'tubular'   => 'Tubular System',
                    'kwikstage' => 'Kwikstage System',
                    'bekisting' => 'Bekisting System',
                ];
            @endphp

            @foreach($catList as $key => $label)
            @php 
                $bData = $categoryBanners[$key] ?? null; 
                $bContent = $bData?->content ?? [];
                while (is_string($bContent)) {
                    $bContent = json_decode($bContent, true) ?? [];
                }
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-4">
                <div class="border-b pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-image text-[#1BBC9A]"></i> Banner Halaman {{ $label }}
                        </h3>
                        <p class="text-[11px] text-gray-400">Pengaturan banner header khusus pada halaman /products/{{ $key }}-system</p>
                    </div>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-[#1BBC9A] border border-emerald-200">
                        {{ $label }}
                    </span>
                </div>

                @if($bData)
                <form action="{{ route('admin.dekorasi.updateBlock', $bData->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="active_tab" value="categories">

                    <div class="p-4 bg-gray-200/70 rounded-2xl border border-gray-300/80 space-y-3 shadow-sm">
                        <div class="w-full aspect-[3.5/1] bg-white rounded-xl border border-gray-300 overflow-hidden flex items-center justify-center shadow-inner relative">
                            @if(!empty($bContent['image']))
                                <img src="{{ asset('images/banners/webp/' . $bContent['image']) }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4">
                                    <i class="fa-regular fa-image text-gray-300 text-3xl mb-1 block"></i>
                                    <span class="text-xs text-gray-400 italic block">Belum ada gambar khusus diunggah</span>
                                    <span class="text-[10px] text-gray-400">(Tampilan di website akan menggunakan warna gradient bawaan)</span>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ganti File Gambar Banner {{ $label }}</label>
                                <input type="file" name="banner_image" accept="image/*" class="w-full text-xs bg-white border border-gray-300 rounded-xl p-1.5">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Judul Teks Banner</label>
                                <input type="text" name="judul_banner" value="{{ $bContent['judul_banner'] ?? $label }}" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sub-Judul Pengantar</label>
                                <input type="text" name="subjudul_banner" value="{{ $bContent['subjudul_banner'] ?? '' }}" placeholder="Solusi perancah berkualitas..." class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs bg-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="bg-[#1BBC9A] text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-[#0C5646] transition-colors cursor-pointer shadow-sm">
                        Simpan Banner {{ $label }}
                    </button>
                </form>
                @endif
            </div>
            @endforeach

        </div>
    </div>

    <!-- CDN SORTABLEJS DRAG AND DROP -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('decoration-blocks-container');

            if (container) {
                new Sortable(container, {
                    animation: 200,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-40',
                    onEnd: function () {
                        updateIndexBadges();
                        saveReorder();
                    }
                });
            }
        });

        function updateIndexBadges() {
            const badges = document.querySelectorAll('.block-index-badge');
            badges.forEach((badge, idx) => {
                badge.innerText = idx + 1;
            });
        }

        function saveReorder() {
            const statusBadge = document.getElementById('reorder-status');
            if (statusBadge) statusBadge.classList.remove('hidden');

            const cards = document.querySelectorAll('[id^="block-card-"]');
            const orders = Array.from(cards).map(card => card.getAttribute('data-id'));

            fetch("{{ route('admin.dekorasi.reorder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(res => res.json())
            .then(data => {
                if (statusBadge) setTimeout(() => statusBadge.classList.add('hidden'), 1000);
            })
            .catch(err => {
                if (statusBadge) statusBadge.classList.add('hidden');
            });
        }
    </script>
</div>
@endsection