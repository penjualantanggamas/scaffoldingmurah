@extends('layouts.admin')

@section('title', 'Tambah Produk Baru | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <div class="max-w-4xl mx-auto mb-4">
        <a href="{{ route('produk.index') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tabel Produk
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-xl p-6 md:p-8 shadow-sm border border-gray-100">
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Form Tambah Produk Tangga Mas</h1>
            <p class="text-xs text-gray-400 mt-1">Isi detail data inventaris perancah baja, tubular, atau bekisting.</p>
        </div>
        
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kategori Halaman</label>
                    <select name="kategori" class="w-full border border-gray-300 rounded-lg p-2.5 bg-white text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all" required>
                        <option value="frame">Frame System</option>
                        <option value="ringlock">Ringlock System</option>
                        <option value="tubular">Tubular System</option>
                        <option value="kwikstage">Kwikstage System</option>
                        <option value="bekisting">Bekisting System</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all" placeholder="Contoh: Main Frame T190" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Spesifikasi Singkat</label>
                <input type="text" name="spesifikasi" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all" placeholder="Contoh: Scaffolding galvanis T170 untuk beban statis tinggi">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Lengkap Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all" placeholder="Tulis spesifikasi detail material baja SNI, keunggulan konstruksi, dan kapasitas beban keselamatan (K3) di sini..."></textarea>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-gray-200 flex items-center gap-3">
                <input type="checkbox" id="has_variant" name="has_variant" value="1" class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer">
                <div>
                    <label for="has_variant" class="block text-sm font-semibold text-gray-800 cursor-pointer">Produk ini memiliki varian ukuran / seri</label>
                    <span class="text-xs text-gray-400 block mt-0.5">Centang jika produk memiliki beberapa ukuran dengan harga atau gambar yang berbeda.</span>
                </div>
            </div>

            <!-- BLOK PRODUK TUNGGAL -->
            <div id="single-product-block" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Utama (Rupiah)</label>
                        <input type="number" name="harga" id="single_harga" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="21000" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Coret / Sebelum Diskon (Opsional)</label>
                        <input type="number" name="harga_coret" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="42000">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Opsi Warna (Opsional)</label>
                        <input type="text" name="warna" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Contoh: Hijau, Biru, Orange">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Opsi Ukuran Tunggal (Opsional)</label>
                        <input type="text" name="ukuran" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Contoh: 1.2m, 1.7m, 1.9m">
                    </div>
                </div>

                <!-- TAMBAHAN: Kolom Stok Produk Tunggal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Stok Fisik Gudang</label>
                    <input type="number" name="stok" id="single_stok" min="0" value="0" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Contoh: 100" required>
                    <p class="text-[11px] text-gray-400 mt-1">Jika stok diisi 0, produk otomatis tidak akan ditayangkan di halaman depan katalog.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Gambar Produk</label>
                    <input type="file" name="gambar" id="single_gambar" class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm focus:outline-none focus:border-brand-green" required>
                </div>
            </div>

            <!-- BLOK PRODUK VARIAN -->
            <div id="variant-product-block" class="bg-gray-50 p-5 rounded-xl border border-gray-200 hidden">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Daftar Varian Ukuran Dinamis</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Setiap baris ukuran wajib diisi spesifikasi dimensi, harga, stok, dan fotonya.</p>
                    </div>
                    <button type="button" id="add-variant-btn" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors flex items-center gap-1 cursor-pointer ml-auto sm:ml-0">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Ukuran
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500 font-bold uppercase">
                                <th class="pb-2 pr-3 w-1/5">Ukuran / Seri</th>
                                <th class="pb-2 pr-3 w-1/5">Harga (Rp)</th>
                                <th class="pb-2 pr-3 w-1/5">Harga Coret (Rp)</th>
                                <th class="pb-2 pr-3 w-1/6">Stok Varian</th> <!-- Kolom Header Baru -->
                                <th class="pb-2 pr-3 w-1/4">Gambar Varian</th>
                                <th class="pb-2 text-center w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="variant-container" class="divide-y divide-gray-100">
                            <tr class="variant-row">
                                <td class="py-3 pr-3"><input type="text" name="varians[0][ukuran]" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" placeholder="Contoh: 1.7m"></td>
                                <td class="py-3 pr-3"><input type="number" name="varians[0][harga]" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" placeholder="21000"></td>
                                <td class="py-3 pr-3"><input type="number" name="varians[0][harga_coret]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="42000"></td>
                                <!-- TAMBAHAN: Input Stok Baris Pertama Varian -->
                                <td class="py-3 pr-3"><input type="number" name="varians[0][stok]" min="0" value="0" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" placeholder="10"></td>
                                <td class="py-3 pr-3"><input type="file" name="varians[0][gambar]" class="w-full text-[10px] bg-white border border-gray-300 rounded-lg p-1 variant-field"></td>
                                <td class="py-3 text-center"><button type="button" class="text-gray-300 cursor-not-allowed" disabled><i class="fa-solid fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[#1BBC9A] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Produk
                </button>
                <a href="{{ route('produk.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-sm text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const hasVariantCheckbox = document.getElementById('has_variant');
    const singleBlock = document.getElementById('single-product-block');
    const variantBlock = document.getElementById('variant-product-block');
    const singleHarga = document.getElementById('single_harga');
    const singleStok = document.getElementById('single_stok');
    const singleGambar = document.getElementById('single_gambar');

    hasVariantCheckbox.addEventListener('change', function() {
        if (this.checked) {
            singleBlock.classList.add('hidden');
            variantBlock.classList.remove('hidden');
            singleHarga.removeAttribute('required');
            singleStok.removeAttribute('required');
            singleGambar.removeAttribute('required');
            document.querySelectorAll('.variant-field').forEach(el => el.setAttribute('required', 'true'));
        } else {
            singleBlock.classList.remove('hidden');
            variantBlock.classList.add('hidden');
            singleHarga.setAttribute('required', 'true');
            singleStok.setAttribute('required', 'true');
            singleGambar.setAttribute('required', 'true');
            document.querySelectorAll('.variant-field').forEach(el => el.removeAttribute('required'));
        }
    });

    let variantIndex = 1;
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const newRow = document.createElement('tr');
        newRow.className = 'variant-row hover:bg-gray-50/50 transition-colors';
        newRow.innerHTML = `
            <td class="py-3 pr-3"><input type="text" name="varians[${variantIndex}][ukuran]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="Contoh: 1.9m" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="25000" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga_coret]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="50000"></td>
            <!-- TAMBAHAN: Input Stok pada Penambahan Baris Varian Baru -->
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][stok]" min="0" value="0" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="10" required></td>
            <td class="py-3 pr-3"><input type="file" name="varians[${variantIndex}][gambar]" class="w-full text-[10px] bg-white border border-gray-300 rounded-lg p-1" required></td>
            <td class="py-3 text-center"><button type="button" class="text-red-500 hover:text-red-700 remove-variant-btn cursor-pointer"><i class="fa-solid fa-trash"></i></button></td>
        `;
        container.appendChild(newRow);
        variantIndex++;
    });

    document.getElementById('variant-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant-btn')) { 
            e.target.closest('.variant-row').remove(); 
        }
    });
</script>
@endsection