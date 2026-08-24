@extends('layouts.admin')

@section('title', 'Tambah Produk Baru | Tangga Mas Admin')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Top Return Bar -->
    <div class="max-w-5xl mx-auto pt-6 px-4">
        <a href="{{ route('produk.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#1BBC9A] flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tabel Produk
        </a>
    </div>

    <!-- Sticky Navigation Tabs (Shopee Style) -->
    <div class="sticky top-0 bg-white border-b border-gray-200 z-40 shadow-sm mt-4 mb-6">
        <div class="max-w-5xl mx-auto px-4">
            <nav class="flex gap-8 text-sm font-medium h-12 items-center" id="form-tabs">
                <a href="#sec-info" class="text-[#1BBC9A] border-b-2 border-[#1BBC9A] py-3 px-1 transition-all">Informasi Produk</a>
                <a href="#sec-deskripsi" class="text-gray-500 hover:text-gray-700 py-3 px-1 transition-all">Deskripsi</a>
                <a href="#sec-penjualan" class="text-gray-500 hover:text-gray-700 py-3 px-1 transition-all">Informasi Penjualan</a>
                <a href="#sec-pengiriman" class="text-gray-500 hover:text-gray-700 py-3 px-1 transition-all">Pengiriman</a>
                <a href="#sec-seo" class="text-gray-500 hover:text-gray-700 py-3 px-1 transition-all">SEO</a>
            </nav>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- SECTION 1: INFORMASI PRODUK -->
            <div id="sec-info" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 scroll-mt-16">
                <h2 class="text-base font-bold text-gray-800 mb-6 border-b border-gray-100 pb-2">Informasi Produk</h2>
                <div class="space-y-5">
                    
                    <!-- FOTO PRODUK MULTIPLE ALA SHOPEE (COVER + GALERI) -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <span class="text-red-500">*</span> Foto Produk (Rasio 1:1, Maks. 9 Foto)
                            </label>
                            <span id="photoCounter" class="text-xs font-bold text-[#1BBC9A] font-mono">(0/9)</span>
                        </div>

                        <!-- GRID UPLOAD ALA SHOPEE -->
                        <div class="grid grid-cols-2 sm:grid-cols-5 md:grid-cols-9 gap-3" id="shopeeImageGrid">
                            
                            <!-- SLOT 1: FOTO COVER UTAMA -->
                            <div class="relative group aspect-square rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center overflow-hidden hover:border-[#1BBC9A] transition-all cursor-pointer" onclick="document.getElementById('coverInput').click()">
                                <img id="coverPreview" src="" class="hidden w-full h-full object-cover">
                                
                                <div id="coverPlaceholder" class="flex flex-col items-center text-center p-1">
                                    <i class="fa-regular fa-image text-gray-400 text-xl mb-1"></i>
                                    <span class="text-[10px] text-gray-500 font-medium">+ Foto Cover</span>
                                </div>

                                <div id="coverLabel" class="absolute bottom-0 inset-x-0 bg-red-500 text-white text-[9px] font-bold py-0.5 text-center uppercase tracking-wider hidden">
                                    * Cover
                                </div>

                                <input type="file" id="coverInput" name="gambar" accept="image/*" class="hidden" required onchange="previewCover(this)">
                            </div>

                            <!-- CONTAINER PREVIEW GALERI BARU (DYNAMIC) -->
                            <div id="galleryPreviews" class="contents"></div>

                            <!-- TOMBOL TAMBAH FOTO GALERI -->
                            <div id="addMoreBox" class="aspect-square rounded-xl border-2 border-dashed border-amber-300 bg-amber-50/50 hover:bg-amber-100/50 flex flex-col items-center justify-center cursor-pointer transition-all p-1 text-center" onclick="document.getElementById('galleryInput').click()">
                                <i class="fa-solid fa-square-plus text-amber-500 text-xl mb-1"></i>
                                <span class="text-[10px] font-bold text-amber-800">Tambah Foto</span>
                            </div>

                            <input type="file" id="galleryInput" name="galeri[]" accept="image/*" multiple class="hidden" onchange="handleGalleryUpload(this)">
                        </div>

                        <p class="text-[11px] text-gray-400 mt-2">
                            💡 <strong>Tips Foto:</strong> Slot pertama otomatis menjadi <strong>Cover Utama</strong> display katalog. Foto tambahan bisa untuk detail sisi, tampak las, atau spesifikasi teknis K3.
                        </p>
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider"><span class="text-red-500">*</span> Nama Produk</label>
                            <span id="char-count" class="text-[10px] text-gray-400 font-mono">0/255</span>
                        </div>
                        <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" required placeholder="Contoh: MAIN FRAME SET T190 BESI HITAM SNI PIPE 1.2 INCH">
                    </div>

                    <!-- Kategori & Spesifikasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1"><span class="text-red-500">*</span> Kategori Halaman</label>
                            <select name="kategori" class="w-full bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.6rem_auto] bg-[position:right_0.75rem_center] bg-no-repeat">
                                <option value="frame" {{ old('kategori') == 'frame' ? 'selected' : '' }}>Frame System</option>
                                <option value="ringlock" {{ old('kategori') == 'ringlock' ? 'selected' : '' }}>Ringlock System</option>
                                <option value="tubular" {{ old('kategori') == 'tubular' ? 'selected' : '' }}>Tubular System</option>
                                <option value="kwikstage" {{ old('kategori') == 'kwikstage' ? 'selected' : '' }}>Kwikstage System</option>
                                <option value="bekisting" {{ old('kategori') == 'bekisting' ? 'selected' : '' }}>Bekisting System</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Spesifikasi Singkat</label>
                            <input type="text" name="spesifikasi" value="{{ old('spesifikasi') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Contoh: Tinggi pipa 6 meter, ketebalan baja 3.2mm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DESKRIPSI (DENGAN CKEDITOR) -->
            <div id="sec-deskripsi" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 scroll-mt-16">
                <h2 class="text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2"><span class="text-red-500">*</span> Deskripsi Lengkap Produk</h2>
                <div class="prose max-w-none">
                    <textarea name="deskripsi" id="deskripsi_editor" class="w-full border border-gray-300 rounded-lg p-3 text-xs focus:outline-none focus:border-[#1BBC9A]">{!! old('deskripsi') !!}</textarea>
                </div>
            </div>

            <!-- SECTION 3: INFORMASI PENJUALAN -->
            <div id="sec-penjualan" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 scroll-mt-16">
                <h2 class="text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Penjualan</h2>

                <!-- Toggle Variasi Shopee Style -->
                <div class="bg-slate-50 p-4 rounded-xl border border-gray-150 mb-6 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-bold text-gray-800">Aktifkan Variasi Ukuran / Seri Produk</span>
                        <span class="text-[11px] text-gray-400 block mt-0.5">Aktifkan jika item memiliki variasi ukuran tinggi (misal: T190 & T170) dengan dimensi kargo berbeda.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="has_variant" name="has_variant" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1BBC9A]"></div>
                    </label>
                </div>

                <!-- SUB-BLOCK A: SINGLE PRODUCT FORM -->
                <div id="single-product-block" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1"><span class="text-red-500">*</span> Harga (Rp)</label>
                            <input type="number" name="harga" id="single_harga" value="{{ old('harga') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="280000" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Harga Coret / Diskon (Rp)</label>
                            <input type="number" name="harga_coret" value="{{ old('harga_coret') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="310000">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1"><span class="text-red-500">*</span> Jumlah Stok Fisik</label>
                            <input type="number" name="stok" id="single_stok" min="0" value="{{ old('stok', 0) }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Maks. Batas Pembelian</label>
                            <input type="number" name="maks_pembelian" value="{{ old('maks_pembelian') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Per Transaksi (Batas Kargo)">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Warna Material (Opsional)</label>
                            <input type="text" name="warna" value="{{ old('warna') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Contoh: Hitam / Silver / Orange">
                        </div>
                    </div>
                </div>

                <!-- SUB-BLOCK B: MATRIKS VARIATION TABLE -->
                <div id="variant-product-block" class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 p-4 hidden">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Matriks Hubungan Seri Ukuran</span>
                        <button type="button" id="add-variant-btn" class="bg-gray-800 hover:bg-gray-900 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg flex items-center gap-1 cursor-pointer transition-colors">
                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baris Varian
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-gray-200 text-gray-500 font-bold uppercase text-[10px] bg-gray-100">
                                    <th class="p-3 w-32">Ukuran / Seri</th>
                                    <th class="p-3 w-28">Harga (Rp)</th>
                                    <th class="p-3 w-28">Disc (Rp)</th>
                                    <th class="p-3 w-20">Stok</th>
                                    <th class="p-3 w-20">Berat (gr)</th>
                                    <th class="p-3 w-40">Dimensi PxLxT (cm)</th>
                                    <th class="p-3 w-24">Foto</th>
                                    <th class="p-3 text-center w-8"></th>
                                </tr>
                            </thead>
                            <tbody id="variant-container" class="divide-y divide-gray-200 bg-white">
                                <tr class="variant-row">
                                    <td class="p-2"><input type="text" name="varians[0][ukuran]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="T190"></td>
                                    <td class="p-2"><input type="number" name="varians[0][harga]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="240000"></td>
                                    <td class="p-2"><input type="number" name="varians[0][harga_coret]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Diskon"></td>
                                    <td class="p-2"><input type="number" name="varians[0][stok]" value="0" min="0" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]"></td>
                                    <td class="p-2"><input type="number" name="varians[0][berat]" min="0" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="Gram"></td>
                                    <td class="p-2">
                                        <div class="flex items-center gap-1 font-mono text-[10px] text-gray-400">
                                            <input type="number" name="varians[0][panjang]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="P"> x
                                            <input type="number" name="varians[0][lebar]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="L"> x
                                            <input type="number" name="varians[0][tinggi]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="T">
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="flex items-center gap-1">
                                            <img class="variant-preview w-7 h-7 object-cover rounded border shrink-0 hidden">
                                            <input type="file" name="varians[0][gambar]" accept="image/*" class="w-full text-[9px] bg-white border border-gray-300 rounded-md p-0.5 variant-field">
                                        </div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="text-gray-300 cursor-not-allowed" disabled><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: PENGIRIMAN & PRE-ORDER -->
            <div id="sec-pengiriman" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 scroll-mt-16">
                <h2 class="text-base font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2">Pengiriman & Logistik</h2>

                <div id="logistic-single-fields" class="space-y-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1"><span class="text-red-500">*</span> Berat Produk Asli</label>
                            <div class="relative">
                                <input type="number" name="berat" id="single_berat" value="{{ old('berat') }}" min="0" class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Contoh: 18500" required>
                                <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-bold">gr</span>
                            </div>
                            <span class="text-[10px] text-gray-400 block mt-1">Masukkan angka gram bulat (Contoh: 18.5 kg = tulis 18500).</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1"><span class="text-red-500">*</span> Dimensi Ukuran Kargo Paket</label>
                            <div class="flex items-center gap-2 font-mono text-xs text-gray-400">
                                <div class="relative flex-1">
                                    <input type="number" name="panjang" id="single_panjang" value="{{ old('panjang') }}" min="0" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-xs text-gray-700 focus:outline-none focus:border-[#1BBC9A]" placeholder="Panjang" required>
                                    <span class="absolute right-2 top-2.5 text-[10px]">cm</span>
                                </div> x
                                <div class="relative flex-1">
                                    <input type="number" name="lebar" id="single_lebar" value="{{ old('lebar') }}" min="0" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-xs text-gray-700 focus:outline-none focus:border-[#1BBC9A]" placeholder="Lebar" required>
                                    <span class="absolute right-2 top-2.5 text-[10px]">cm</span>
                                </div> x
                                <div class="relative flex-1">
                                    <input type="number" name="tinggi" id="single_tinggi" value="{{ old('tinggi') }}" min="0" class="w-full border border-gray-300 rounded-lg p-2.5 pr-8 text-xs text-gray-700 focus:outline-none focus:border-[#1BBC9A]" placeholder="Tinggi" required>
                                    <span class="absolute right-2 top-2.5 text-[10px]">cm</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400 block mt-1">Penting untuk menghitung keakuratan rumus berat volumetrik kargo logistik.</span>
                        </div>
                    </div>
                </div>

                <div id="logistic-variant-note" class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-amber-700 mb-6 flex gap-2 items-start hidden">
                    <i class="fa-solid fa-circle-info mt-0.5 text-xs"></i>
                    <p class="text-[11px] leading-relaxed font-medium">
                        <strong>Logistik Berbasis Varian Aktif:</strong> Data berat asli serta dimensi panjang × lebar × tinggi saat ini diatur secara mandiri pada tiap baris di tabel <strong>Informasi Penjualan</strong> atas.
                    </p>
                </div>

                <!-- Fitur Pre-Order (PO) -->
                <div class="border-t border-gray-150 pt-4 mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><span class="text-red-500">*</span> Apakah Produk Ini Pre-Order?</label>
                        <div class="flex gap-6 text-xs">
                            <label class="flex items-center gap-1.5 font-semibold text-gray-700 cursor-pointer">
                                <input type="radio" name="is_preorder" value="0" class="w-4 h-4 text-[#1BBC9A] focus:ring-[#1BBC9A]" checked id="po-no"> Tidak (Ready Stock)
                            </label>
                            <label class="flex items-center gap-1.5 font-semibold text-gray-700 cursor-pointer">
                                <input type="radio" name="is_preorder" value="1" class="w-4 h-4 text-[#1BBC9A] focus:ring-[#1BBC9A]" id="po-yes"> Ya (Pre-Order Inden)
                            </label>
                        </div>
                    </div>

                    <div id="po-duration-block" class="bg-gray-50 p-4 rounded-xl border border-gray-200 max-w-sm hidden">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Durasi Antrean Produksi</label>
                        <select name="waktu_preorder" class="w-full bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-xs focus:outline-none focus:border-[#1BBC9A] appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.55rem_auto] bg-[position:right_0.75rem_center] bg-no-repeat">
                            <option value="3">3 Hari Kerja</option>
                            <option value="5" selected>5 Hari Kerja</option>
                            <option value="7">7 Hari Kerja</option>
                            <option value="14">14 Hari Kerja</option>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Estimasi pengerjaan pabrikasi di atas belum termasuk durasi perjalanan logistik kendaraan.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: OPTIMASI SEO (META TAGS) -->
            <div id="sec-seo" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 scroll-mt-16">
                <div class="mb-4 border-b border-gray-100 pb-2">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-[#1BBC9A]"></i> Optimasi SEO Produk (Meta Tags)
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Pengaturan meta tag agar produk perancah ini muncul secara optimal di pencarian Google & pratinjau media sosial.</p>
                </div>

                <div class="space-y-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Kosongkan jika ingin menyamakan dengan Nama Produk">
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
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Pisahkan dengan koma. Contoh: Jual Scaffolding Frame, Main Frame T190, Supplier Scaffolding Surabaya">
                        @error('meta_keywords')
                            <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:outline-none focus:border-[#1BBC9A] bg-white" placeholder="Ringkasan spesifikasi singkat 150-160 karakter yang tampil di pencarian Google...">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ACTION FOOTER BUTTONS -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-[#1BBC9A] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-xs shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan & Daftarkan Produk
                </button>
                <a href="{{ route('produk.index') }}" class="bg-white border border-gray-300 text-gray-600 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-100 transition-colors text-xs text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // CKEDITOR DESKRIPSI PRODUK
    ClassicEditor
        .create(document.querySelector('#deskripsi_editor'), {
            toolbar: {
                items: [
                    'bold', 'italic', 'underline',
                    '|', 'bulletedList', 'numberedList',
                    '|', 'undo', 'redo'
                ]
            }
        })
        .then(editor => {
            const textarea = document.querySelector('#deskripsi_editor');
            editor.model.document.on('change:data', () => { textarea.value = editor.getData(); });
            const form = textarea.closest('form');
            if (form) {
                form.addEventListener('submit', () => { textarea.value = editor.getData(); });
            }
        })
        .catch(error => { console.error(error); });

    const galleryDT = new DataTransfer();

    function openGalleryPicker() {
        const galleryInput = document.getElementById('galleryInput');
        if (galleryInput) {
            galleryInput.value = '';
            galleryInput.click();
        }
    }

    function previewCover(input) {
        const coverPreview = document.getElementById('coverPreview');
        const coverPlaceholder = document.getElementById('coverPlaceholder');
        const coverLabel = document.getElementById('coverLabel');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.src = e.target.result;
                coverPreview.classList.remove('hidden');
                coverLabel.classList.remove('hidden');
                coverPlaceholder.classList.add('hidden');
                updatePhotoCounter();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleGalleryUpload(input) {
        const files = Array.from(input.files);
        if (!files.length) return;

        const coverInput = document.getElementById('coverInput');
        const coverCount = (coverInput && coverInput.files && coverInput.files.length) ? 1 : 0;

        files.forEach(file => {
            const totalFoto = coverCount + galleryDT.files.length;
            if (totalFoto < 9) {
                galleryDT.items.add(file);
            } else {
                alert('Maksimal total foto (Cover + Galeri) adalah 9 foto.');
            }
        });

        input.files = galleryDT.files;
        renderGalleryPreviews();
    }

    function renderGalleryPreviews() {
        const container = document.getElementById('galleryPreviews');
        if (!container) return;
        container.innerHTML = '';

        Array.from(galleryDT.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-xl border border-gray-200 bg-white overflow-hidden group shadow-sm';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeNewGalleryImage(${index})" 
                            class="absolute top-1 right-1 bg-rose-600 hover:bg-rose-700 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] shadow transition-all cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        updatePhotoCounter();
    }

    function removeNewGalleryImage(index) {
        const newDT = new DataTransfer();
        Array.from(galleryDT.files).forEach((file, i) => {
            if (i !== index) newDT.items.add(file);
        });

        galleryDT.items.clear();
        Array.from(newDT.files).forEach(f => galleryDT.items.add(f));

        const galleryInput = document.getElementById('galleryInput');
        if (galleryInput) galleryInput.files = galleryDT.files;
        
        renderGalleryPreviews();
    }

    function updatePhotoCounter() {
        const coverInput = document.getElementById('coverInput');
        const coverCount = (coverInput && coverInput.files && coverInput.files.length) ? 1 : 0;
        const total = coverCount + galleryDT.files.length;

        const photoCounter = document.getElementById('photoCounter');
        if (photoCounter) photoCounter.innerText = `(${total}/9)`;

        const addMoreBox = document.getElementById('addMoreBox');
        if (addMoreBox) {
            if (total >= 9) {
                addMoreBox.classList.add('hidden');
            } else {
                addMoreBox.classList.remove('hidden');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const galleryInput = document.getElementById('galleryInput');
                if (galleryInput && galleryDT.files.length > 0) {
                    galleryInput.files = galleryDT.files;
                }
            });
        }
        updatePhotoCounter();
    });

    const namaInput = document.getElementById('nama_produk');
    const charCount = document.getElementById('char-count');
    function updateCharCount() {
        if(namaInput && charCount) charCount.textContent = `${namaInput.value.length}/255`;
    }
    if(namaInput) {
        namaInput.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    const tabs = document.querySelectorAll('#form-tabs a');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            tabs.forEach(t => t.className = "text-gray-500 hover:text-gray-700 py-3 px-1 transition-all");
            this.className = "text-[#1BBC9A] border-b-2 border-[#1BBC9A] py-3 px-1 transition-all";
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({ behavior: 'smooth' });
        });
    });

    const hasVariantCheckbox = document.getElementById('has_variant');
    const singleBlock = document.getElementById('single-product-block');
    const variantBlock = document.getElementById('variant-product-block');
    const logisticSingleFields = document.getElementById('logistic-single-fields');
    const logisticVariantNote = document.getElementById('logistic-variant-note');

    const singleHarga = document.getElementById('single_harga');
    const singleStok = document.getElementById('single_stok');
    const singleBerat = document.getElementById('single_berat');
    const singlePanjang = document.getElementById('single_panjang');
    const singleLebar = document.getElementById('single_lebar');
    const singleTinggi = document.getElementById('single_tinggi');

    if (hasVariantCheckbox) {
        hasVariantCheckbox.addEventListener('change', function() {
            if (this.checked) {
                singleBlock.classList.add('hidden');
                variantBlock.classList.remove('hidden');
                logisticSingleFields.classList.add('hidden');
                logisticVariantNote.classList.remove('hidden');

                singleHarga.removeAttribute('required');
                singleStok.removeAttribute('required');
                singleBerat.removeAttribute('required');
                singlePanjang.removeAttribute('required');
                singleLebar.removeAttribute('required');
                singleTinggi.removeAttribute('required');

                document.querySelectorAll('.variant-field').forEach(el => el.setAttribute('required', 'true'));
            } else {
                singleBlock.classList.remove('hidden');
                variantBlock.classList.add('hidden');
                logisticSingleFields.classList.remove('hidden');
                logisticVariantNote.classList.add('hidden');

                singleHarga.setAttribute('required', 'true');
                singleStok.setAttribute('required', 'true');
                singleBerat.setAttribute('required', 'true');
                singlePanjang.setAttribute('required', 'true');
                singleLebar.setAttribute('required', 'true');
                singleTinggi.setAttribute('required', 'true');

                document.querySelectorAll('.variant-field').forEach(el => el.removeAttribute('required'));
            }
        });
    }

    let variantIndex = 1;
    const addVariantBtn = document.getElementById('add-variant-btn');
    if (addVariantBtn) {
        addVariantBtn.addEventListener('click', function() {
            const container = document.getElementById('variant-container');
            const newRow = document.createElement('tr');
            newRow.className = 'variant-row divide-x divide-gray-100';
            newRow.innerHTML = `
                <td class="p-2"><input type="text" name="varians[${variantIndex}][ukuran]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="T190" required></td>
                <td class="p-2"><input type="number" name="varians[${variantIndex}][harga]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="240000" required></td>
                <td class="p-2"><input type="number" name="varians[${variantIndex}][harga_coret]" class="w-full border border-gray-300 rounded-md p-1.5 text-xs focus:outline-none focus:border-[#1BBC9A]" placeholder="Diskon"></td>
                <td class="p-2"><input type="number" name="varians[${variantIndex}][stok]" value="0" min="0" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" required></td>
                <td class="p-2"><input type="number" name="varians[${variantIndex}][berat]" min="0" class="w-full border border-gray-300 rounded-md p-1.5 text-xs variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="Gram" required></td>
                <td class="p-2">
                    <div class="flex items-center gap-1 font-mono text-[10px] text-gray-400">
                        <input type="number" name="varians[${variantIndex}][panjang]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="P" required> x
                        <input type="number" name="varians[${variantIndex}][lebar]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="L" required> x
                        <input type="number" name="varians[${variantIndex}][tinggi]" min="0" class="w-12 border border-gray-300 rounded-md p-1 text-center text-xs text-gray-700 variant-field focus:outline-none focus:border-[#1BBC9A]" placeholder="T" required>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex items-center gap-1">
                        <img class="variant-preview w-7 h-7 object-cover rounded border shrink-0 hidden">
                        <input type="file" name="varians[${variantIndex}][gambar]" accept="image/*" class="w-full text-[9px] bg-white border border-gray-300 rounded-md p-0.5 variant-field">
                    </div>
                </td>
                <td class="p-2 text-center"><button type="button" class="text-red-500 hover:text-red-700 remove-variant-btn cursor-pointer transition-colors"><i class="fa-solid fa-trash"></i></button></td>
            `;
            container.appendChild(newRow);
            variantIndex++;
        });
    }

    const variantContainer = document.getElementById('variant-container');
    if(variantContainer) {
        variantContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variant-btn')) {
                e.target.closest('.variant-row').remove();
            }
        });

        variantContainer.addEventListener('change', function(e) {
            if (e.target.type === 'file' && e.target.name.includes('[gambar]')) {
                const file = e.target.files[0];
                const previewImg = e.target.closest('.flex').querySelector('.variant-preview');

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.src = '';
                    previewImg.classList.add('hidden');
                }
            }
        });
    }

    const poNo = document.getElementById('po-no');
    const poYes = document.getElementById('po-yes');
    const poDurationBlock = document.getElementById('po-duration-block');

    if(poNo && poYes && poDurationBlock) {
        poNo.addEventListener('change', function() { if(this.checked) poDurationBlock.classList.add('hidden'); });
        poYes.addEventListener('change', function() { if(this.checked) poDurationBlock.classList.remove('hidden'); });
    }
</script>

<style>
    .ck-editor__editable_inline { min-height: 200px; border-radius: 0 0 0.5rem 0.5rem !important; font-size: 0.875rem; }
    .ck-toolbar { border-radius: 0.5rem 0.5rem 0 0 !important; background-color: #f8fafc !important; }
    .ck-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; }
    .ck-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; }
</style>
@endsection