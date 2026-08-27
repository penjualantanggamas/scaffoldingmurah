@extends('layouts.admin')

@section('title', 'Master Data Armada | Tangga Mas Admin')

@section('content')
<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- HEADER HALAMAN -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Master Data Armada Pengiriman</h1>
                <p class="text-xs text-gray-500 mt-0.5">Atur batasan kapasitas berat (kg), volume (P x L x T), dan urutan prioritas kendaraan armada gudang.</p>
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
            
            <!-- FORM TAMBAH ARMADA BARU -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4 sticky top-20">
                <h2 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-brand-green"></i> Tambah Armada Baru
                </h2>

                <form action="{{ route('admin.vehicles.store') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Armada</label>
                        <input type="text" name="nama_armada" placeholder="Contoh: Pickup Gran Max / Engkel CDE" required 
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:outline-none focus:border-brand-green">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Max Kapasitas Berat (Kg)</label>
                        <div class="relative flex items-center">
                            <input type="number" name="max_berat_kg" placeholder="1000" min="1" step="0.1" required 
                                   class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                            <span class="absolute right-3 text-xs font-bold text-gray-400">kg</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Max Kapasitas Volume (m³)</label>
                        <div class="relative flex items-center">
                            <input type="number" name="max_volume_m3" placeholder="3.5" min="0.1" step="0.01" required 
                                   class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                            <span class="absolute right-3 text-xs font-bold text-gray-400">m³</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Urutan Prioritas Kapasitas</label>
                        <input type="number" name="urutan" value="{{ $vehicles->count() + 1 }}" min="1" required 
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                        <span class="text-[10px] text-gray-400 mt-1 block leading-tight">*1 = Terkecil (Pickup), 2 = Engkel, 3 = CDD, 4 = Fuso</span>
                    </div>

                    <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white font-bold text-xs py-2.5 rounded-xl shadow transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Armada
                    </button>
                </form>
            </div>

            <!-- TABEL DAFTAR MASTER ARMADA -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-truck-front text-gray-400"></i> Daftar Armada Aktif ({{ $vehicles->count() }})
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 border-b border-gray-100 font-bold uppercase text-[10px] text-gray-400">
                            <tr>
                                <th class="p-3.5 text-center w-12">Urutan</th>
                                <th class="p-3.5">Nama Armada</th>
                                <th class="p-3.5">Max Tonase</th>
                                <th class="p-3.5">Max Volume</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vehicles as $v)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="p-3.5 text-center font-bold text-gray-500">#{{ $v->urutan }}</td>
                                    <td class="p-3.5 font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fa-solid fa-truck text-brand-green"></i> {{ $v->nama_armada }}
                                    </td>
                                    <td class="p-3.5 font-bold text-gray-700">
                                        {{ number_format($v->max_berat_kg, 0, ',', '.') }} kg
                                    </td>
                                    <td class="p-3.5 font-bold text-blue-600">
                                        {{ number_format($v->max_volume_m3, 2, ',', '.') }} m³
                                    </td>
                                    <td class="p-3.5">
                                        <form action="{{ route('admin.vehicles.toggle', $v->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Klik untuk mengubah status" 
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer transition-all {{ $v->is_aktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}">
                                                {{ $v->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-3.5 text-right space-x-1">
                                        <button type="button" onclick="openEditVehicleModal({{ $v->id }}, '{{ $v->nama_armada }}', {{ $v->max_berat_kg }}, {{ $v->max_volume_m3 }}, {{ $v->urutan }})" 
                                                class="text-amber-600 hover:text-amber-800 font-bold text-xs p-1.5 rounded hover:bg-amber-50 transition-colors" title="Edit Armada">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus armada ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs p-1.5 rounded hover:bg-rose-50 transition-colors" title="Hapus Armada">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400 font-medium">
                                        <i class="fa-solid fa-truck-ramp-box text-2xl mb-2 block text-gray-300"></i>
                                        Belum ada armada yang terdaftar. Silakan tambah armada baru.
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

<!-- MODAL EDIT ARMADA -->
<div id="editVehicleModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative animate-in fade-in zoom-in duration-200">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-brand-green"></i> Edit Data Armada
            </h3>
            <button type="button" onclick="closeEditVehicleModal()" class="text-gray-400 hover:text-gray-600 text-sm p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editVehicleForm" method="POST" class="space-y-3">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Armada</label>
                <input type="text" id="edit_v_nama" name="nama_armada" required 
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:outline-none focus:border-brand-green">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Max Kapasitas Berat (Kg)</label>
                <div class="relative flex items-center">
                    <input type="number" id="edit_v_berat" name="max_berat_kg" min="1" step="0.1" required 
                           class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                    <span class="absolute right-3 text-xs font-bold text-gray-400">kg</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Max Kapasitas Volume (m³)</label>
                <div class="relative flex items-center">
                    <input type="number" id="edit_v_volume" name="max_volume_m3" min="0.1" step="0.01" required 
                           class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
                    <span class="absolute right-3 text-xs font-bold text-gray-400">m³</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Urutan Prioritas</label>
                <input type="number" id="edit_v_urutan" name="urutan" min="1" required 
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-brand-green">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditVehicleModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-brand-green text-white font-bold text-xs rounded-xl hover:bg-brand-green-dark transition-colors shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function openEditVehicleModal(id, nama, berat, volume, urutan) {
    const modal = document.getElementById('editVehicleModal');
    const form = document.getElementById('editVehicleForm');
    
    form.action = `/admin/vehicles/${id}`;
    document.getElementById('edit_v_nama').value = nama;
    document.getElementById('edit_v_berat').value = berat;
    document.getElementById('edit_v_volume').value = volume;
    document.getElementById('edit_v_urutan').value = urutan;

    modal.classList.remove('hidden');
}

function closeEditVehicleModal() {
    document.getElementById('editVehicleModal').classList.add('hidden');
}
</script>
@endsection