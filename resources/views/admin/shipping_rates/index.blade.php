@extends('layouts.admin')

@section('title', 'Tarif Ongkir Armada | Tangga Mas')

@section('content')
<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- HEADER HALAMAN -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Tarif & Jangkauan Armada Gudang</h1>
                <p class="text-xs text-gray-500 mt-0.5">Kelola wilayah yang dapat dijangkau oleh armada pengiriman Tangga Mas beserta tarif ongkirnya.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-brand-green hover:underline flex items-center gap-1 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- FORM TAMBAH WILAYAH JANGKAUAN DENGAN API -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4 sticky top-20">
                <h2 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-brand-green"></i> Tambah Wilayah Jangkauan
                </h2>

                <form action="{{ route('admin.shipping.store') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <!-- DROPDOWN PROVINSI API -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi</label>
                        <select id="provinsi" name="provinsi" required 
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-green">
                            <option value="">Memuat Provinsi...</option>
                        </select>
                    </div>

                    <!-- DROPDOWN KOTA/KABUPATEN API -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kota / Kabupaten</label>
                        <select id="kota" name="kota" required disabled 
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-green">
                            <option value="">Pilih Provinsi Dahulu...</option>
                        </select>
                        <span class="text-[10px] text-gray-400 mt-1 block leading-tight">*Otomatis tersinkronisasi dengan API Wilayah Indonesia</span>
                    </div>

                    <!-- INPUT TARIF ONGKIR -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tarif Ongkir (Rp)</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs font-bold text-gray-400">Rp</span>
                            <input type="number" name="biaya_pengiriman" placeholder="50000" min="0" step="1000" required 
                                   class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white font-bold text-xs py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Wilayah Baru
                    </button>
                </form>
            </div>

            <!-- TABEL DAFTAR WILAYAH & TARIF -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-truck-ramp-box text-gray-400"></i> Daftar Jangkauan ({{ $shippingRates->count() }})
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 border-b border-gray-100 font-bold uppercase text-[10px] text-gray-400">
                            <tr>
                                <th class="p-3.5">Kota / Kabupaten</th>
                                <th class="p-3.5">Provinsi</th>
                                <th class="p-3.5">Tarif Ongkir</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($shippingRates as $rate)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="p-3.5 font-bold text-gray-900">{{ $rate->kota }}</td>
                                    <td class="p-3.5 text-gray-500">{{ $rate->provinsi }}</td>
                                    <td class="p-3.5 font-extrabold text-brand-price">
                                        Rp {{ number_format($rate->biaya_pengiriman, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3.5">
                                        <form action="{{ route('admin.shipping.toggle', $rate->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Klik untuk mengubah status" 
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer transition-all {{ $rate->is_aktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}">
                                                {{ $rate->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-3.5 text-right space-x-1">
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" onclick="openEditModal({{ $rate->id }}, '{{ $rate->provinsi }}', '{{ $rate->kota }}', {{ (int)$rate->biaya_pengiriman }})" 
                                                class="text-amber-600 hover:text-amber-800 font-bold text-xs p-1.5 rounded hover:bg-amber-50 transition-colors" title="Edit Tarif">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('admin.shipping.destroy', $rate->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus wilayah jangkauan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs p-1.5 rounded hover:bg-rose-50 transition-colors" title="Hapus Wilayah">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 font-medium">
                                        <i class="fa-solid fa-map-location-dot text-2xl mb-2 block text-gray-300"></i>
                                        Belum ada data wilayah jangkauan Armada Gudang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL EDIT TARIF WILAYAH -->
<!-- ========================================================================= -->
<div id="editRateModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative animate-in fade-in zoom-in duration-200">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-brand-green"></i> Edit Tarif Wilayah
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-sm p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editRateForm" method="POST" class="space-y-3">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi</label>
                <input type="text" id="edit_provinsi" name="provinsi" readonly required 
                       class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs font-semibold text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kota / Kabupaten</label>
                <input type="text" id="edit_kota" name="kota" readonly required 
                       class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs font-semibold text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Tarif Ongkir (Rp)</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-gray-400">Rp</span>
                    <input type="number" id="edit_biaya" name="biaya_pengiriman" min="0" step="1000" required 
                           class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-brand-green text-white font-bold text-xs rounded-xl hover:bg-brand-green-dark transition-colors shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- JAVASCRIPT FETCH API WILAYAH INDONESIA & CONTROL MODAL -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const provSelect = document.getElementById('provinsi');
  const kotaSelect = document.getElementById('kota');

  if (!provSelect || !kotaSelect) return;

  // 1. FETCH DATA PROVINSI
  fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
    .then(response => response.json())
    .then(provinces => {
      provSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
      provinces.forEach(prov => {
        let opt = document.createElement('option');
        opt.value = prov.name;     
        opt.dataset.id = prov.id;   
        opt.textContent = prov.name;
        
        // Auto select Jawa Timur secara default
        if (prov.name.toUpperCase() === 'JAWA TIMUR') {
            opt.selected = true;
        }
        provSelect.appendChild(opt);
      });

      // Trigger change event jika Jawa Timur otomatis terpilih
      if (provSelect.value) {
          provSelect.dispatchEvent(new Event('change'));
      }
    })
    .catch(error => {
      console.error("Error:", error);
      provSelect.innerHTML = '<option value="">Gagal memuat data provinsi</option>';
    });

  // 2. FETCH DATA KOTA / KABUPATEN SAAT PROVINSI DIPILIH
  provSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const provId = selectedOption ? selectedOption.dataset.id : null;

    kotaSelect.innerHTML = '<option value="">Memuat Kota/Kabupaten...</option>';
    kotaSelect.setAttribute('disabled', 'true');
    kotaSelect.classList.remove('bg-white');
    kotaSelect.classList.add('bg-gray-50');

    if (!provId) {
        kotaSelect.innerHTML = '<option value="">Pilih Provinsi Dahulu...</option>';
        return;
    }

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
      .then(response => response.json())
      .then(regencies => {
        kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten...</option>';
        kotaSelect.removeAttribute('disabled');
        kotaSelect.classList.remove('bg-gray-50');
        kotaSelect.classList.add('bg-white');

        regencies.forEach(reg => {
          let opt = document.createElement('option');
          opt.value = reg.name;
          opt.textContent = reg.name;
          kotaSelect.appendChild(opt);
        });
      })
      .catch(err => {
        console.error("Gagal memuat kota:", err);
        kotaSelect.innerHTML = '<option value="">Gagal memuat kota</option>';
      });
  });
});

function openEditModal(id, provinsi, kota, biaya) {
    const modal = document.getElementById('editRateModal');
    const form = document.getElementById('editRateForm');
    
    form.action = `/admin/shipping-rates/${id}`;
    document.getElementById('edit_provinsi').value = provinsi;
    document.getElementById('edit_kota').value = kota;
    document.getElementById('edit_biaya').value = biaya;

    modal.classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editRateModal').classList.add('hidden');
}
</script>
@endsection