@extends('layouts.admin')

@section('title', 'Manajemen Produk | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <!-- Navigasi Pintas Kembali -->
    <div class="max-w-6xl mx-auto mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <!-- Header Panel Kontrol -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Produk Tangga Mas</h1>
                <p class="text-xs text-gray-400 mt-0.5">Kelola seluruh data inventaris perancah besi, tubular, dan aksesoris K3.</p>
            </div>
            <a href="{{ route('produk.create') }}" class="bg-[#1BBC9A] text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Produk Baru
            </a>
        </div>

        <!-- Alert Notifikasi Operasional -->
        @if(session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Area Tabel Utama -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 w-20">Gambar</th>
                        <th class="p-4">Nama Produk</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Status Unggulan</th> 
                        <th class="p-4">Harga Katalog</th>
                        <th class="p-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                    @forelse($produks as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- Preview Gambar -->
                        <td class="p-4">
                            @if($item->gambar)
                                <img src="{{ asset('images/products/' . $item->gambar) }}" class="w-12 h-12 object-cover rounded-lg border border-gray-100 shadow-inner">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-[10px] font-medium border border-gray-100 border-dashed">No Image</div>
                            @endif
                        </td>
                        
                        <!-- Info Produk -->
                        <td class="p-4">
                            <span class="font-semibold text-gray-900 block leading-tight">{{ $item->nama_produk }}</span>
                            <span class="text-[11px] text-gray-400 block mt-1 tracking-wide">Slug: {{ $item->slug }}</span>
                        </td>
                        
                        <!-- 1. Kolom Kategori (Sekarang Khusus Kategori Saja) -->
                        <td class="p-4 capitalize">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full text-xs font-semibold border border-slate-200/40">
                                {{ $item->kategori }} System
                            </span>
                        </td>

                        <!-- 2. Kolom Terlaris (Berdiri Sendiri) -->
                        <td class="p-4">
                            <form action="{{ route('produk.toggleTerlaris', $item->id) }}" method="POST">
                                @csrf
                                @if($item->is_terlaris)
                                    <button type="submit" class="bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold px-2.5 py-1.5 rounded-md hover:bg-amber-100 transition-colors flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-star text-amber-500"></i> Terlaris Aktif
                                    </button>
                                @else
                                    <button type="submit" class="bg-gray-50 text-gray-400 border border-gray-200 text-[11px] font-medium px-2.5 py-1.5 rounded-md hover:bg-gray-100 hover:text-gray-600 transition-all flex items-center gap-1 cursor-pointer">
                                        <i class="fa-regular fa-star"></i> Set Terlaris
                                    </button>
                                @endif
                            </form>
                        </td>

                        <!-- Tampilan Cepat Variasi
                        <td class="p-4 text-xs space-y-1 text-gray-600">
                            <div><span class="text-gray-400">Ukuran:</span> <span class="font-medium">{{ $item->ukuran ?? '-' }}</span></div>
                            <div><span class="text-gray-400">Warna:</span> <span class="font-medium">{{ $item->warna ?? '-' }}</span></div>
                        </td> -->
                        
                        <!-- Kolom Harga -->
                        <td class="p-4 font-bold text-brand-price">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                            @if($item->harga_coret)
                                <span class="block text-[11px] text-gray-400 line-through font-normal mt-0.5">Rp {{ number_format($item->harga_coret, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        
                        <!-- Tombol Interaksi Modul Admin -->
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('produk.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100/70 px-3 py-1.5 rounded-lg border border-blue-100 transition-colors flex items-center gap-1 text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen dari database?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100/70 px-3 py-1.5 rounded-lg border border-red-100 transition-colors flex items-center gap-1 text-xs">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-gray-400 bg-gray-50/30 rounded-b-xl border border-dashed border-gray-100">
                            <i class="fa-solid fa-box-open text-3xl text-gray-300 mb-2 block"></i>
                            <p class="text-sm">Belum ada data produk scaffolding yang tersimpan di MySQL.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection