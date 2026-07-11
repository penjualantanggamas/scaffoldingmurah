@extends('layouts.app')

@section('title', 'Pusat Edukasi K3 & Berita | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-green bg-brand-green/10 px-3 py-1 rounded-full">Literasi Perancah</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-3 mb-2">Pusat Edukasi & Info K3</h1>
            <p class="text-gray-500 text-sm leading-relaxed">Temukan panduan keselamatan pemasangan scaffolding, regulasi proyek nasional, dan kabar pembaruan produk terbaru dari PT Tangga Mas Jaya Makmur.</p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ !request('kategori') ? 'bg-slate-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">
                Semua Artikel
            </a>
            <a href="{{ route('blog.index', ['kategori' => 'Edukasi K3']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('kategori') == 'Edukasi K3' ? 'bg-slate-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">
                Edukasi K3
            </a>
            <a href="{{ route('blog.index', ['kategori' => 'Info Produk']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('kategori') == 'Info Produk' ? 'bg-slate-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">
                Info Produk
            </a>
            <a href="{{ route('blog.index', ['kategori' => 'Event & Proyek']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('kategori') == 'Event & Proyek' ? 'bg-slate-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">
                Event & Proyek
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto">
            @forelse($artikels as $item)
            <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
                <div class="aspect-[16/10] bg-gray-100 overflow-hidden relative">
                    @if($item->gambar)
                        <img src="{{ asset('images/blog/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $item->judul }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-4xl"></i></div>
                    @endif
                    <span class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm border text-[10px] font-bold text-slate-700 px-2.5 py-1 rounded-md shadow-sm">
                        {{ $item->kategori }}
                    </span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-[11px] text-gray-400 mb-2">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $item->created_at->format('d M Y') }}</span>
                            <span><i class="fa-regular fa-eye mr-1"></i> {{ $item->views }}x dibaca</span>
                        </div>
                        <h2 class="font-bold text-gray-900 text-base md:text-lg mb-2 group-hover:text-brand-green transition-colors line-clamp-2">
                            <a href="{{ route('blog.show', $item->slug) }}">{{ $item->judul }}</a>
                        </h2>
                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-3 mb-4">{{ $item->ringkasan }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                        <a href="{{ route('blog.show', $item->slug) }}" class="text-xs font-bold text-brand-green hover:text-brand-green-dark flex items-center gap-1 transition-all">
                            Baca Detail <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
                <i class="fa-solid fa-newspaper text-4xl text-gray-300 mb-2 block"></i>
                <p class="text-sm text-gray-400">Belum ada tulisan artikel keselamatan kerja dalam kategori ini.</p>
            </div>
            @endforelse
        </div>

        <div class="max-w-6xl mx-auto mt-10">
            {{ $artikels->links() }}
        </div>

    </div>
</div>
@endsection