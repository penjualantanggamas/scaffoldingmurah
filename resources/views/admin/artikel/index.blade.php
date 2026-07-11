@extends('layouts.admin')

@section('title', 'Manajemen Artikel | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <div class="max-w-5xl mx-auto mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="max-w-5xl mx-auto bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Artikel & Edukasi K3</h1>
                <p class="text-xs text-gray-400 mt-0.5">Kelola konten literasi keselamatan perancah besi dan info rilis Tangga Mas.</p>
            </div>
            <a href="{{ route('artikel.create') }}" class="bg-[#1BBC9A] text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Tulis Artikel Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 w-24">Banner</th>
                        <th class="p-4">Judul Artikel</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Dilihat</th>
                        <th class="p-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                    @forelse($artikels as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-4">
                            @if($item->gambar)
                                <img src="{{ asset('images/blog/' . $item->gambar) }}" class="w-16 h-10 object-cover rounded-lg border">
                            @else
                                <div class="w-16 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-[10px]">No Image</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="font-semibold text-gray-900 block leading-tight max-w-xs truncate">{{ $item->judul }}</span>
                            <span class="text-[11px] text-gray-400 block mt-1">Dibuat: {{ $item->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="p-4 text-center font-bold text-gray-600">{{ number_format($item->views) }}x</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('artikel.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100/70 px-3 py-1.5 rounded-lg border border-blue-100 transition-colors text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('artikel.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100/70 px-3 py-1.5 rounded-lg border border-red-100 transition-colors text-xs cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <i class="fa-solid fa-feather-pointed text-3xl mb-2 block"></i>
                            <p class="text-sm">Belum ada artikel edukasi yang dipublikasikan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection