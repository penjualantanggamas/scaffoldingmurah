@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <div class="max-w-4xl mx-auto mb-4">
        <a href="{{ route('artikel.index') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tabel Artikel
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-xl p-6 md:p-8 shadow-sm border border-gray-100">
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">Tulis Artikel Edukasi Baru</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs space-y-2 shadow-sm animate-fade-in">
                <div class="font-bold text-sm flex items-center gap-2 text-red-800">
                    <i class="fa-solid fa-circle-exclamation text-base text-red-600"></i> Gagal Menyimpan Artikel!
                </div>
                <ul class="list-disc list-inside space-y-1 text-red-600 font-medium pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-base text-red-600"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif
        
        <form id="formArtikel" action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Artikel / Panduan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border @error('judul') border-red-500 @else border-gray-300 @enderror rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Contoh: Standar Operasional K3 Pemasangan Perancah Tubular" required>
                    @error('judul')
                        <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Konten <span class="text-red-500">*</span></label>
                    <select name="kategori" class="w-full border @error('kategori') border-red-500 @else border-gray-300 @enderror rounded-lg p-2.5 bg-white text-sm focus:outline-none focus:border-brand-green" required>
                        <option value="Edukasi K3" {{ old('kategori') == 'Edukasi K3' ? 'selected' : '' }}>Edukasi K3</option>
                        <option value="Info Produk" {{ old('kategori') == 'Info Produk' ? 'selected' : '' }}>Info Produk</option>
                        <option value="Event & Proyek" {{ old('kategori') == 'Event & Proyek' ? 'selected' : '' }}>Event & Proyek</option>
                    </select>
                    @error('kategori')
                        <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- ================= SEKSI PENGATURAN PERMALINK & SEO URL ================= -->
            <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-xl space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5 text-brand-green-dark">
                        <i class="fa-solid fa-link"></i> Pengaturan Kustom Permalink URL
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">Atur lokasi sub-folder dan slug URL artikel. Jika slug dikosongkan, sistem membuat otomatis dari judul.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- INPUT PREFIX URL -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Prefix / Sub-Folder URL <span class="text-red-500">*</span></label>
                        <input type="text" name="prefix_url" value="{{ old('prefix_url', 'jualscaffolding') }}" 
                               placeholder="jualscaffolding" required
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-xs font-semibold focus:outline-none focus:border-[#1BBC9A] bg-white">
                        <span class="text-[10px] text-gray-400 mt-1 block">*Contoh: jual-scaffolding, scaffolding-murah, info-scaffolding, tips-k3, berita, edukasi</span>
                    </div>

                    <!-- INPUT CUSTOM SLUG -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Slug SEO Kustom <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="slug" value="{{ old('slug') }}" 
                               placeholder="produsen-scaffolding-terpercaya-jakarta"
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-xs font-semibold focus:outline-none focus:border-[#1BBC9A] bg-white">
                        <span class="text-[10px] text-gray-400 mt-1 block">*Kosongkan jika ingin generate otomatis dari Judul Artikel</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ringkasan Singkat Konten (Maks 500 Karakter) <span class="text-red-500">*</span></label>
                <textarea name="ringkasan" rows="2" class="w-full border @error('ringkasan') border-red-500 @else border-gray-300 @enderror rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Tulis 1-2 kalimat pengantar menarik yang akan muncul di halaman katalog blog depan..." required>{{ old('ringkasan') }}</textarea>
                @error('ringkasan')
                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Konten Artikel Lengkap <span class="text-red-500">*</span></label>
                <div class="prose max-w-none">
                    <textarea name="konten" id="editor" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Tulis pembahasan lengkap artikel Anda di sini...">{!! old('konten') !!}</textarea>
                </div>
                @error('konten')
                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- BANNER UTAMA -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Banner Utama Artikel <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-400">(Format: JPG, PNG, WEBP | Maks: 10MB)</span></label>
                <input type="file" name="gambar" accept="image/*" class="w-full border @error('gambar') border-red-500 @else border-gray-300 @enderror rounded-lg p-2 bg-white text-sm focus:outline-none" required>
                @error('gambar')
                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- ================= SEKSI FAQ ARTIKEL (OPSIONAL) ================= -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-circle-question text-[#1BBC9A]"></i> FAQ Pertanyaan Sering Diajukan
                        </h3>
                        <p class="text-xs text-gray-500">Pertanyaan umum mengenai isi artikel ini (akan tampil di bagian bawah artikel).</p>
                    </div>
                    <button type="button" onclick="addFaqRow()" class="bg-emerald-50 text-[#1BBC9A] border border-emerald-200 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Tambah Pertanyaan
                    </button>
                </div>

                <div id="faqContainer" class="space-y-3">
                    <!-- Baris FAQ akan ditambahkan dinamis melalui JavaScript -->
                </div>
            </div>

            <!-- ================= SEKSI OPTIMASI SEO (META TAGS) ================= -->
            <div class="pt-4 border-t border-gray-200">
                <div class="mb-3">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-[#1BBC9A]"></i> Optimasi SEO (Meta Tags)
                    </h3>
                    <p class="text-xs text-gray-500">Pengaturan meta tag untuk meningkatkan peringkat artikel di mesin pencari Google. Kosongkan jika ingin menggunakan bawaan sistem.</p>
                </div>

                <div class="space-y-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Kosongkan jika ingin menyamakan dengan Judul Artikel">
                            @error('meta_title')
                                <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Author</label>
                            <input type="text" name="meta_author" value="{{ old('meta_author', 'PT. Tangga Mas Jaya Makmur') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Contoh: PT. Tangga Mas Jaya Makmur">
                            @error('meta_author')
                                <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Pisahkan dengan koma. Contoh: Jual Scaffolding, K3 Konstruksi, Tangga Mas">
                        @error('meta_keywords')
                            <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Ringkasan singkat 150-160 karakter untuk hasil pencarian Google...">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[#1BBC9A] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i> Terbitkan Artikel
                </button>
                <a href="{{ route('artikel.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm text-center">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);
                data.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("image.upload") }}', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        return reject(result.error.message || 'Gagal mengunggah gambar.');
                    }
                    resolve({
                        default: result.url
                    });
                })
                .catch(error => {
                    reject('Gagal terhubung ke server.');
                });
            }));
        }

        abort() {}
    }

    function CustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    ClassicEditor
        .create(document.querySelector('#editor'), {
            extraPlugins: [CustomUploadAdapterPlugin],
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'underline', 'link',
                    '|', 'bulletedList', 'numberedList', '|', 'insertTable', 'imageUpload',
                    '|', 'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            }
        })
        .then(editor => {
            const textarea = document.querySelector('#editor');
            editor.model.document.on('change:data', () => { textarea.value = editor.getData(); });
            document.getElementById('formArtikel').addEventListener('submit', () => { textarea.value = editor.getData(); });
        })
        .catch(error => { console.error(error); });

    let faqIndex = 0;
    function addFaqRow(pertanyaan = '', jawaban = '') {
        const container = document.getElementById('faqContainer');
        const rowId = `faq_row_${faqIndex}`;
        
        const html = `
            <div id="${rowId}" class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-2 relative group transition-all">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-question text-emerald-600"></i> FAQ #${faqIndex + 1}
                    </span>
                    <button type="button" onclick="removeFaqRow('${rowId}')" class="text-gray-400 hover:text-red-500 text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
                <div>
                    <input type="text" name="faqs[${faqIndex}][pertanyaan]" value="${pertanyaan}" placeholder="Masukkan pertanyaan FAQ..." class="w-full border border-gray-300 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-[#1BBC9A] bg-white">
                </div>
                <div>
                    <textarea name="faqs[${faqIndex}][jawaban]" rows="2" placeholder="Masukkan jawaban FAQ..." class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white">${jawaban}</textarea>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        faqIndex++;
    }

    function removeFaqRow(rowId) {
        const elem = document.getElementById(rowId);
        if (elem) elem.remove();
    }
</script>

<style>
    .ck-editor__editable_inline { min-height: 300px; border-radius: 0 0 0.5rem 0.5rem !important; }
    .ck-toolbar { border-radius: 0.5rem 0.5rem 0 0 !important; background-color: #f8fafc !important; }
    .ck-content h1 { font-size: 2em !important; font-weight: bold !important; }
    .ck-content h2 { font-size: 1.5em !important; font-weight: bold !important; }
    .ck-content h3 { font-size: 1.25em !important; font-weight: bold !important; }
    .ck-content a { color: #1BBC9A !important; text-decoration: underline !important; }

    /* Styling Tampilan Gambar di Dalam Editor */
    .ck-content img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 0.5rem !important;
        margin: 1rem 0 !important;
        display: block !important;
    }

    .ck-content table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 1rem 0 !important;
    }
    .ck-content th {
        background-color: #f3f4f6 !important;
        font-weight: bold !important;
        padding: 8px 12px !important;
        border: 1px solid #d1d5db !important;
    }
    .ck-content td {
        padding: 8px 12px !important;
        border: 1px solid #d1d5db !important;
    }
</style>
@endsection