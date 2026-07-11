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
                    <select name="kategori" class="w-full border border-gray-300 rounded-lg p-2.5 bg-white text-sm focus:outline-none focus:border-brand-green transition-all" required>
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
                <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-brand-green transition-all" placeholder="Tulis spesifikasi detail...">${{ $produk->deskripsi }}</textarea>
            </div>

            @php $hasVariant = $produk->varians->count() > 0; @endphp
            <input type="hidden" name="has_variant" id="has_variant" value="{{ $hasVariant ? '1' : '0' }}">

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
                                <th class="pb-2 pr-3 w-1/4">Ukuran / Seri</th>
                                <th class="pb-2 pr-3 w-1/4">Harga (Rp)</th>
                                <th class="pb-2 pr-3 w-1/4">Harga Coret (Rp)</th>
                                <th class="pb-2 pr-3 w-1/4">Gambar Varian</th>
                                <th class="pb-2 text-center w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="variant-container" class="divide-y divide-gray-100">
                            @if($hasVariant)
                                @foreach($produk->varians as $index => $v)
                                <tr class="variant-row">
                                    <input type="hidden" name="varians[{{ $index }}][id]" value="{{ $v->id }}">
                                    <td class="py-3 pr-3"><input type="text" name="varians[{{ $index }}][ukuran]" value="{{ $v->ukuran }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field" required></td>
                                    <td class="py-3 pr-3"><input type="number" name="varians[{{ $index }}][harga]" value="{{ (int)$v->harga }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs variant-field" required></td>
                                    <td class="py-3 pr-3"><input type="number" name="varians[{{ $index }}][harga_coret]" value="{{ $v->harga_coret ? (int)$v->harga_coret : '' }}" class="w-full border border-gray-300 rounded-lg p-2 text-xs"></td>
                                    <td class="py-3 pr-3">
                                        <div class="flex items-center gap-2">
                                            @if($v->gambar)
                                                <img src="{{ asset('images/products/' . $v->gambar) }}" class="w-8 h-8 object-cover rounded border">
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
    let variantIndex = {{ $hasVariant ? $produk->varians->count() : 0 }};
    
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const newRow = document.createElement('tr');
        newRow.className = 'variant-row hover:bg-gray-50/50 transition-colors';
        newRow.innerHTML = `
            <td class="py-3 pr-3"><input type="text" name="varians[${variantIndex}][ukuran]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="Contoh: 1.9m" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="25000" required></td>
            <td class="py-3 pr-3"><input type="number" name="varians[${variantIndex}][harga_coret]" class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-brand-green" placeholder="50000"></td>
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