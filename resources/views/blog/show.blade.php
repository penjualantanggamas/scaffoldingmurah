@extends('layouts.frontend')

@section('title', $artikel->judul . ' | Tangga Mas Blog')

@section('content')
<div class="bg-white min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        
        <nav class="text-xs text-gray-400 mb-6 flex items-center gap-1">
            <a href="{{ url('/') }}" class="hover:text-brand-green">Home</a> / 
            <a href="{{ route('blog.index') }}" class="hover:text-brand-green">Blog</a> / 
            <span class="text-gray-600 font-medium truncate max-w-[200px] md:max-w-none">{{ $artikel->judul }}</span>
        </nav>

        <div class="mb-6">
            <span class="inline-block bg-brand-green/10 text-brand-green text-xs font-bold px-3 py-1 rounded-md mb-3">
                {{ $artikel->kategori }}
            </span>
            <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 leading-tight md:leading-tight">
                {{ $artikel->judul }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-y border-gray-100 py-3.5 mb-8 text-xs text-gray-500">
            <div class="flex items-center gap-4">
                <span><i class="fa-regular fa-calendar-days mr-1"></i> {{ $artikel->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span><i class="fa-regular fa-eye mr-1"></i> Telah dibaca {{ number_format($artikel->views) }} kali</span>
            </div>
        </div>

        @if($artikel->gambar)
        <div class="rounded-2xl overflow-hidden aspect-[16/9] mb-8 bg-gray-50 border shadow-inner">
            <img src="{{ asset('images/blog/' . $artikel->gambar) }}" class="w-full h-full object-cover" alt="{{ $artikel->judul }}">
        </div>
        @endif

        <!-- Seksional Konten Utama: Menggunakan kelas pemeta artikel kustom -->
        <div class="content-artikel text-gray-700 leading-relaxed border-b border-gray-100 pb-8">
            {!! $artikel->konten !!}
        </div>

        <!-- ================= SEKSI FAQ ARTIKEL (DITAMPILKAN JIKA ADA DATA) ================= -->
        @if(!empty($artikel->faqs) && is_array($artikel->faqs) && count($artikel->faqs) > 0)
        <section class="mt-8 pt-6 border-b border-gray-100 pb-10">
            <div class="mb-5">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-[#1BBC9A]"></i> FAQ
                </h3>
                <!-- <p class="text-xs text-gray-500 mt-1">Jawaban singkat atas pertanyaan umum seputar pembahasan di artikel ini.</p> -->
            </div>

            <div class="space-y-3">
                @foreach($artikel->faqs as $index => $faq)
                <details class="group bg-gray-50/80 border border-gray-200/80 rounded-xl overflow-hidden transition-all duration-200 hover:border-emerald-300">
                    <summary class="flex items-center justify-between p-4 cursor-pointer font-bold text-xs md:text-sm text-gray-800 group-open:text-[#1BBC9A] select-none">
                        <span class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-[#1BBC9A] text-xs font-extrabold flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </span>
                            {{ $faq['pertanyaan'] }}
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-open:rotate-180 transition-transform duration-200 ml-2 shrink-0"></i>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-xs md:text-sm text-gray-600 border-t border-gray-200/60 bg-white leading-relaxed">
                        {!! nl2br(e($faq['jawaban'])) !!}
                    </div>
                </details>
                @endforeach
            </div>
        </section>
        @endif

        @if($relatedArticles->count() > 0)
        <section class="mt-12 pt-4">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Artikel Edukasi Pilihan Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach($relatedArticles as $sub)
                <a href="{{ route('blog.show', $sub->slug) }}" class="group block bg-gray-50 rounded-xl border border-transparent p-3 hover:bg-white hover:border-gray-100 hover:shadow-sm transition-all duration-200">
                    <div class="aspect-[16/10] bg-gray-200 rounded-lg overflow-hidden mb-3">
                        @if($sub->gambar)
                            <img src="{{ asset('images/blog/' . $sub->gambar) }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-xl"></i></div>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-800 text-xs md:text-sm leading-tight group-hover:text-brand-green transition-colors line-clamp-2 mb-1">
                        {{ $sub->judul }}
                    </h4>
                    <span class="text-[10px] text-gray-400 block">{{ $sub->created_at->format('d M Y') }}</span>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

<!-- STYLE PENETRAL RESET TAILWIND KHUSUS AREA EDITOR -->
<style>
    .content-artikel h1 {
        font-size: 2.25rem; /* text-4xl equivalent */
        font-weight: 800;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        color: #111827;
        line-height: 1.25;
    }
    .content-artikel h2 {
        font-size: 1.5rem; /* text-2xl equivalent */
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        color: #1f2937;
        line-height: 1.35;
    }
    .content-artikel h3 {
        font-size: 1.25rem; /* text-xl equivalent */
        font-weight: 700;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    .content-artikel p {
        margin-bottom: 1.25rem;
        font-size: 1rem;
        line-height: 1.75;
    }
    .content-artikel strong {
        font-weight: 700;
        color: #111827;
    }
    .content-artikel em {
        font-style: italic;
    }
    .content-artikel u {
        text-decoration: underline;
    }
    .content-artikel ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .content-artikel ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .content-artikel li {
        margin-bottom: 0.25rem;
    }
</style>
@endsection