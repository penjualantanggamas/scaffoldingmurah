@extends('layouts.frontend')

@section('title', $produk->nama_produk . ' | Tangga Mas Scaffolding')

@section('content')
<div class="container mx-auto px-4 py-6 md:py-10 max-w-6xl">
    <!-- Breadcrumb -->
    <nav class="text-xs text-gray-500 mb-5 capitalize">
        <a href="{{ url('/') }}" class="hover:text-brand-green">Home</a> / 
        <a href="{{ url('/products') }}" class="hover:text-brand-green">Products</a> / 
        <a href="{{ url('/products/' . $produk->kategori . '-system') }}" class="hover:text-brand-green">{{ $produk->kategori }} System</a> / 
        <span class="text-gray-800 font-medium">{{ $produk->nama_produk }}</span>
    </nav>

    <!-- Main Card Container -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-0 bg-white rounded-xl border border-gray-150 shadow-sm items-start overflow-hidden">
        
        <!-- AREA GAMBAR UTAMA (Tanpa padding, gambar full ke card) -->
        <div class="md:col-span-5 w-full md:sticky md:top-28">
            <div class="bg-brand-gray-bg aspect-square flex items-center justify-center overflow-hidden relative shadow-inner">
                <img id="main-product-image" src="{{ $produk->gambar ? asset('images/products/' . $produk->gambar) : asset('images/placeholder.png') }}" class="w-full h-full object-cover" alt="{{ $produk->nama_produk }}" onerror="this.src='{{ asset('images/logotm.png') }}'">
                
                <!-- Badge Promo -->
                <div id="promo-badge-container" class="{{ $produk->harga_coret ? '' : 'hidden' }} absolute top-4 left-4">
                    @php
                        $diskonBawaan = $produk->harga_coret ? round((($produk->harga_coret - $produk->harga) / $produk->harga_coret) * 100) : 0;
                    @endphp
                    <span id="promo-badge-text" class="bg-red-500 text-white text-[10px] md:text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                        PROMO {{ $diskonBawaan }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- INFO PRODUK & CONTROL OPSI -->
        <div class="md:col-span-7 flex flex-col justify-between min-h-full p-4 md:p-6 py-4 md:py-6">
            <div>
                <span class="inline-block text-[10px] md:text-xs font-bold tracking-wider uppercase text-brand-green bg-brand-green/10 px-3 py-1 rounded-full mb-3">
                    {{ $produk->kategori }} System
                </span>
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 mb-2 leading-tight">{{ $produk->nama_produk }}</h1>
                <p class="text-xs md:text-sm text-gray-400 mb-4">{{ $produk->spesifikasi ?? 'Standar Keamanan Proyek Nasional K3' }}</p>

                <!-- AREA HARGA -->
                <div class="flex items-baseline gap-3 mb-5 border-b border-gray-150 pb-4">
                    <span id="display-harga" class="text-xl md:text-2xl lg:text-3xl font-extrabold text-brand-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    <span id="display-harga-coret" class="{{ $produk->harga_coret ? '' : 'hidden' }} text-xs md:text-sm text-gray-400 line-through">
                        Rp {{ $produk->harga_coret ? number_format($produk->harga_coret, 0, ',', '.') : '' }}
                    </span>
                </div>

                <!-- OPSI KUSTOMISASI -->
                <div class="space-y-4 mb-5">
                    @if($produk->varians && $produk->varians->count() > 0)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-750 mb-2">Pilih Ukuran Varian:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($produk->varians as $index => $v)
                            <label class="cursor-pointer">
                                <input type="radio" name="variant_selector" value="{{ $v->id }}" {{ $index === 0 ? 'checked' : '' }} class="peer sr-only"
                                    data-harga="{{ (int)$v->harga }}"
                                    data-hargacoret="{{ $v->harga_coret ? (int)$v->harga_coret : '' }}"
                                    data-gambar="{{ $v->gambar ? asset('images/products/' . $v->gambar) : asset('images/logotm.png') }}">
                                <span class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium block bg-white text-gray-700 peer-checked:border-brand-green peer-checked:text-brand-green peer-checked:ring-1 peer-checked:ring-brand-green transition-all hover:bg-gray-50">
                                    {{ $v->ukuran }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @elseif($produk->ukuran)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-750 mb-2"><i class="fa-solid fa-ruler-combined mr-1.5 text-gray-400"></i> Ukuran:</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(explode(',', $produk->ukuran) as $size)
                            <button type="button" class="border border-brand-green text-brand-green bg-brand-green/5 rounded-lg px-3 py-1.5 text-xs font-medium focus:outline-none">
                                {{ trim($size) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($produk->warna)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-750 mb-2"><i class="fa-solid fa-palette mr-1.5 text-gray-400"></i> Pilihan Lapisan:</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(explode(',', $produk->warna) as $color)
                            <button type="button" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium bg-white text-gray-600 focus:outline-none">
                                {{ trim($color) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-6 border-t border-gray-100 pt-4">
                    <h3 class="text-xs font-bold text-gray-800 mb-1.5">Deskripsi Produk:</h3>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        {{ $produk->deskripsi ?? 'PT Tangga Mas Jaya Makmur menjamin semua material perancah diproduksi menggunakan baja standar SNI berkualitas tinggi yang kuat menahan beban berat pada proyek konstruksi Anda.' }}
                    </p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="space-y-3 pt-4 border-t border-gray-150">
                <div class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full">
                    <!-- Pengatur Qty -->
                    <div class="flex items-center border border-gray-300 rounded-xl bg-gray-50 h-10 shrink-0">
                        <button onclick="document.getElementById('prodQuantity').stepDown()" class="px-2.5 text-gray-500 hover:bg-gray-200 h-full rounded-l-xl transition-colors font-bold text-xs">-</button>
                        <input type="number" id="prodQuantity" value="1" min="1" class="w-10 text-center bg-transparent border-none text-xs font-bold focus:outline-none focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button onclick="document.getElementById('prodQuantity').stepUp()" class="px-2.5 text-gray-500 hover:bg-gray-200 h-full rounded-r-xl transition-colors font-bold text-xs">+</button>
                    </div>

                    <!-- Tombol Keranjang -->
                    <button onclick="addToCart({{ $produk->id }})" class="flex-1 min-w-[160px] bg-white border border-brand-green text-brand-green hover:bg-brand-green hover:text-white px-4 h-10 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
                
                <!-- Tombol Beli Langsung -->
                <button class="w-full bg-[#1BBC9A] text-white font-bold text-xs md:text-sm h-11 rounded-xl hover:bg-[#0C5646] transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-bolt"></i> Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- PRODUK TERKAIT -->
    @if($relatedProducts && $relatedProducts->count() > 0)
    <section class="mt-12 md:mt-16">
        <h2 class="text-base md:text-xl font-bold text-gray-900 mb-4 md:mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
            @foreach($relatedProducts as $item)
            <a href="{{ url('/products/detail/' . $item->slug) }}" class="bg-white rounded-xl group border border-gray-150 overflow-hidden hover:shadow-md transition-shadow block">
                <!-- Gambar full tanpa padding -->
                <div class="bg-brand-gray-bg aspect-square flex items-center justify-center overflow-hidden">
                    <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/logotm.png') }}'" alt="{{ $item->nama_produk }}">
                </div>
                <!-- Teks dengan padding -->
                <div class="p-2.5 md:p-3">
                    <h3 class="font-semibold text-gray-800 text-[11px] md:text-xs mb-0.5 group-hover:text-brand-green transition-colors line-clamp-2 min-h-[32px] md:min-h-[40px] leading-tight whitespace-normal">
                        {{ $item->nama_produk }}
                    </h3>
                    <span class="text-brand-price font-bold text-xs md:text-sm block mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function addToCart(produkId) {
        const qtyInput = document.getElementById('prodQuantity');
        const qty = qtyInput ? parseInt(qtyInput.value) : 1;
        const activeVariant = document.querySelector('input[name="variant_selector"]:checked');
        const variantId = activeVariant ? activeVariant.value : null;

        fetch(`/cart/add/${produkId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: qty, variant_id: variantId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const badge = document.getElementById('cartBadgeCount');
                if(badge) badge.innerText = data.cart_count;
                
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#1BBC9A',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Lihat Keranjang',
                    cancelButtonText: 'Lanjut Belanja'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('cart.index') }}";
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const variantRadios = document.querySelectorAll('input[name="variant_selector"]');
        const mainImage = document.getElementById('main-product-image');
        const displayHarga = document.getElementById('display-harga');
        const displayHargaCoretReal = document.getElementById('display-harga-coret');
        const badgeContainer = document.getElementById('promo-badge-container');
        const badgeText = document.getElementById('promo-badge-text');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        variantRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const gambarUrl = this.getAttribute('data-gambar');
                    if (gambarUrl && !gambarUrl.includes('null')) mainImage.setAttribute('src', gambarUrl);

                    const harga = parseInt(this.getAttribute('data-harga'));
                    const hargaCoretAttr = this.getAttribute('data-hargacoret');
                    displayHarga.textContent = formatRupiah(harga);

                    if (hargaCoretAttr) {
                        const hargaCoret = parseInt(hargaCoretAttr);
                        displayHargaCoretReal.textContent = formatRupiah(hargaCoret);
                        displayHargaCoretReal.classList.remove('hidden');

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
    });
</script>
@endsection