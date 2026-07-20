@extends('layouts.admin')

@section('title', 'Edit Produk: ' . $produk->nama_produk . ' | Tangga Mas Admin')

@section('content')
<div class="p-6 md:p-12">
    <div class="max-w-4xl mx-auto mb-4">
        <a href="{{ route('produk.index') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-green flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Tabel Produk
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-xl p-6 md:p-8 shadow-sm border border-gray-100">
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Form Edit Produk Tangga Mas</h1>
            <p class="text-xs text-gray-400 mt-1">Lakukan perubahan informasi perancah atau data tabel variasi penjualan.</p>
        </div>
        
        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kategori Halaman</label>
                    <select name="kategori" class="w-full bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2.5 text-xs focus:outline-none focus:border-brand-green transition-all text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.65rem_auto] bg-[position:right_0.75rem_center] bg-no-repeat">
                        <option value="frame" {{ $produk->kategori == 'frame' ? 'selected' : '' }}>Frame System</option>
                        <option value="ringlock" {{ $produk->kategori == 'ringlock' ? 'selected' : '' }}>Ringlock System</option>
                        <option value="tubular" {{ $produk->kategori == 'tubular' ? 'selected' : '' }}>Tubular System</option>
                        <option value="kwikstage" {{ $produk->kategori == 'kwikstage' ? 'selected' : '' }}>Kwikstage System</option>
                        <option value="bekisting" {{ $produk->kategori == 'bekisting' ? 'selected' : '' }}>Bekisting System</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green transition-all" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Spesifikasi Singkat</label>
                <input type="text" name="spesifikasi" value="{{ $produk->spesifikasi }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green transition-all" placeholder="Contoh: Scaffolding galvanis T170">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Lengkap Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green transition-all" placeholder="Tulis spesifikasi detail...">{{ $produk->deskripsi }}</textarea>
            </div>

            @php $hasVariant = $produk->varians->count() > 0; @endphp
            
            <!-- Pilihan Checkbox Varian Seri Produk -->
            <div class="bg-slate-50 p-4 rounded-xl border border-gray-200 flex items-center gap-3">
                <input type="checkbox" id="has_variant" name="has_variant" value="1" class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green cursor-pointer" {{ $hasVariant ? 'checked' : '' }}>
                <div>
                    <label for="has_variant" class="block text-sm font-semibold text-gray-800 cursor-pointer">Produk ini memiliki varian ukuran / seri</label>
                    <span class="text-xs text-gray-400 block mt-0.5">Centang jika produk memiliki beberapa ukuran dengan harga atau gambar yang berbeda.</span>
                </div>
            </div>

            <!-- Blok Produk Tunggal -->
            <div id="single-product-block" class="{{ $hasVariant ? 'hidden' : '' }} space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Utama (Rupiah)</label>
                        <input type="number" name="harga" id="single_harga" value="{{ $produk->harga }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" {{ !$hasVariant ? 'required' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Coret (Opsional)</label>
                        <input type="number" name="harga_coret" value="{{ $produk->harga_coret }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Opsi Warna (Opsional)</label>
                        <input type="text" name="warna" value="{{ $produk->warna }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Opsi Ukuran Tunggal (Opsional)</label>
                        <input type="text" name="ukuran" value="{{ $produk->ukuran }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green">
                    </div>
                </div>

                <!-- TAMBAHAN: Kolom Input Stok Produk Tunggal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Stok Fisik Gudang</label>
                    <input type="number" name="stok" id="single_stok" min="0" value="{{ $produk->stok ?? 0 }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green" placeholder="Contoh: 100" {{ !$hasVariant ? 'required' : '' }}>
                    <p class="text-[11px] text-gray-400 mt-1">Jika stok bernilai 0, sistem otomatis menyembunyikan display item dari halaman katalog depan pembeli.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Gambar Produk Baru (Kosongkan jika tidak diubah)</label>
                    @if($produk->gambar && !$hasVariant)
                        <div class="mb-3 flex items-center gap-2 bg-gray-50 p-2 border border-gray-200 rounded-lg w-fit">
                            <img src="{{ asset('images/products/' . $produk->gambar) }}" class="w-12 h-12 object-cover rounded-md">
                            <span class="text-xs text-gray-500 font-medium">Gambar aktif</span>
                        </div>
                    @endif
                    <input type="file" name="gambar" class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm">
                </div>
            </div>

            <!-- Blok Produk Varian -->
            <div id="variant-product-block" class="{{ !$hasVariant ? 'hidden' : '' }} bg-gray-50 p-5 rounded-xl border border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Daftar Varian Ukuran Aktif</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Manajemen penyesuaian harga khusus tiap seri ukuran.</p>
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
                            @if($hasVariant)
                                @foreach($produk->varians as $index => $v)
                                <tr class="variant-row">
                                    <input type="hidden" name="varians[{{ $index }}][id]" value="{{ $v->id }}">
                                    <td class="py-3 pr-3"><input type="text" name="varians[{{ $index }}][ukuran]" value="{{ $v->ukuran }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" required></td>
                                    <td class="py-3 pr-3"><input type="number" name="varians[{{ $index }}][harga]" value="{{ (int)$v->harga }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" required></td>
                                    <td class="py-3 pr-3"><input type="number" name="varians[{{ $index }}][harga_coret]" value="{{ $v->harga_coret ? (int)$v->harga_coret : '' }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green"></td>
                                    <!-- TAMBAHAN: Value data stok varian yang sudah ter-save sebelumnya -->
                                    <td class="py-3 pr-3"><input type="number" name="varians[{{ $index }}][stok]" value="{{ $v->stok ?? 0 }}" min="0" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field focus:outline-none focus:border-brand-green" required></td>
                                    <td class="py-3 pr-3">
                                        <div class="flex items-center gap-2">
                                            @if($v->gambar)
                                                <img src="{{ asset('images/products/' . $v->gambar) }}" class="w-8 h-8 object-cover rounded border shrink-0">
                                            @endif
                                            <input type="file" name="varians[{{ $index }}][gambar]" class="w-full text-[10px] bg-white border border-gray-300 rounded-lg p-1">
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-variant-btn cursor-pointer"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[#1BBC9A] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Perbarui Produk
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

    // Sinkronisasi Interaksi Perubahan Checkbox pada Halaman Edit
    hasVariantCheckbox.addEventListener('change', function() {
        if (this.checked) {
            singleBlock.classList.add('hidden');
            variantBlock.classList.remove('hidden');
            singleHarga.removeAttribute('required');
            singleStok.removeAttribute('required');
            document.querySelectorAll('.variant-field').forEach(el => el.setAttribute('required', 'true'));
        } else {
            singleBlock.classList.remove('hidden');
            variantBlock.classList.add('hidden');
            singleHarga.setAttribute('required', 'true');
            singleStok.setAttribute('required', 'true');
            document.querySelectorAll('.variant-field').forEach(el => el.removeAttribute('required'));
        }
    });

    let variantIndex = {{ $hasVariant ? $produk->varians->count() : 0 }};
    
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const newRow = document.createElement('tr');
        newRow.className = 'variant-row hover:bg-gray-50/50 transition-colors';
        newRow.innerHTML = `
            <td class="py-3 pr-3"><input type="text" name="varians[${variantIndex}][ukuran]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="Contoh: 1.9m" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="25000" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga_coret]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="50000"></td>
            <!-- TAMBAHAN: Input Stok pada Penambahan Varian Baru -->
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][stok]" min="0" value="0" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="10" required></td>
            <td class="py-3 pr-3"><input type="file" name="varians[${variantIndex}][gambar]" class="w-full text-[10px] bg-white border border-gray-300 rounded-lg p-1" required></td>
            <td class="py-3 text-center"><button type="button" class="text-red-500 hover:text-red-700 remove-variant-btn"><i class="fa-solid fa-trash"></i></button></td>
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