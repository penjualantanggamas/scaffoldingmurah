@extends('layouts.frontend')

@php
    $orderObj = is_array($order) ? (object) $order : $order;
    $orderNumber = $orderObj->kode_transaksi ?? $orderObj->order_number ?? $orderObj->no_pesanan ?? $orderObj->id;
    $orderStatus = $orderObj->status_pesanan ?? $orderObj->status ?? 'pending';
    $statusBayar = $orderObj->status_pembayaran ?? $orderObj->payment_status ?? 'menunggu_pembayaran';
    $orderItems  = $orderObj->items ?? $orderObj->orderItems ?? [];

    $isUnpaid = in_array($statusBayar, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin', 'unpaid', 'menunggu']);
@endphp

@section('title', 'Detail Pesanan #' . $orderNumber . ' | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-100 min-h-screen py-6 md:py-8">
    <div class="container mx-auto px-4 max-w-6xl space-y-5">

        <!-- HEADER NAVIGASI -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <a href="{{ route('customer.orders.index') }}" 
               class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-600 hover:text-[#1BBC9A] transition-colors">
                <i class="fa-solid fa-arrow-left"></i> KEMBALI KE PESANAN SAYA
            </a>
            <div class="flex items-center gap-2 text-xs sm:text-sm bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 shrink-0">
                <span class="text-gray-500 font-medium">NO. PESANAN:</span>
                <span class="font-extrabold text-gray-900">#{{ $orderNumber }}</span>
                <span class="text-gray-300">|</span>
                <span class="font-extrabold text-[#1BBC9A] uppercase">
                    {{ str_replace('_', ' ', $orderStatus) }}
                </span>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- STEPPER BAR (KHUSUS UNTUK PESANAN YANG SUDAH DIBAYAR / SELESAI) -->
        <!-- ========================================================================= -->
        @if(!$isUnpaid)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative">
                    <!-- Step 1: Pesanan Dibuat -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#1BBC9A] border-2 border-[#1BBC9A] flex items-center justify-center text-lg mb-2 shadow-sm">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <span class="font-bold text-xs sm:text-sm text-gray-800">Pesanan Dibuat</span>
                        <span class="text-[11px] text-gray-400 mt-0.5">
                            {{ !empty($orderObj->created_at) ? \Carbon\Carbon::parse($orderObj->created_at)->format('d-m-Y H:i') : '-' }}
                        </span>
                    </div>

                    <!-- Step 2: Pesanan Dibayarkan -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#1BBC9A] border-2 border-[#1BBC9A] flex items-center justify-center text-lg mb-2">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <span class="font-bold text-xs sm:text-sm text-gray-800">Pesanan Dibayar</span>
                        <span class="text-[11px] text-gray-400 mt-0.5">
                            {{ !empty($orderObj->updated_at) ? \Carbon\Carbon::parse($orderObj->updated_at)->format('d-m-Y H:i') : '-' }}
                        </span>
                    </div>

                    <!-- Step 3: Pesanan Dikirimkan -->
                    @php $isShipped = in_array($orderStatus, ['shipped', 'dikirim', 'completed', 'selesai']); @endphp
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full {{ $isShipped ? 'bg-emerald-50 text-[#1BBC9A] border-2 border-[#1BBC9A]' : 'bg-gray-100 text-gray-400 border-2 border-gray-200' }} flex items-center justify-center text-lg mb-2">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <span class="font-bold text-xs sm:text-sm {{ $isShipped ? 'text-gray-800' : 'text-gray-400' }}">Pesanan Dikirim</span>
                    </div>

                    <!-- Step 4: Pesanan Selesai -->
                    @php $isCompleted = in_array($orderStatus, ['completed', 'selesai']); @endphp
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full {{ $isCompleted ? 'bg-emerald-50 text-[#1BBC9A] border-2 border-[#1BBC9A]' : 'bg-gray-100 text-gray-400 border-2 border-gray-200' }} flex items-center justify-center text-lg mb-2">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="font-bold text-xs sm:text-sm {{ $isCompleted ? 'text-gray-800' : 'text-gray-400' }}">Pesanan Selesai</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- GRID UTAMA 2 KOLOM (KIRI: INFORMASI PESANAN | KANAN: INSTRUKSI & BUKTI) -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- ========================================================================= -->
            <!-- KOLOM KIRI (7 SPAN): INFORMASI ALAMAT, PRODUK, & RINCIAN HARGA -->
            <!-- ========================================================================= -->
            <div class="{{ $isUnpaid ? 'lg:col-span-7' : 'lg:col-span-12' }} space-y-4">
                
                <!-- 1. Alamat Pengiriman Proyek -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 sm:px-6 py-3 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-[#1BBC9A]"></i>
                        <h2 class="text-xs sm:text-sm font-bold text-gray-800">Alamat Pengiriman</h2>
                    </div>
                    <div class="p-4 sm:p-5 text-xs sm:text-sm space-y-1">
                        @php
                            $addressObj = is_array($orderObj->address ?? null) ? (object) $orderObj->address : ($orderObj->address ?? null);
                            $custObj    = is_array($orderObj->customer ?? null) ? (object) $orderObj->customer : ($orderObj->customer ?? null);
                            
                            $namaPenerima = $custObj->nama_lengkap ?? $custObj->name ?? 'Customer';
                            $noHp         = $custObj->no_hp ?? '-';
                        @endphp

                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-gray-900">{{ $namaPenerima }}</span>
                            <span class="text-gray-500 font-semibold">({{ $noHp }})</span>
                        </div>

                        @if($addressObj)
                            <p class="text-gray-600 leading-relaxed font-medium">
                                <strong class="text-gray-800">[{{ $addressObj->label_alamat ?? 'Alamat Proyek' }}]</strong> 
                                {{ $addressObj->detail_jalan ?? '' }}, 
                                Kel. {{ $addressObj->kelurahan ?? '' }}, Kec. {{ $addressObj->kecamatan ?? '' }}, 
                                {{ $addressObj->kota ?? '' }}, {{ $addressObj->provinsi ?? '' }} - {{ $addressObj->kode_pos ?? '' }}
                            </p>
                        @else
                            <p class="text-gray-600 leading-relaxed font-medium">
                                {{ $orderObj->alamat_lengkap ?? 'Alamat Pengiriman Proyek' }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- 2. Rincian Produk Dipesan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 sm:px-6 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-xs sm:text-sm font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-[#1BBC9A]"></i> Rincian Produk Dipesan
                        </h2>
                        <span class="bg-[#1BBC9A] text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Tangga Mas</span>
                    </div>

                    <div class="divide-y divide-gray-100 px-4 sm:px-6">
                        @php $calculatedSubtotal = 0; @endphp
                        @foreach($orderItems as $item)
                            @php
                                $isArr = is_array($item);
                                
                                $prod = $isArr 
                                    ? ($item['produk'] ?? $item['product'] ?? null) 
                                    : ($item->produk ?? $item->product ?? null);
                                $prodArr = is_array($prod);

                                $rawGambar = $isArr 
                                    ? ($item['gambar'] ?? $item['foto'] ?? ($prodArr ? ($prod['foto'] ?? $prod['gambar'] ?? null) : ($prod->foto ?? $prod->gambar ?? null)))
                                    : ($item->gambar ?? $item->foto ?? ($prodArr ? ($prod['foto'] ?? $prod['gambar'] ?? null) : ($prod->foto ?? $prod->gambar ?? null)));

                                $imgUrl = $rawGambar ? asset('images/products/' . $rawGambar) : asset('images/logotm.png');

                                $namaProduk = $isArr 
                                    ? ($item['nama_produk'] ?? ($prodArr ? ($prod['nama_produk'] ?? null) : ($prod->nama_produk ?? null)))
                                    : ($item->nama_produk ?? ($prodArr ? ($prod['nama_produk'] ?? null) : ($prod->nama_produk ?? null)));
                                
                                $namaProduk = $namaProduk ?? 'Produk Scaffolding';

                                $ukuranVarian = $isArr ? ($item['ukuran_varian'] ?? null) : ($item->ukuran_varian ?? null);

                                $qtyItem = $isArr 
                                    ? ($item['jumlah'] ?? $item['quantity'] ?? 1)
                                    : ($item->jumlah ?? $item->quantity ?? 1);

                                $hargaItem = $isArr 
                                    ? ($item['harga_satuan'] ?? $item['harga'] ?? 0)
                                    : ($item->harga_satuan ?? $item->harga ?? 0);

                                $subtotalBaris = $hargaItem * $qtyItem;
                                $calculatedSubtotal += $subtotalBaris;
                            @endphp

                            <div class="py-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                    <img src="{{ $imgUrl }}" 
                                         alt="{{ $namaProduk }}" 
                                         onerror="this.src='{{ asset('images/logotm.png') }}'" 
                                         class="w-14 h-14 sm:w-16 sm:h-16 object-cover rounded-lg border border-gray-200 shrink-0">
                                    
                                    <div class="min-w-0">
                                        <h3 class="text-xs sm:text-sm font-semibold text-gray-900 line-clamp-2 leading-snug">
                                            {{ $namaProduk }}
                                        </h3>
                                        @if($ukuranVarian)
                                            <span class="text-[10px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200 font-medium inline-block mt-1">
                                                Varian: {{ $ukuranVarian }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 font-medium block mt-0.5">x{{ $qtyItem }} Pcs</span>
                                    </div>
                                </div>
                                
                                <div class="text-right shrink-0">
                                    <span class="text-xs sm:text-sm font-extrabold text-gray-900">
                                        Rp {{ number_format($subtotalBaris, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Rincian Biaya Pembayaran -->
                @php
                    $subtotalProduk = $orderObj->subtotal_produk ?? ($calculatedSubtotal > 0 ? $calculatedSubtotal : 0);
                    $ongkir = $orderObj->biaya_pengiriman ?? $orderObj->ongkir ?? 0;
                    $kodeUnik = $orderObj->kode_unik ?? 0;
                    $grandTotal = $orderObj->grand_total ?? ($subtotalProduk + $ongkir + $kodeUnik);
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-4 sm:p-5 space-y-2 text-xs sm:text-sm">
                    <h3 class="font-bold text-gray-800 uppercase tracking-wider mb-2">Rincian Pembayaran</h3>
                    
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Produk</span>
                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($subtotalProduk, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Pengiriman</span>
                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($ongkir, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($kodeUnik > 0)
                        <div class="flex justify-between text-amber-700 font-semibold">
                            <span>Kode Unik Verifikasi</span>
                            <span>+Rp {{ number_format($kodeUnik, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-baseline pt-3 border-t border-dashed border-gray-200">
                        <span class="text-xs sm:text-sm font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-lg sm:text-xl font-extrabold text-[#1BBC9A]">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- KOLOM KANAN (5 SPAN): KODE UNIK, COUNTDOWN & UPLOAD BUKTI TRANSFER -->
            <!-- ========================================================================= -->
            @if($isUnpaid)
                <div class="lg:col-span-5 sticky top-20">
                    
                    <!-- KONDISI A: MENUNGGU PEMBAYARAN (DENGAN SALIN NOMINAL & REKENING) -->
                    @if($statusBayar == 'menunggu_pembayaran' || $statusBayar == 'unpaid' || $statusBayar == 'menunggu')
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 sm:p-6 space-y-5">
                            
                            <!-- Header & Countdown Timer 24 Jam -->
                            <div class="text-center space-y-2 border-b border-gray-100 pb-4">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Batas Waktu Pembayaran</span>
                                
                                <div id="countdownBox" class="inline-flex items-center justify-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 font-extrabold text-xs sm:text-sm px-4 py-2 rounded-xl">
                                    <i class="fa-solid fa-clock animate-pulse"></i>
                                    <span id="timerCountdown">23 Jam 59 Menit 59 Detik</span>
                                </div>
                                
                                <p class="text-[11px] text-gray-500 mt-1">Segera selesaikan pembayaran sebelum waktu habis.</p>
                            </div>

                            <!-- Rincian Nominal & Highlight Kode Unik 3 Digit -->
                            <div class="bg-amber-50/60 border border-amber-200 rounded-2xl p-4 text-xs space-y-3">
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Subtotal + Ongkir</span>
                                    <span class="font-semibold text-gray-800">
                                        Rp {{ number_format(($orderObj->subtotal_produk + $orderObj->biaya_pengiriman), 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center text-gray-600">
                                    <span class="flex items-center gap-1 font-medium">
                                        Kode Unik <i class="fa-solid fa-circle-info text-amber-500" title="3 digit angka unik untuk verifikasi pembayaran"></i>
                                    </span>
                                    <span class="font-bold text-amber-800 bg-amber-200/70 px-2 py-0.5 rounded">
                                        +{{ sprintf('%03d', $orderObj->kode_unik ?? 0) }}
                                    </span>
                                </div>

                                <div class="border-t border-amber-200/80 pt-3 text-center space-y-2">
                                    <p class="text-gray-600 font-bold text-[11px]">TRANSFER TEPAT SESUAI NOMINAL DI BAWAH:</p>
                                    
                                    @php
                                        $exactAmount = (int) $orderObj->grand_total;
                                        $formattedTotal = number_format($exactAmount, 0, ',', '.');
                                        $mainNominal = substr($formattedTotal, 0, -3);
                                        $lastThree = substr($formattedTotal, -3);
                                    @endphp

                                    <!-- Box Nominal + Tombol Salin Nominal -->
                                    <div class="flex items-center justify-center gap-2 bg-white p-2 rounded-xl border border-amber-300 shadow-sm">
                                        <div class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                                            Rp {{ $mainNominal }}<span class="bg-amber-300 text-rose-700 px-1.5 py-0.5 rounded-lg border border-amber-400 font-extrabold">{{ $lastThree }}</span>
                                        </div>
                                        
                                        <!-- Tombol Salin Nominal -->
                                        <button type="button" onclick="salinNominal({{ $exactAmount }})" 
                                                class="text-[10px] font-extrabold bg-[#1BBC9A] hover:bg-[#0C5646] text-white px-2.5 py-1.5 rounded-lg shadow-sm transition-all flex items-center gap-1 cursor-pointer shrink-0" title="Salin Nominal Transfer">
                                            <i class="fa-regular fa-copy"></i> Salin
                                        </button>
                                    </div>

                                    <p class="text-[10px] text-rose-600 font-bold">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Tiga digit terakhir (<strong>{{ $lastThree }}</strong>) wajib dimasukkan saat transfer agar uang masuk terverifikasi.
                                    </p>
                                </div>
                            </div>

                            <!-- Rekening Tujuan Transfer Bank BCA -->
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-xs space-y-2">
                                <p class="font-bold text-gray-800 text-[11px]">Transfer ke Rekening Resmi PT Tangga Mas:</p>
                                <div class="p-3 bg-white rounded-lg border border-gray-200 flex items-center justify-between">
                                    <div>
                                        <span class="font-bold text-blue-800 block text-xs">BANK BCA</span>
                                        <span class="text-sm font-extrabold text-gray-900 block my-0.5" id="noRekening">1234567890</span>
                                        <span class="text-[10px] text-gray-500">a.n PT Tangga Mas Jaya Makmur</span>
                                    </div>
                                    <button type="button" onclick="salinRekening()" class="text-[10px] font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-1.5 rounded-lg border transition-all flex items-center gap-1 cursor-pointer">
                                        <i class="fa-regular fa-copy"></i> Salin
                                    </button>
                                </div>
                            </div>

                            <!-- Form Upload Bukti Transfer -->
                            <form action="{{ route('checkout.uploadProof', $orderObj->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 pt-1">
                                @csrf
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1">Unggah Foto / Screenshot Bukti Transfer</label>
                                    <input type="file" name="bukti_transfer" accept="image/*" required
                                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#1BBC9A] file:text-white hover:file:bg-[#0C5646] cursor-pointer bg-gray-50 border border-gray-200 rounded-xl p-1">
                                    @error('bukti_transfer')
                                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-extrabold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-paper-plane"></i> Konfirmasi Pembayaran
                                </button>
                            </form>

                        </div>

                        <!-- SCRIPT JAVASCRIPT COUNTDOWN & SALIN NOMINAL/REKENING -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const expiredAtStr = "{{ !empty($orderObj->expired_at) ? \Carbon\Carbon::parse($orderObj->expired_at)->toIso8601String() : \Carbon\Carbon::parse($orderObj->created_at)->addHours(24)->toIso8601String() }}";
                                const expiredTime = new Date(expiredAtStr).getTime();

                                const timerInterval = setInterval(function () {
                                    const now = new Date().getTime();
                                    const distance = expiredTime - now;

                                    if (distance < 0) {
                                        clearInterval(timerInterval);
                                        document.getElementById("timerCountdown").innerHTML = "WAKTU PEMBAYARAN HABIS";
                                        document.getElementById("countdownBox").className = "inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-600 font-extrabold text-xs px-4 py-2 rounded-xl";
                                        return;
                                    }

                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                    document.getElementById("timerCountdown").innerHTML = 
                                        `${hours} Jam ${minutes} Menit ${seconds} Detik`;
                                }, 1000);
                            });

                            function salinNominal(amount) {
                                navigator.clipboard.writeText(amount.toString()).then(function() {
                                    alert("Nominal transfer berhasil disalin: Rp " + amount.toLocaleString('id-ID'));
                                }).catch(function(err) {
                                    console.error('Gagal menyalin: ', err);
                                });
                            }

                            function salinRekening() {
                                const noRek = document.getElementById("noRekening").innerText;
                                navigator.clipboard.writeText(noRek).then(function() {
                                    alert("Nomor Rekening BCA berhasil disalin: " + noRek);
                                });
                            }
                        </script>

                    <!-- KONDISI B: SUDAH UPLOAD BUKTI (MENUNGGU VERIFIKASI ADMIN) -->
                    @else
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5 sm:p-6 space-y-4">
                            <div class="text-center space-y-2 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto text-xl shadow-sm">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                </div>
                                <span class="bg-blue-100 text-blue-800 border border-blue-300 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1 mt-1">
                                    Menunggu Verifikasi Admin
                                </span>
                                <h1 class="text-base font-extrabold text-gray-900 pt-1">Tagihan: {{ $orderNumber }}</h1>
                            </div>

                            @if(!empty($orderObj->bukti_transfer))
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-center space-y-1.5">
                                    <p class="text-[11px] text-gray-500 font-medium">Bukti Transfer Terunggah:</p>
                                    <a href="{{ asset('uploads/bukti_transfer/' . $orderObj->bukti_transfer) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('uploads/bukti_transfer/' . $orderObj->bukti_transfer) }}" class="h-32 object-cover rounded-lg border mx-auto shadow-sm hover:opacity-90 transition-opacity">
                                    </a>
                                    <p class="text-[9px] text-gray-400">Klik gambar untuk memperbesar.</p>
                                </div>
                            @endif

                            <form action="{{ route('checkout.uploadProof', $orderObj->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 pt-2">
                                @csrf
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1">Ganti File Bukti Transfer</label>
                                    <input type="file" name="bukti_transfer" accept="image/*" required
                                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#1BBC9A] file:text-white hover:file:bg-[#0C5646] cursor-pointer bg-gray-50 border border-gray-200 rounded-xl p-1">
                                </div>

                                <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-extrabold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-arrows-rotate"></i> Perbarui Bukti Transfer
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endif

        </div>

    </div>
</div>
@endsection