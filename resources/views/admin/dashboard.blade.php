@extends('layouts.admin')

@section('title', 'Admin Dashboard | Tangga Mas')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        
        <!-- HEADER DASHBOARD -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali! Berikut adalah ringkasan operasional produk dan penjualan Tangga Mas.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <!-- TOMBOL UPDATE TARIF ONGKIR -->
                <a href="{{ route('admin.shipping.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-truck-ramp-box"></i> Tarif Ongkir
                </a>
                <a href="{{ route('produk.create') }}" class="bg-brand-green hover:bg-brand-green-dark text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Produk Baru
                </a>
                <a href="{{ route('artikel.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-nib"></i> Tulis Artikel
                </a>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KOMPONEN CUPLIKAN OPERASIONAL PESANAN (STYLE SHOPEE SELLER CENTRE) -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 md:p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base md:text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-[#1BBC9A]"></i> Penting Hari Ini
                    </h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">Aktivitas pesanan yang membutuhkan tindakan admin segera.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand-green hover:underline flex items-center gap-1">
                    Semua Pesanan <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <!-- Grid 5 Tahapan Operasional Pesanan -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                
                <!-- 1. PERLU VERIFIKASI -->
                <a href="{{ route('admin.orders.index', ['status' => 'verifikasi']) }}" 
                   class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-4 rounded-xl text-center transition-all group block">
                    <div class="text-2xl sm:text-3xl font-extrabold text-blue-600 group-hover:scale-105 transition-transform">
                        {{ $countVerifikasi ?? 0 }}
                    </div>
                    <div class="text-xs font-semibold text-gray-700 mt-1 flex items-center justify-center gap-1">
                        <span>Perlu Verifikasi</span>
                        @if(($countVerifikasi ?? 0) > 0)
                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                        @endif
                    </div>
                </a>

                <!-- 2. PERLU DIKIRIM -->
                <a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" 
                   class="bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100 p-4 rounded-xl text-center transition-all group block">
                    <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">
                        {{ $countDiproses ?? 0 }}
                    </div>
                    <div class="text-xs font-semibold text-gray-700 mt-1">
                        Perlu Dikirim
                    </div>
                </a>

                <!-- 3. DIKIRIM -->
                <a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" 
                   class="bg-teal-50/50 hover:bg-teal-50 border border-teal-100 p-4 rounded-xl text-center transition-all group block">
                    <div class="text-2xl sm:text-3xl font-extrabold text-teal-600 group-hover:scale-105 transition-transform">
                        {{ $countDikirim ?? 0 }}
                    </div>
                    <div class="text-xs font-semibold text-gray-700 mt-1">
                        Dikirim
                    </div>
                </a>

                <!-- 4. SELESAI -->
                <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" 
                   class="bg-emerald-50/50 hover:bg-emerald-50 border border-emerald-100 p-4 rounded-xl text-center transition-all group block">
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 group-hover:scale-105 transition-transform">
                        {{ $countSelesai ?? 0 }}
                    </div>
                    <div class="text-xs font-semibold text-gray-700 mt-1">
                        Selesai
                    </div>
                </a>

                <!-- 5. BATAL -->
                <a href="{{ route('admin.orders.index', ['status' => 'batal']) }}" 
                   class="bg-rose-50/50 hover:bg-rose-50 border border-rose-100 p-4 rounded-xl text-center transition-all group block col-span-2 sm:col-span-1">
                    <div class="text-2xl sm:text-3xl font-extrabold text-rose-600 group-hover:scale-105 transition-transform">
                        {{ $countBatal ?? 0 }}
                    </div>
                    <div class="text-xs font-semibold text-gray-700 mt-1">
                        Batal
                    </div>
                </a>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KOMPONEN PERFORMA TOKO (STYLE SHOPEE SELLER CENTRE) -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 md:p-6 mb-8">
            <!-- Header Kartu Performa Toko -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-base md:text-lg font-bold text-gray-900 flex items-center gap-2">
                        Performa Toko
                    </h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        Waktu update terakhir: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB <span class="hidden sm:inline">(Perubahan data dibanding data kemarin)</span>
                    </p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand-green hover:underline flex items-center gap-1 self-start sm:self-auto">
                    Lainnya <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <!-- Frame Grid Metric (5 Kolom) -->
            <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                    
                    <!-- 1. Penjualan -->
                    <div class="pt-3 md:pt-0 md:px-3 first:pt-0 first:px-0">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-medium mb-2">
                            <span>Penjualan</span>
                            <i class="fa-regular fa-circle-question text-[11px] text-gray-300" title="Total omset dari pesanan terkonfirmasi"></i>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            Rp {{ number_format($totalPenjualan ?? 4300000, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-xs flex items-center gap-1 font-semibold text-gray-400">
                            <span>—</span>
                        </div>
                    </div>

                    <!-- 2. Total Pengunjung -->
                    <div class="pt-3 md:pt-0 md:px-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-medium mb-2">
                            <span>Total Pengunjung</span>
                            <i class="fa-regular fa-circle-question text-[11px] text-gray-300" title="Jumlah pengunjung unik ke web toko"></i>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            {{ number_format($totalPengunjung ?? 51, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-xs flex items-center gap-1 font-semibold text-emerald-600">
                            <i class="fa-solid fa-caret-up"></i>
                            <span>{{ number_format($growthPengunjung ?? 6.25, 2, ',', '.') }}%</span>
                        </div>
                    </div>

                    <!-- 3. Jumlah Produk Diklik -->
                    <div class="pt-3 md:pt-0 md:px-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-medium mb-2">
                            <span>Jumlah Produk Diklik</span>
                            <i class="fa-regular fa-circle-question text-[11px] text-gray-300" title="Total interaksi klik katalog produk"></i>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            {{ number_format($totalKlikProduk ?? 73, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-xs flex items-center gap-1 font-semibold text-rose-500">
                            <i class="fa-solid fa-caret-down"></i>
                            <span>{{ number_format($growthKlik ?? 23.96, 2, ',', '.') }}%</span>
                        </div>
                    </div>

                    <!-- 4. Pesanan -->
                    <div class="pt-3 md:pt-0 md:px-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-medium mb-2">
                            <span>Pesanan</span>
                            <i class="fa-regular fa-circle-question text-[11px] text-gray-300" title="Jumlah pesanan masuk"></i>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            {{ number_format($totalPesanan ?? 2, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-xs flex items-center gap-1 font-semibold text-gray-400">
                            <span>—</span>
                        </div>
                    </div>

                    <!-- 5. Tingkat Konversi Pesanan -->
                    <div class="pt-3 md:pt-0 md:px-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-medium mb-2">
                            <span>Tingkat Konversi</span>
                            <i class="fa-regular fa-circle-question text-[11px] text-gray-300" title="Persentase pengunjung yang checkout pesanan"></i>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            {{ number_format($tingkatKonversi ?? 2.74, 2, ',', '.') }}%
                        </div>
                        <div class="mt-2 text-xs flex items-center gap-1 font-semibold text-emerald-600">
                            <i class="fa-solid fa-caret-up"></i>
                            <span>{{ number_format($growthKonversi ?? 2.74, 2, ',', '.') }}%</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- STATS KATALOG UTAMA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Katalog</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProduk }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Frame System</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalFrame }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Ringlock</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRinglock }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tubular System</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTubular }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
            </div>

            <div class="bg-slate-900 p-5 rounded-xl shadow-sm flex items-center justify-between text-white">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Artikel K3</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $totalArtikel }} <span class="text-xs font-normal text-slate-400">Rilis</span></h3>
                </div>
            </div>
        </div>

        <!-- TABEL REKAP PRODUK & BLOG -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h2 class="font-bold text-gray-800 text-xs md:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> Input Produk Terbaru
                    </h2>
                    <a href="{{ route('produk.index') }}" class="text-[11px] text-brand-green font-semibold hover:underline">Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($produkTerbaru as $item)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="p-3 flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden border border-gray-100">
                                        @if($item->gambar)
                                            <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-regular fa-image text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="truncate max-w-[140px]">
                                        <span class="font-semibold text-gray-800 block truncate">{{ $item->nama_produk }}</span>
                                        <span class="text-[10px] text-gray-400 block mt-0.5 capitalize">{{ $item->kategori }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-bold text-brand-price text-right">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center py-8 text-gray-400">Belum ada data produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h2 class="font-bold text-gray-800 text-xs md:text-sm flex items-center gap-2">
                        <i class="fa-solid fa-feather text-gray-400"></i> Update Edukasi K3 & Blog
                    </h2>
                    <a href="{{ route('artikel.index') }}" class="text-[11px] text-brand-green font-semibold hover:underline">Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($artikelTerbaru as $art)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="p-3 flex items-center gap-2.5">
                                    <div class="w-10 h-7 rounded bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden border">
                                        @if($art->gambar)
                                            <img src="{{ asset('images/blog/' . $art->gambar) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-file-lines text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="truncate max-w-[160px]">
                                        <span class="font-semibold text-gray-800 block truncate">{{ $art->judul }}</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $art->kategori }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-right text-gray-500 font-medium">
                                    <i class="fa-regular fa-eye text-[10px]"></i> {{ $art->views }}x
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center py-8 text-gray-400">Belum ada rilis panduan K3.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gradient-to-br from-brand-green-dark to-brand-green text-white p-5 rounded-xl shadow-sm relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-white/10 text-7xl pointer-events-none">
                        <i class="fa-solid fa-helmet-safety"></i>
                    </div>
                    <h3 class="font-bold text-sm mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved"></i> Reminder
                    </h3>
                    <p class="text-[11px] text-white/80 leading-relaxed mb-3">
                        Seluruh produk scaffolding besi & tubular Tangga Mas wajib melewati proses *Quality Control* beban statis sebelum dipublikasikan ke katalog frontend demi keselamatan pekerja proyek.
                    </p>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2.5 border border-white/10">
                        <span class="text-[10px] font-semibold block uppercase tracking-wide text-white/90">Slogan:</span>
                        <span class="text-xs italic font-medium mt-0.5 block text-brand-mint">"Maju dan Berkualitas, Pasti Tangga Mas."</span>
                    </div>
                </div>

                <!-- NAVIGASI OPERASIONAL (DITAMBAHKAN PINTASAN ONGIR ARMADA) -->
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-xs mb-3">Navigasi Operasional</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('admin.shipping.index') }}" class="flex flex-col items-center justify-center p-2.5 bg-amber-50/60 border border-amber-200 rounded-xl hover:border-amber-500 transition-all text-center group">
                            <i class="fa-solid fa-truck-ramp-box text-amber-600 text-sm mb-1"></i>
                            <span class="text-[10px] font-semibold text-amber-900">Ongkir Armada</span>
                        </a>
                        <a href="{{ url('/products') }}" target="_blank" class="flex flex-col items-center justify-center p-2.5 bg-gray-50 border border-gray-200 rounded-xl hover:border-brand-green transition-all text-center group">
                            <i class="fa-solid fa-globe text-gray-400 group-hover:text-brand-green text-sm mb-1"></i>
                            <span class="text-[10px] font-semibold text-gray-700">Lihat Toko</span>
                        </a>
                        <a href="{{ route('artikel.index') }}" class="flex flex-col items-center justify-center p-2.5 bg-gray-50 border border-gray-200 rounded-xl hover:border-brand-green transition-all text-center group">
                            <i class="fa-solid fa-newspaper text-gray-400 group-hover:text-brand-green text-sm mb-1"></i>
                            <span class="text-[10px] font-semibold text-gray-700">List Blog</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection