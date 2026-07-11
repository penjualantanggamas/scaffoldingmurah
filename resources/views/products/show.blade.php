@extends('layouts.app')

@section('title', $produk->nama_produk . ' | Tangga Mas Scaffolding')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
    <nav class="text-xs md:text-sm text-gray-500 mb-6 capitalize">
        <a href="{{ url('/') }}" class="hover:text-brand-green">Home</a> / 
        <a href="{{ url('/products') }}" class="hover:text-brand-green">Products</a> / 
        <a href="{{ url('/products/' . $produk->kategori . '-system') }}" class="hover:text-brand-green">{{ $produk->kategori }} System</a> / 
        <span class="text-gray-800 font-medium">{{ $produk->nama_produk }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 bg-white p-4 md:p-8 rounded-xl border border-gray-100 shadow-sm">
        <!-- AREA GAMBAR UTAMA -->
        <div class="space-y-4">
            <div class="bg-brand-gray-bg border border-gray-200 rounded-xl aspect-square flex items-center justify-center overflow-hidden relative">
                <img id="main-product-image" src="{{ $produk->gambar ? asset('images/products/' . $produk->gambar) : asset('images/placeholder.png') }}" class="w-full h-full object-cover" alt="{{ $produk->nama_produk }}">
                
                <!-- Badge Promo -->
                <div id="promo-badge-container" class="{{ $produk->harga_coret ? '' : 'hidden' }} absolute top-4 left-4">
                    @php
                        $diskonBawaan = $produk->harga_coret ? round((($produk->harga_coret - $produk->harga) / $produk->harga_coret) * 100) : 0;
                    @endphp
                    <span id="promo-badge-text" class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-md">
                        PROMO {{ $diskonBawaan }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- INFO PRODUK & CONTROL OPSI -->
        <div class="flex flex-col justify-between">
            <div>
                <span class="inline-block text-xs font-semibold tracking-wide uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-3">
                    {{ $produk->kategori }} System
                </span>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $produk->nama_produk }}</h1>
                <p class="text-sm text-gray-500 mb-4">{{ $produk->spesifikasi ?? 'Standar Keamanan Proyek Nasional' }}</p>

                <!-- AREA HARGA DINAMIS -->
                <div class="flex items-baseline gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span id="display-harga" class="text-2xl md:text-3xl font-extrabold text-brand-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    <span id="display-harga-coret" class="{{ $produk->harga_coret ? '' : 'hidden' }} text-sm md:text-base text-gray-400 line-through">
                        Rp {{ $produk->harga_coret ? number_format($produk->harga_coret, 0, ',', '.') : '' }}
                    </span>
                </div>

                <div class="space-y-4 mb-6">
                    <!-- OPSI MULTI-VARIAN (JIKA ADA DI DB) -->
                    @if($produk->varians->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Pilih Ukuran Varian:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($produk->varians as $index => $v)
                            <label class="cursor-pointer">
                                <input type="radio" name="variant_selector" value="{{ $v->id }}" {{ $index === 0 ? 'checked' : '' }} class="peer sr-only"
                                    data-harga="{{ (int)$v->harga }}"
                                    data-hargacoret="{{ $v->harga_coret ? (int)$v->harga_coret : '' }}"
                                    data-gambar="{{ asset('images/products/' . $v->gambar) }}">
                                <span class="border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium block bg-white text-gray-700 peer-checked:border-brand-green peer-checked:text-brand-green peer-checked:ring-1 peer-checked:ring-brand-green transition-all hover:bg-gray-50">
                                    {{ $v->ukuran }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- OPSI INPUT TUNGGAL BAWAAN (JIKA TIDAK MEMILIKI DATA VARIAN TABLE) -->
                    @elseif($produk->ukuran)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fa-solid fa-ruler-combined mr-1.5 text-gray-400"></i> Ukuran:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $produk->ukuran) as $size)
                            <button type="button" class="border border-brand-green text-brand-green bg-brand-green/5 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none">
                                {{ trim($size) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Pilihan Warna Bawaan -->
                    @if($produk->warna)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fa-solid fa-palette mr-1.5 text-gray-400"></i> Pilihan Warna / Lapisan:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $produk->warna) as $color)
                            <button type="button" class="border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium bg-white text-gray-700 focus:outline-none">
                                {{ trim($color) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi Produk:</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $produk->deskripsi ?? 'Belum ada deskripsi spesifik untuk produk ini. PT Tangga Mas Jaya Makmur menjamin semua material perancah diproduksi menggunakan baja standar SNI berkualitas tinggi yang kuat menahan beban berat pada proyek konstruksi Anda.' }}
                    </p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="space-y-3 pt-4 border-t border-gray-100">
                <div class="flex gap-3">
                    <button class="flex-1 border-2 border-brand-green text-brand-green font-bold text-sm md:text-base py-3 rounded-xl hover:bg-brand-mint transition-colors flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                    </button>
                    <button class="border border-gray-200 text-gray-400 hover:text-red-500 hover:bg-red-50 p-3 rounded-xl transition-colors flex items-center justify-center aspect-square cursor-pointer" aria-label="Tambah ke Favorit">
                        <i class="fa-regular fa-heart text-xl"></i>
                    </button>
                </div>
                <button class="w-full bg-[#1BBC9A] text-white font-bold text-sm md:text-base py-3.5 rounded-xl hover:bg-[#0C5646] transition-colors shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-bolt"></i> Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- PRODUK TERKAIT -->
    @if($relatedProducts->count() > 0)
    <section class="mt-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($relatedProducts as $item)
            <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-lg group border border-gray-100 p-3 hover:shadow-md transition-shadow">
                <div class="bg-brand-gray-bg rounded-md aspect-square flex items-center justify-center mb-3 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-regular fa-image text-gray-300 text-3xl"></i>
                    @endif
                </div>
                <h3 class="font-semibold text-gray-800 text-sm mb-1 group-hover:text-brand-green transition-colors line-clamp-1">{{ $item->nama_produk }}</h3>
                <span class="text-brand-price font-bold text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>

<!-- ENGINE SCRIPT SWITCHER VARIAN DINAMIS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantRadios = document.querySelectorAll('input[name="variant_selector"]');
        
        const mainImage = document.getElementById('main-product-image');
        const displayHarga = document.getElementById('display-harga');
        const displayHargaCoret = document.getElementById('display-display-harga-coret');
        const displayHargaCoretReal = document.getElementById('display-harga-coret');
        const badgeContainer = document.getElementById('promo-badge-container');
        const badgeText = document.getElementById('promo-badge-text');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        variantRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // 1. Ganti Gambar Varian
                    const gambarUrl = this.getAttribute('data-gambar');
                    if (gambarUrl && !gambarUrl.includes('null')) {
                        mainImage.setAttribute('src', gambarUrl);
                    }

                    // 2. Ganti Nilai Finansial
                    const harga = parseInt(this.getAttribute('data-harga'));
                    const hargaCoretAttr = this.getAttribute('data-hargacoret');
                    
                    displayHarga.textContent = formatRupiah(harga);

                    if (hargaCoretAttr) {
                        const hargaCoret = parseInt(hargaCoretAttr);
                        displayHargaCoretReal.textContent = formatRupiah(hargaCoret);
                        displayHargaCoretReal.classList.remove('hidden');

                        // Hitung Ulang Persen Diskon Varian Aktif
                        const diskon = Math.round(((hargaCoret - harga) / hargaCoret) * 100);
                        if (diskon > 0) {
                            badgeText.textContent = 'PROMO ' + diskon + '%';
                            badgeContainer.classList.remove('hidden');
                        } else {
                            badgeContainer.classList.add('hidden');
                        }
                    } else {
                        displayHargaCoretReal.classList.add('hidden');
                        badgeContainer.classList.add('hidden');
                    }
                }
            });
        });
        
        // Memicu trigger klik pertama kali saat halaman dimuat agar sinkron dengan data varian awal
        const checkedRadio = document.querySelector('input[name="variant_selector"]:checked');
        if (checkedRadio) {
            checkedRadio.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection