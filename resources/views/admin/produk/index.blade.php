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
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Produk</h1>
                <p class="text-xs text-gray-400 mt-0.5">Kelola seluruh data inventaris</p>
            </div>
            
            <!-- KELOMPOK TOMBOL AKSI (Sejajar Kanan) -->
            <div class="flex flex-wrap items-center gap-2">
                
                <!-- 1. Tombol Unduh Template Bertipe Dropdown -->
                <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="bg-white hover:bg-gray-50 text-gray-750 font-medium text-sm py-2 px-4 rounded-xl transition-all flex items-center gap-2 border border-gray-200 shadow-sm cursor-pointer" title="Pilih format unduhan format Excel">
                        <i class="fa-solid fa-download text-blue-600"></i>
                        <span>Template Excel</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <!-- Menu Dropdown Pilihan Template -->
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 rounded-xl bg-white shadow-lg border border-gray-100 z-50 overflow-hidden hidden" 
                        :class="{ 'hidden': !open }">
                        <div class="py-1">
                            <!-- Pilihan 1: Tambah Baru (Kosong) -->
                            <a href="{{ route('produk.template') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 border-b border-gray-50 font-medium">
                                <i class="fa-solid fa-file-circle-plus text-emerald-600 text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">Template Baru</span>
                                    <span class="text-[10px] text-gray-400">Untuk tambah produk dari awal</span>
                                </div>
                            </a>
                            
                            <!-- Pilihan 2: Edit Massal (Berisi Data Database) -->
                            <a href="{{ route('produk.templateEdit') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 font-medium">
                                <i class="fa-solid fa-file-pen text-amber-500 text-sm"></i>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">Template Edit Massal</span>
                                    <span class="text-[10px] text-gray-400">Unduh data produk aktif saat ini</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Form Upload Excel Massal -->
                <form action="{{ route('produk.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center m-0">
                    @csrf
                    <input type="file" name="file_excel" id="file_excel" class="hidden" accept=".xlsx, .xls, .csv" onchange="this.form.submit()">
                    <label for="file_excel" class="bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm py-2 px-4 rounded-xl transition-all cursor-pointer flex items-center gap-2 border border-gray-200 shadow-sm" title="Unggah file Excel">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i>
                        <span>Import Excel</span>
                    </label>
                </form>

                <!-- PERBAIKAN: Tombol Hapus Massal disetel flex secara dinamis lewat JS -->
                <button id="btnDeleteMassal" class="hidden bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors items-center gap-1.5 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-trash-can text-xs"></i> Hapus Massal (<span id="checkCount">0</span>)
                </button>

                <!-- 3. Tombol Tambah Produk Manual -->
                <a href="{{ route('produk.create') }}" class="bg-[#1BBC9A] text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-[#0C5646] transition-colors text-sm shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Produk Baru
                </a>
                
            </div>
        </div>

        <!-- AREA FILTER PENCARIAN & KATEGORI -->
        <form action="{{ route('produk.index') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200/60 flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-1/2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama produk atau kata kunci spesifikasi..." 
                    class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-xs focus:outline-none focus:border-brand-green transition-all"
                >
            </div>
            
            <div class="w-full sm:w-1/4">
                <select name="kategori" class="w-full bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2.5 text-xs focus:outline-none focus:border-brand-green transition-all text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.65rem_auto] bg-[position:right_0.75rem_center] bg-no-repeat">
                    <option value="">-- Semua Kategori --</option>
                    <option value="frame" {{ request('kategori') == 'frame' ? 'selected' : '' }}>Frame System</option>
                    <option value="ringlock" {{ request('kategori') == 'ringlock' ? 'selected' : '' }}>Ringlock System</option>
                    <option value="tubular" {{ request('kategori') == 'tubular' ? 'selected' : '' }}>Tubular System</option>
                    <option value="kwikstage" {{ request('kategori') == 'kwikstage' ? 'selected' : '' }}>Kwikstage System</option>
                    <option value="bekisting" {{ request('kategori') == 'bekisting' ? 'selected' : '' }}>Bekisting System</option>
                </select>
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2 shrink-0">
                <button type="submit" class="w-full sm:w-auto bg-[#1BBC9A] text-white px-5 py-2 rounded-lg font-semibold hover:bg-[#0C5646] transition-colors text-xs flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-filter text-[10px]"></i> Cari Data
                </button>
                
                @if(request('search') || request('kategori') || session('last_search') || session('last_kategori'))
                    <a href="{{ route('produk.index', ['reset' => 1]) }}" class="w-full sm:w-auto bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-xs text-center flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
                    </a>
                @endif
            </div>
        </form>

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
                        <!-- PERBAIKAN: Checkbox Master untuk pilih semua -->
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" id="checkboxMaster" class="rounded border-gray-300 text-[#1BBC9A] focus:ring-[#1BBC9A] w-4 h-4 cursor-pointer">
                        </th>
                        <th class="p-4 w-20">Gambar</th>
                        <th class="p-4">Nama Produk</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Unggulan</th> 
                        <th class="p-4">Harga Katalog</th>
                        <th class="p-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                    @forelse($produks as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- PERBAIKAN: Checkbox Pilihan per item data -->
                        <td class="p-4 text-center">
                            <input type="checkbox" name="produk_ids[]" value="{{ $item->id }}" class="produk-checkbox rounded border-gray-300 text-[#1BBC9A] focus:ring-[#1BBC9A] w-4 h-4 cursor-pointer">
                        </td>

                        <!-- Preview Gambar -->
                        <td class="p-4">
                            <img src="{{ $item->gambar ? asset('images/products/' . $item->gambar) : asset('images/logotm.png') }}" 
                                alt="{{ $item->nama_produk }}" 
                                class="w-12 h-12 object-cover rounded-lg border border-gray-100 shadow-inner bg-gray-50 p-1">
                        </td>
                                                
                        <!-- Info Produk -->
                        <td class="p-4 max-w-xs md:max-w-sm whitespace-normal">
                            <span class="font-semibold text-gray-900 block leading-snug line-clamp-2 cursor-help" title="{{ $item->nama_produk }}">
                                {{ $item->nama_produk }}
                            </span>
                            <span class="text-[11px] text-gray-400 block mt-1 tracking-wide truncate" title="Slug: {{ $item->slug }}">
                                Slug: {{ $item->slug }}
                            </span>
                        </td>
                                                
                        <!-- Kolom Kategori -->
                        <td class="p-4 capitalize">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full text-xs font-semibold border border-slate-200/40">
                                {{ $item->kategori }} System
                            </span>
                        </td>

                        <!-- Kolom Terlaris -->
                        <td class="p-4">
                            <form action="{{ route('produk.toggleTerlaris', $item->id) }}" method="POST">
                                @csrf
                                @if($item->is_terlaris)
                                    <button type="submit" class="bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold px-2.5 py-1.5 rounded-md hover:bg-amber-100 transition-colors flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-star text-amber-500"></i> Terlaris
                                    </button>
                                @else
                                    <button type="submit" class="bg-gray-50 text-gray-400 border border-gray-200 text-[11px] font-medium px-2.5 py-1.5 rounded-md hover:bg-gray-100 hover:text-gray-600 transition-all flex items-center gap-1 cursor-pointer">
                                        <i class="fa-regular fa-star"></i>Terlaris
                                    </button>
                                @endif
                            </form>
                        </td>
                        
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
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100/70 px-3 py-1.5 rounded-lg border border-red-100 transition-colors flex items-center gap-1 text-xs cursor-pointer">
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
                            <p class="text-sm">Tidak ada produk scaffolding yang cocok dengan kriteria pencarian Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- PERBAIKAN: Menambahkan SweetAlert2 untuk modal konfirmasi hapus massal -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxMaster = document.getElementById('checkboxMaster');
    const checkboxes = document.querySelectorAll('.produk-checkbox');
    const btnDeleteMassal = document.getElementById('btnDeleteMassal');
    const checkCount = document.getElementById('checkCount');

    if (!checkboxMaster || !btnDeleteMassal) return;

    // Fungsi menghitung dan mengontrol visibilitas tombol Hapus Massal
    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.produk-checkbox:checked').length;
        checkCount.innerText = checkedCount;

        if (checkedCount > 0) {
            btnDeleteMassal.classList.remove('hidden');
            btnDeleteMassal.classList.add('inline-flex');
        } else {
            btnDeleteMassal.classList.remove('inline-flex');
            btnDeleteMassal.classList.add('hidden');
        }
    }

    // Event Checkbox Master (Centang Semua / Hapus Semua Centang)
    checkboxMaster.addEventListener('change', function () {
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Event individual checkbox
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked) checkboxMaster.checked = false;
            
            if (document.querySelectorAll('.produk-checkbox:checked').length === checkboxes.length) {
                checkboxMaster.checked = true;
            }
            updateBulkDeleteButton();
        });
    });

    // Request AJAX Fetch untuk proses eksekusi Hapus Massal
    btnDeleteMassal.addEventListener('click', function () {
        const selectedIds = Array.from(document.querySelectorAll('.produk-checkbox:checked')).map(cb => cb.value);

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda akan menghapus ${selectedIds.length} produk scaffolding terpilih secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Massal!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.produk.deleteMassal') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Eror!', 'Terjadi masalah pada koneksi server.', 'error');
                });
            }
        });
    });
});
</script>
@endsection