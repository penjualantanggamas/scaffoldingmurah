@extends('layouts.frontend')

@section('title', 'Pesanan Saya | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-100 min-h-screen py-6 md:py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6">Pesanan Saya</h1>

        <!-- Notifikasi Sukses / Error -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($isGuest) && $isGuest)
            <!-- ========================================================================= -->
            <!-- TAMPILAN KHUSUS UNTUK CUSTOMER BELUM LOGIN (GUEST) -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center shadow-sm my-6">
                <div class="w-16 h-16 bg-emerald-50 text-[#1BBC9A] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-emerald-100">
                    <i class="fa-solid fa-user-lock"></i>
                </div>
                
                <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-1">
                    Login Terlebih Dahulu
                </h2>
                
                <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                    Untuk melihat pesanan Anda bisa login terlebih dahulu menggunakan akun Anda.
                </p>

                <a href="{{ route('customer.login') }}" class="inline-flex items-center justify-center gap-2 bg-[#1BBC9A] hover:bg-[#0C5646] text-white text-xs sm:text-sm font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            </div>
        @else
            <!-- ========================================================================= -->
            <!-- TAMPILAN DAFTAR PESANAN UNTUK CUSTOMER YANG SUDAH LOGIN -->
            <!-- ========================================================================= -->

            <!-- Tab Filter Status Ala Shopee (DITAMBAHKAN TAB "Menunggu Verifikasi") -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4 overflow-hidden">
                <div class="overflow-x-auto">
                    <nav class="flex border-b border-gray-100 min-w-max text-xs sm:text-sm font-semibold text-gray-600">
                        @php
                            $tabs = [
                                'all'        => 'Semua',
                                'unpaid'     => 'Belum Bayar',
                                'verifikasi' => 'Menunggu Verifikasi',
                                'processing' => 'Sedang Dikemas',
                                'shipped'    => 'Dikirim',
                                'completed'  => 'Selesai',
                                'cancelled'  => 'Dibatalkan',
                            ];
                            $currentStatus = request('status', 'all');
                        @endphp

                        @foreach($tabs as $key => $label)
                            <a href="{{ route('customer.orders.index', ['status' => $key]) }}" 
                               class="py-3.5 px-5 sm:px-8 text-center transition-all border-b-2 whitespace-nowrap {{ $currentStatus == $key ? 'text-[#1BBC9A] font-bold border-[#1BBC9A] bg-emerald-50/30' : 'border-transparent hover:text-[#1BBC9A]' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Search Bar Pesanan -->
            <div class="mb-4">
                <form action="{{ route('customer.orders.index') }}" method="GET" class="relative">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kamu bisa cari berdasarkan No. Pesanan..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all shadow-sm">
                </form>
            </div>

            <!-- Card List Pesanan -->
            @forelse($orders as $order)
                @php
                    $orderObj = is_array($order) ? (object) $order : $order;
                    $orderNumber = $orderObj->order_number ?? $orderObj->kode_transaksi ?? $orderObj->no_pesanan ?? $orderObj->id;
                    $orderStatus = $orderObj->status_pesanan ?? $orderObj->status ?? 'pending';
                    $paymentStatus = $orderObj->status_pembayaran ?? 'menunggu_pembayaran';
                    $orderTotal  = $orderObj->total_harga ?? $orderObj->grand_total ?? $orderObj->total ?? 0;
                    $orderItems  = $orderObj->items ?? $orderObj->orderItems ?? [];
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4">
                    
                    <!-- Header Card Order -->
                    <div class="bg-white px-4 sm:px-6 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#1BBC9A] text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Tangga Mas</span>
                            <span class="font-extrabold text-gray-900">No. Order: #{{ $orderNumber }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <!-- Label Status Bayar -->
                            @if($paymentStatus === 'menunggu_pembayaran' || $paymentStatus === 'unpaid')
                                <span class="bg-amber-50 text-amber-700 border border-amber-200 font-bold text-[10px] px-2 py-0.5 rounded uppercase">
                                    Belum Bayar
                                </span>
                            @elseif($paymentStatus === 'menunggu_konfirmasi_admin' || $paymentStatus === 'verifikasi')
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 font-bold text-[10px] px-2 py-0.5 rounded uppercase">
                                    Verifikasi Admin
                                </span>
                            @else
                                <span class="bg-emerald-50 text-[#1BBC9A] border border-emerald-200 font-bold text-[10px] px-2 py-0.5 rounded uppercase">
                                    {{ str_replace('_', ' ', $paymentStatus) }}
                                </span>
                            @endif

                            <span class="text-gray-300">|</span>

                            <span class="uppercase font-extrabold text-gray-700 text-[11px] tracking-wide">
                                {{ str_replace('_', ' ', $orderStatus) }}
                            </span>
                        </div>
                    </div>

                    <!-- Body Card (Loop Item Produk) -->
                    <div class="divide-y divide-gray-100 px-4 sm:px-6">
                        @foreach($orderItems as $item)
                            @php
                                $isArr = is_array($item);
                                $prod = $isArr ? ($item['produk'] ?? $item['product'] ?? null) : ($item->produk ?? $item->product ?? null);
                                $prodArr = is_array($prod);

                                $rawGambar = $isArr 
                                    ? ($item['gambar'] ?? $item['foto'] ?? $item['image'] ?? ($prodArr ? ($prod['foto'] ?? $prod['gambar'] ?? $prod['image'] ?? null) : ($prod->foto ?? $prod->gambar ?? $prod->image ?? null)))
                                    : ($item->gambar ?? $item->foto ?? $item->image ?? ($prodArr ? ($prod['foto'] ?? $prod['gambar'] ?? $prod['image'] ?? null) : ($prod->foto ?? $prod->gambar ?? $prod->image ?? null)));

                                $imgUrl = asset('images/logotm.png');
                                if (!empty($rawGambar)) {
                                    $imgUrl = str_contains($rawGambar, '/') ? asset($rawGambar) : asset('images/' . $rawGambar);
                                }

                                $namaProduk = $isArr 
                                    ? ($item['nama_produk'] ?? $item['product_name'] ?? $item['nama'] ?? ($prodArr ? ($prod['nama_produk'] ?? $prod['nama'] ?? null) : ($prod->nama_produk ?? $prod->nama ?? null)))
                                    : ($item->nama_produk ?? $item->product_name ?? $item->nama ?? ($prodArr ? ($prod['nama_produk'] ?? $prod['nama'] ?? null) : ($prod->nama_produk ?? $prod->nama ?? null)));
                                $namaProduk = $namaProduk ?? 'Produk Scaffolding';

                                $qtyItem = $isArr 
                                    ? ($item['qty'] ?? $item['jumlah'] ?? $item['quantity'] ?? 1)
                                    : ($item->qty ?? $item->jumlah ?? $item->quantity ?? 1);

                                $hargaItem = $isArr 
                                    ? ($item['harga'] ?? $item['harga_satuan'] ?? $item['price'] ?? ($prodArr ? ($prod['harga'] ?? $prod['price'] ?? 0) : ($prod->harga ?? $prod->price ?? 0)))
                                    : ($item->harga ?? $item->harga_satuan ?? $item->price ?? ($prodArr ? ($prod['harga'] ?? $prod['price'] ?? 0) : ($prod->harga ?? $prod->price ?? 0)));
                            @endphp

                            <div class="py-3.5 flex items-center justify-between gap-3 sm:gap-4">
                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                    <img src="{{ $imgUrl }}" 
                                         alt="{{ $namaProduk }}" 
                                         onerror="this.onerror=null; this.src='{{ asset('images/products/' . $rawGambar) }}'; this.onerror=function(){this.src='{{ asset('images/logotm.png') }}';}" 
                                         class="w-14 h-14 sm:w-16 sm:h-16 object-cover rounded-lg border border-gray-200 shrink-0">
                                    
                                    <div class="min-w-0">
                                        <h3 class="text-xs sm:text-sm font-semibold text-gray-900 line-clamp-2 leading-snug">
                                            {{ $namaProduk }}
                                        </h3>
                                        <span class="text-xs text-gray-500 font-medium block mt-1">x{{ $qtyItem }} Pcs</span>
                                    </div>
                                </div>
                                
                                <div class="text-right shrink-0">
                                    <span class="text-xs sm:text-sm font-extrabold text-gray-900">
                                        Rp {{ number_format($hargaItem, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Card Order -->
                    <div class="bg-gray-50/80 px-4 sm:px-6 py-3 border-t border-gray-150 flex items-center justify-between gap-3">
                        <div>
                            <!-- Tombol Lihat Detail Pesanan -->
                            <a href="{{ route('customer.orders.show', $orderObj->id) }}" 
                               class="inline-flex items-center justify-center gap-1.5 bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-extrabold text-xs px-4 py-2 rounded-lg transition-all shadow-md">
                                <i class="fa-solid fa-eye"></i> Lihat Detail Pesanan
                            </a>
                        </div>

                        <div class="text-right flex items-center justify-end gap-2">
                            <span class="text-xs text-gray-500 font-medium">Total Pesanan:</span>
                            <span class="text-sm sm:text-base font-extrabold text-[#1BBC9A]">
                                Rp {{ number_format($orderTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="w-16 h-16 bg-emerald-50 text-[#1BBC9A] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-700">Belum Ada Pesanan</p>
                    <p class="text-xs text-gray-400 mt-1">Belum ada riwayat transaksi pada status ini.</p>
                    <a href="{{ url('/products') }}" class="inline-block mt-4 bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-xs px-5 py-2.5 rounded-lg transition-colors">
                        Mulai Belanja
                    </a>
                </div>
            @endempty

            <!-- Pagination -->
            @if(!empty($orders) && method_exists($orders, 'links'))
                <div class="mt-6 flex justify-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
@endsection