@extends('layouts.admin')

@section('title', 'Edit Artikel | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <div class="max-w-4xl mx-auto mb-4">
        <a href="{{ route('artikel.index') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tabel Artikel
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-xl p-6 md:p-8 shadow-sm border border-gray-100">
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">Edit Artikel</h1>
        
        <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Artikel / Panduan</label>
                    <input type="text" name="judul" value="{{ $artikel->judul }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Konten</label>
                    <select name="kategori" class="w-full border border-gray-300 rounded-lg p-2.5 bg-white text-sm focus:outline-none focus:border-brand-green" required>
                        <option value="Edukasi K3" {{ $artikel->kategori == 'Edukasi K3' ? 'selected' : '' }}>Edukasi K3</option>
                        <option value="Info Produk" {{ $artikel->kategori == 'Info Produk' ? 'selected' : '' }}>Info Produk</option>
                        <option value="Event & Proyek" {{ $artikel->kategori == 'Event & Proyek' ? 'selected' : '' }}>Event & Proyek</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ringkasan Singkat Konten</label>
                <textarea name="ringkasan" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" required>{{ $artikel->ringkasan }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Konten Artikel Lengkap</label>
                <div class="prose max-w-none">
                    <textarea name="konten" id="editor" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green">{!! $artikel->konten !!}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti Banner Artikel (Kosongkan jika tidak diubah)</label>
                @if($artikel->gambar)
                    <div class="mb-2 flex items-center gap-2 bg-gray-50 p-2 border rounded-lg w-fit">
                        <img src="{{ asset('images/blog/' . $artikel->gambar) }}" class="w-14 h-8 object-cover rounded">
                        <span class="text-xs text-gray-400">Banner aktif</span>
                    </div>
                @endif
                <input type="file" name="gambar" class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm focus:outline-none">
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[#1BBC9A] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-up-from-bracket text-xs"></i> Simpan Perubahan
                </button>
                <a href="{{ route('artikel.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm text-center">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>

<style>
    .ck-editor__editable_inline {
        min-height: 300px;
        border-radius: 0 0 0.5rem 0.5rem !important;
    }
    .ck-toolbar {
        border-radius: 0.5rem 0.5rem 0 0 !important;
        background-color: #f8fafc !important;
    }
</style>
@endsection