@extends('layouts.admin')

@section('title', 'Admin Dashboard | Tangga Mas')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali! Berikut adalah ringkasan operasional produk dan artikel Tangga Mas.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('produk.create') }}" class="bg-brand-green hover:bg-brand-green-dark text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Produk Baru
                </a>
                <a href="{{ route('artikel.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-nib"></i> Tulis Artikel
                </a>
            </div>
        </div>

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
                <!-- <div class="w-10 h-10 rounded-xl bg-brand-green/10 text-brand-green flex items-center justify-center text-md shadow-inner">
                    <i class="fa-solid fa-cubes"></i>
                </div> -->
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Ringlock</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRinglock }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
                <!-- <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-md shadow-inner">
                    <i class="fa-solid fa-circle-nodes"></i>
                </div> -->
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tubular System</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTubular }} <span class="text-xs font-normal text-gray-500">Item</span></h3>
                </div>
                <!-- <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-md shadow-inner">
                    <i class="fa-solid fa-grip-lines-vertical"></i>
                </div> -->
            </div>

            <div class="bg-slate-900 p-5 rounded-xl shadow-sm flex items-center justify-between text-white">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Artikel K3</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $totalArtikel }} <span class="text-xs font-normal text-slate-400">Rilis</span></h3>
                </div>
                <!-- <div class="w-10 h-10 rounded-xl bg-white/10 text-brand-mint flex items-center justify-center text-md shadow-inner">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div> -->
            </div>
        </div>

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

                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-xs mb-3">Navigasi Operasional</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ url('/products') }}" target="_blank" class="flex flex-col items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:border-brand-green transition-all text-center group">
                            <i class="fa-solid fa-globe text-gray-400 group-hover:text-brand-green text-sm mb-1"></i>
                            <span class="text-[11px] font-semibold text-gray-700">Lihat Toko</span>
                        </a>
                        <a href="{{ route('artikel.index') }}" class="flex flex-col items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:border-brand-green transition-all text-center group">
                            <i class="fa-solid fa-newspaper text-gray-400 group-hover:text-brand-green text-sm mb-1"></i>
                            <span class="text-[11px] font-semibold text-gray-700">List Blog</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection