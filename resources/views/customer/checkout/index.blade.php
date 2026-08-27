@extends('layouts.frontend')

@section('title', 'Checkout Pesanan | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-100 min-h-screen py-6 md:py-8">
    <div class="container mx-auto px-4 max-w-5xl">

        <!-- ========================================================================= -->
        <!-- HEADER CHECKOUT DENGAN LOGIKA PEMBATALAN BUY NOW -->
        <!-- ========================================================================= -->
        @php
            $isBuyNow = request()->has('is_buy_now') && request()->get('is_buy_now') == '1';
            $buyNowCartId = isset($cartItems) && count($cartItems) > 0 ? reset($cartItems)['cart_id'] ?? null : null;
            $kodeUnik = $kodeUnik ?? rand(100, 999);
        @endphp

        <div class="flex items-center justify-between mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-150">
            <div>
                <h1 class="text-base sm:text-lg font-bold text-gray-900">Checkout Pesanan</h1>
                <p class="text-xs text-gray-400">Lengkapi data pengiriman dan konfirmasi pesanan Anda.</p>
            </div>
            
            @if($isBuyNow && $buyNowCartId)
                <button type="button" onclick="batalBuyNow('{{ $buyNowCartId }}')" 
                        class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-lg border border-rose-200 transition-all flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i> Batal Pembelian
                </button>
            @else
                <a href="{{ route('cart.index') }}" class="text-xs font-bold text-gray-600 hover:text-[#1BBC9A] transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
                </a>
            @endif
        </div>

        <!-- Form Utama Checkout -->
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <!-- Hidden Input ID Item Keranjang & Kode Unik -->
            @foreach($cartItems as $item)
                <input type="hidden" name="cart_ids[]" value="{{ $item['cart_id'] }}">
            @endforeach
            <input type="hidden" name="kode_unik" value="{{ $kodeUnik }}">

            <!-- ========================================================================= -->
            <!-- 1. KARTU ALAMAT PENGIRIMAN -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden mb-4 relative">
                <div class="h-1.5 w-full bg-[repeating-linear-gradient(135deg,#1BBC9A,#1BBC9A_12px,#ffffff_12px,#ffffff_18px,#0C5646_18px,#0C5646_30px,#ffffff_30px,#ffffff_36px)]"></div>
                
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm sm:text-base font-bold text-[#1BBC9A] flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-lg"></i> Alamat Pengiriman Proyek
                        </h2>
                        <a href="{{ route('customer.profile') }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i> Ubah / Tambah Alamat
                        </a>
                    </div>

                    @if($selectedAddress)
                        <input type="hidden" name="customer_address_id" id="customer_address_id" value="{{ $selectedAddress->id }}">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-gray-700 bg-gray-50/70 p-3.5 rounded-lg border border-gray-200">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-gray-900 text-xs sm:text-sm">{{ $customer->nama_lengkap }}</span>
                                    <span class="text-gray-500 font-semibold">({{ $customer->no_hp }})</span>
                                    @if($selectedAddress->is_utama)
                                        <span class="bg-emerald-50 border border-emerald-300 text-[#1BBC9A] text-[9px] font-bold px-2 py-0.5 rounded uppercase">Utama</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 leading-relaxed font-medium">
                                    <strong class="text-gray-800">[{{ $selectedAddress->label_alamat }}]</strong> 
                                    {{ $selectedAddress->detail_jalan }}, 
                                    Kel. {{ $selectedAddress->kelurahan }}, Kec. {{ $selectedAddress->kecamatan }}, 
                                    <span id="address_kota_name">{{ $selectedAddress->kota }}</span>, {{ $selectedAddress->provinsi }} - {{ $selectedAddress->kode_pos }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3.5 rounded-lg flex items-center justify-between">
                            <span>Belum ada alamat dipilih. Silakan pilih atau tambahkan alamat baru.</span>
                            <a href="{{ route('customer.profile') }}" class="font-bold underline text-amber-900">Atur Alamat</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. KARTU INFORMASI PRODUK DIPESAN -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden mb-4">
                
                <!-- Header Tabel Desktop -->
                <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-3.5 border-b border-gray-100 text-xs font-bold text-gray-500 bg-gray-50">
                    <div class="col-span-6">Produk Dipesan</div>
                    <div class="col-span-2 text-center">Harga Satuan</div>
                    <div class="col-span-2 text-center">Jumlah</div>
                    <div class="col-span-2 text-right">Subtotal Produk</div>
                </div>

                <!-- Brand Header -->
                <div class="px-4 sm:px-6 py-3 bg-white border-b border-gray-100 flex items-center gap-2 text-xs">
                    <span class="bg-[#1BBC9A] text-white text-[10px] font-bold px-1.5 py-0.5 rounded">OFFICIAL</span>
                    <span class="font-bold text-gray-800">Tangga Mas Scaffolding & Formwork</span>
                </div>

                <!-- Loop Item Produk Terpilih -->
                <div class="divide-y divide-gray-100">
                    @foreach($cartItems as $item)
                        <div class="p-4 sm:px-6 grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-center">
                            
                            <!-- Detail Produk (Gambar + Nama + Varian) -->
                            <div class="sm:col-span-6 flex items-center gap-3">
                                <img src="{{ $item['gambar'] ? asset('images/products/' . $item['gambar']) : asset('images/logotm.png') }}" 
                                     alt="{{ $item['nama_produk'] }}" 
                                     onerror="this.src='{{ asset('images/logotm.png') }}'" 
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200 shrink-0">
                                <div>
                                    <h3 class="text-xs sm:text-sm font-semibold text-gray-900 line-clamp-2">{{ $item['nama_produk'] }}</h3>
                                    
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        @if($item['ukuran_varian'])
                                            <span class="text-[10px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200 font-medium">
                                                Varian: {{ $item['ukuran_varian'] }}
                                            </span>
                                        @endif
                                        @if($item['is_preorder'])
                                            <span class="text-[10px] text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-bold">
                                                PO {{ $item['waktu_preorder'] }} Hari
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Harga Satuan -->
                            <div class="sm:col-span-2 text-left sm:text-center text-xs font-semibold text-gray-700">
                                <span class="sm:hidden text-gray-400">Harga: </span>Rp {{ number_format($item['harga'], 0, ',', '.') }}
                            </div>

                            <!-- Jumlah -->
                            <div class="sm:col-span-2 text-left sm:text-center text-xs font-semibold text-gray-700">
                                <span class="sm:hidden text-gray-400">Jumlah: </span>{{ $item['jumlah'] }} Pcs
                            </div>

                            <!-- Subtotal -->
                            <div class="sm:col-span-2 text-left sm:text-right text-xs font-bold text-[#1BBC9A]">
                                <span class="sm:hidden text-gray-400">Subtotal: </span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Catatan & Pengiriman -->
                <div class="bg-gray-50/70 p-4 sm:p-6 border-t border-gray-150 grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    
                    <!-- Input Catatan Pesanan -->
                    <div class="flex flex-col gap-1">
                        <label for="catatan_pembeli" class="text-xs font-bold text-gray-600">Pesan / Catatan Proyek (Opsional):</label>
                        <input type="text" id="catatan_pembeli" name="catatan_pembeli" placeholder="(Opsional) Tinggalkan pesan untuk tim armada gudang..." 
                               class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#1BBC9A] transition-all">
                    </div>

                    <!-- Opsi Pengiriman -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700 block">Opsi Pengiriman Kargo / Armada:</label>
                        <div class="space-y-2" id="shipping_options_container">
                            
                            <!-- OPSI 1: ARMADA GUDANG -->
                            @if($armadaFee !== null)
                                <label class="flex flex-col p-3 bg-white border-2 border-[#1BBC9A] rounded-xl cursor-pointer transition-all space-y-2 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="metode_pengiriman" value="armada_gudang" checked onchange="updateTotalCost({{ $armadaFee }})" class="text-[#1BBC9A] focus:ring-[#1BBC9A]">
                                            <div class="text-xs">
                                                <span class="font-bold text-gray-800 block">Armada Gudang Tangga Mas</span>
                                                <span class="text-gray-500 text-[11px]">Dikalkulasi presisi berdasarkan muatan (kg & volume)</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-extrabold text-[#1BBC9A]" id="armada_fee_text">
                                            Rp {{ number_format($armadaFee, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- DETAIL BREAKDOWN MUATAN & ARMADA TERPILIH -->
                                    @if(isset($fleetDetails) && $fleetDetails['success'])
                                        <div class="bg-emerald-50/70 p-2.5 rounded-lg border border-emerald-100 text-[11px] space-y-1.5 mt-1">
                                            <div class="flex items-center justify-between text-gray-700">
                                                <span class="font-semibold text-gray-600">Total Muatan Pesanan:</span>
                                                <span class="font-mono text-gray-900 font-bold">
                                                    {{ number_format($fleetDetails['total_weight'], 1) }} kg / {{ number_format($fleetDetails['total_volume'], 2) }} m³
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="font-semibold text-gray-600">Alokasi Armada:</span>
                                                <div class="flex flex-wrap gap-1 justify-end">
                                                    @foreach($fleetDetails['fleet'] as $f)
                                                        <span class="bg-white border border-emerald-300 text-[#0C5646] px-2 py-0.5 rounded font-bold text-[10px] shadow-2xs">
                                                            {{ $f['qty'] }}x {{ $f['vehicle_name'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @if(!empty($fleetDetails['is_split']))
                                                <div class="text-[10px] text-amber-700 font-bold flex items-center gap-1 pt-0.5">
                                                    <i class="fa-solid fa-truck-shapes"></i> Multi-Armada (Split Load Aktif)
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </label>
                            @else
                                <div class="flex items-center justify-between p-3 bg-gray-100 border border-gray-200 rounded-xl opacity-60 cursor-not-allowed">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="metode_pengiriman" value="armada_gudang" disabled class="text-gray-400">
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-500 block">Armada Gudang Tangga Mas</span>
                                            <span class="text-[10px] text-rose-500 font-bold">Wilayah ({{ $selectedAddress->kota ?? 'Alamat Anda' }}) belum terjangkau</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] bg-rose-100 text-rose-700 font-bold px-2 py-0.5 rounded">Tidak Tersedia</span>
                                </div>
                            @endif

                            <!-- OPSI 2: EKSPEDISI KARGO -->
                            <label class="flex items-start gap-2.5 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#1BBC9A] transition-all">
                                <input type="radio" name="metode_pengiriman" value="ekspedisi" {{ $armadaFee === null ? 'checked' : '' }} onchange="updateTotalCost(0)" class="mt-0.5 text-[#1BBC9A] focus:ring-[#1BBC9A]">
                                <div class="text-xs">
                                    <span class="font-bold text-gray-800 block">Kirim via Ekspedisi Kargo</span>
                                    <span class="text-gray-500 text-[11px]">Pengiriman luar kota / antar pulau (Ongkir dikonfirmasi terpisah)</span>
                                </div>
                            </label>

                            <!-- OPSI 3: AMBIL SENDIRI DI GUDANG -->
                            <label class="flex items-start gap-2.5 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#1BBC9A] transition-all">
                                <input type="radio" name="metode_pengiriman" value="ambil_sendiri" onchange="updateTotalCost(0)" class="mt-0.5 text-[#1BBC9A] focus:ring-[#1BBC9A]">
                                <div class="text-xs">
                                    <span class="font-bold text-gray-800 block">Ambil Sendiri di Gudang (Rp 0)</span>
                                    <span class="text-gray-500 text-[11px]">Pick-up mandiri di Gudang Bangkingan Gresik</span>
                                </div>
                            </label>

                        </div>
                    </div>

                </div>

                <div class="px-6 py-3.5 bg-emerald-50/50 border-t border-gray-150 text-right text-xs">
                    <span class="text-gray-600 font-medium">Total Pesanan ({{ count($cartItems) }} Produk): </span>
                    <span class="text-sm font-extrabold text-[#1BBC9A] ml-1">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- 3. KARTU METODE PEMBAYARAN & RINCIAN BIAYA DENGAN KODE UNIK -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-150 overflow-hidden mb-6 p-4 sm:p-6 space-y-6">
                
                <!-- Header Metode Pembayaran -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Metode Pembayaran</h3>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="px-4 py-2 border-2 border-[#1BBC9A] text-[#1BBC9A] bg-emerald-50/50 text-xs font-bold rounded-lg cursor-default flex items-center gap-1.5">
                            <i class="fa-solid fa-building-columns"></i> Transfer Bank (Verification)
                        </button>
                    </div>
                </div>

                <!-- Detail Rekening Bank Tujuan -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs space-y-3">
                    <p class="font-bold text-gray-700">Silakan melakukan transfer ke rekening resmi PT Tangga Mas Jaya Makmur:</p>
                    <div class="grid grid-cols-1 sm:grid-cols gap-3">
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <span class="font-bold text-blue-800 block text-xs">BANK BCA</span>
                            <span class="text-sm font-extrabold text-gray-900 block my-0.5">123-456-7890</span>
                            <span class="text-[10px] text-gray-500">a.n PT Tangga Mas Jaya Makmur</span>
                        </div>
                    </div>
                </div>

                <!-- Table Rincian Biaya Akhir -->
                @php
                    $initialOngkir = $armadaFee !== null ? $armadaFee : 0;
                    $initialGrandTotal = $subtotal + $initialOngkir + $kodeUnik;
                @endphp
                <div class="border-t border-gray-150 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Produk</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Pengiriman</span>
                        <span id="displayOngkir" class="font-bold text-[#1BBC9A]">
                            Rp {{ number_format($initialOngkir, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-gray-600">
                        <span>Kode Unik Verifikasi</span>
                        <span id="kodeUnik" class="font-bold text-[#1BBC9A]">
                            +Rp {{ number_format($kodeUnik, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-baseline pt-3 border-t border-dashed border-gray-200">
                        <span class="text-sm font-bold text-gray-800">Total Pembayaran</span>
                        <span id="displayGrandTotal" class="text-xl sm:text-2xl font-extrabold text-[#1BBC9A]">
                            Rp {{ number_format($initialGrandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Tombol Submit Buat Pesanan -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] text-gray-500 leading-tight">
                        Dengan mengklik "Buat Pesanan Sekarang", Anda menyetujui ketentuan pemesanan perancah baja Tangga Mas Scaffolding.
                    </p>
                    <button type="submit" class="w-full sm:w-auto bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-extrabold text-sm py-3.5 px-10 rounded-xl shadow-md transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer shrink-0">
                        <i class="fa-solid fa-paper-plane"></i> Buat Pesanan Sekarang
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<!-- JAVASCRIPT: CALCULATOR ONGKIR + KODE UNIK & BATAL BUY NOW -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let subtotalProduk = {{ $subtotal }};
    let kodeUnikVerifikasi = {{ $kodeUnik }};
    let currentShippingFee = {{ $initialOngkir }};

    function updateTotalCost(shippingFee) {
        currentShippingFee = shippingFee;
        let grandTotal = subtotalProduk + shippingFee + kodeUnikVerifikasi;
        
        let formattedOngkir = new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            minimumFractionDigits: 0 
        }).format(shippingFee).replace('Rp', 'Rp ');

        let formattedGrandTotal = new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            minimumFractionDigits: 0 
        }).format(grandTotal).replace('Rp', 'Rp ');

        document.getElementById('displayOngkir').textContent = formattedOngkir;
        document.getElementById('displayGrandTotal').textContent = formattedGrandTotal;
    }

    function batalBuyNow(cartId) {
        Swal.fire({
            title: 'Batalkan Pembelian?',
            text: 'Produk ini tidak akan disimpan di keranjang belanja Anda.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjutkan Checkout'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Membatalkan...',
                    text: 'Mengembalikan Anda ke halaman produk',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch("{{ route('checkout.cancelBuyNow') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_id: cartId })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (document.referrer && document.referrer !== window.location.href) {
                        window.location.href = document.referrer;
                    } else {
                        window.location.href = "{{ url('/products') }}";
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.close();
                    window.location.href = "{{ url('/products') }}";
                });
            }
        });
    }
</script>
@endsection