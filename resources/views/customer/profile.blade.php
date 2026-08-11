@extends('layouts.frontend')

@section('title', 'Pengaturan Akun | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-100 min-h-screen pb-16">
    <div class="container mx-auto px-4 max-w-3xl pt-4 sm:pt-6">

        <!-- HEADER HALAMAN -->
        <div class="flex items-center justify-between mb-4 px-1">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Pengaturan Akun</h1>
            <span class="text-xs text-gray-400 font-medium">Tangga Mas</span>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if (session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- KARTU RINGKASAN PROFIL AKUN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 border border-emerald-200 text-[#1BBC9A] flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="text-sm font-bold text-gray-900 truncate">{{ $customer->nama_lengkap }}</h2>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $customer->email ?? $customer->no_hp }}</p>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MENU PILIHAN PENGATURAN AKUN (STYLE SHOPEE) -->
        <!-- ========================================================================= -->
        <div id="mainAccountMenu" class="space-y-5">
            
            <!-- GRUP 1: AKUN SAYA -->
            <div>
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider px-3 mb-1.5 block">
                    Akun Saya
                </span>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm divide-y divide-gray-100 overflow-hidden">
                    
                    <button type="button" onclick="switchSection('profileSection')" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-user-gear text-gray-400 group-hover:text-[#1BBC9A] text-sm w-5"></i>
                            <span class="text-xs sm:text-sm font-medium text-gray-800">Pengaturan Profil</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                    </button>

                    <button type="button" onclick="switchSection('addressSection')" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-map-location-dot text-gray-400 group-hover:text-[#1BBC9A] text-sm w-5"></i>
                            <span class="text-xs sm:text-sm font-medium text-gray-800">Pengaturan Alamat Proyek</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                    </button>

                </div>
            </div>

            <!-- GRUP 2: AKSES & PENGATURAN AKUN -->
            <div>
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider px-3 mb-1.5 block">
                    Pengaturan Sesi
                </span>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm divide-y divide-gray-100 overflow-hidden">
                    <form method="POST" action="{{ route('customer.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-4 hover:bg-rose-50/50 transition-colors text-left group">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 text-sm w-5"></i>
                                <span class="text-xs sm:text-sm font-bold text-rose-600">Ganti Akun / Logout</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs text-rose-300"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- SECTION 1: FORM PENGATURAN PROFIL -->
        <!-- ========================================================================= -->
        <div id="profileSection" class="hidden space-y-4">
            <button type="button" onclick="showMainMenu()" class="inline-flex items-center gap-2 text-xs font-bold text-[#1BBC9A] hover:underline mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Pengaturan Akun
            </button>

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="text-base font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-[#1BBC9A]"></i> Data Akun Utama
                    </h2>
                </div>

                <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ $customer->nama_lengkap }}" required
                            class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Telepon/WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ $customer->no_hp }}" required
                            class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1">Email Akun (Terkunci)</label>
                        <input type="email" value="{{ $customer->email }}" disabled
                            class="block w-full px-3 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-xs text-gray-400 cursor-not-allowed" />
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs font-bold text-amber-600 mb-2"><i class="fa-solid fa-key"></i> Ganti Password (Opsional)</p>
                        <div class="space-y-3">
                            <input type="password" name="password" placeholder="Password Baru"
                                class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                            <input type="password" name="password_confirmation" placeholder="Ulangi Password Baru"
                                class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all cursor-pointer">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Data Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SECTION 2: PENGATURAN ALAMAT PROYEK -->
        <!-- ========================================================================= -->
        <div id="addressSection" class="hidden space-y-6">
            <button type="button" onclick="showMainMenu()" class="inline-flex items-center gap-2 text-xs font-bold text-[#1BBC9A] hover:underline mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Pengaturan Akun
            </button>

            <!-- KARTU FORM TAMBAH ALAMAT BARU -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6">
                <div class="border-b border-gray-100 pb-4 mb-4">
                    <h2 class="text-base font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-[#1BBC9A]"></i> Daftarkan Lokasi Alamat Baru
                    </h2>
                </div>

                <form method="POST" action="{{ route('customer.address.store') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Nama / Label Alamat</label>
                        <input type="text" name="label_alamat" required placeholder="Contoh: Proyek Ruko Merr Baru, Gudang Utara"
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                    </div>

                    <!-- BARIS API 1: PROVINSI & KOTA -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Provinsi</label>
                            <select id="provinsi" name="provinsi" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-[#1BBC9A]/20">
                                <option value="">Pilih Provinsi...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Kota / Kabupaten</label>
                            <select id="kota" name="kota" required disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1BBC9A]/20">
                                <option value="">Pilih Kota/Kabupaten...</option>
                            </select>
                        </div>
                    </div>

                    <!-- BARIS API 2: KECAMATAN & KELURAHAN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Kecamatan</label>
                            <select id="kecamatan" name="kecamatan" required disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1BBC9A]/20">
                                <option value="">Pilih Kecamatan...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Kelurahan / Desa</label>
                            <select id="kelurahan" name="kelurahan" required disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1BBC9A]/20">
                                <option value="">Pilih Kelurahan...</option>
                            </select>
                        </div>
                    </div>

                    <!-- BARIS DETAIL JALAN & KODE POS -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Nama Jalan / Detail Lokasi</label>
                            <input type="text" name="detail_jalan" required placeholder="Contoh: Jl. Raya Legok Kp. Cakung RT 03/03 No. 12"
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" required placeholder="15820"
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#1BBC9A] transition-all" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_utama" class="rounded border-gray-300 text-[#1BBC9A] focus:ring-[#1BBC9A] h-4 w-4">
                            <span class="ml-2.5 text-xs text-gray-600 font-bold">Jadikan ini Alamat Utama Pengiriman</span>
                        </label>
                        
                        <button type="submit" class="bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-xs py-2.5 px-5 rounded-xl shadow-md transition-all cursor-pointer">
                            <i class="fa-solid fa-plus mr-1"></i> Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>

            <!-- LIST ALAMAT TERDAFTAR -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Alamat Tersimpan ({{ $customer->addresses->count() }})</h3>
                
                @if($customer->addresses->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-gray-400 text-xs font-medium">
                        <i class="fa-solid fa-map text-lg mb-2 block text-gray-300"></i>
                        Belum ada alamat pengiriman terdaftar. Silakan tambahkan alamat di atas.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($customer->addresses as $addr)
                            <div class="bg-white rounded-2xl border p-5 space-y-3 relative transition-all shadow-sm flex flex-col justify-between {{ $addr->is_utama ? 'border-[#1BBC9A] ring-2 ring-emerald-50 bg-emerald-50/10' : 'border-gray-200' }}">
                                
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <i class="fa-solid fa-location-dot text-[#1BBC9A] shrink-0"></i>
                                            <h4 class="font-bold text-xs text-gray-800 truncate">{{ $addr->label_alamat }}</h4>
                                        </div>
                                        @if($addr->is_utama)
                                            <span class="bg-emerald-100 border border-emerald-300 text-[#1BBC9A] text-[9px] font-extrabold px-2 py-0.5 rounded-full tracking-wide uppercase shrink-0">Utama</span>
                                        @endif
                                    </div>

                                    <div class="text-[11px] text-gray-600 leading-relaxed font-medium">
                                        <p class="text-gray-900 font-semibold">{{ $addr->detail_jalan }}</p>
                                        <p>Kel. {{ $addr->kelurahan }}, Kec. {{ $addr->kecamatan }}</p>
                                        <p>{{ $addr->kota }}, {{ $addr->provinsi }} - {{ $addr->kode_pos }}</p>
                                    </div>
                                </div>

                                <!-- AKSI ALAMAT: SET UTAMA & HAPUS -->
                                <div class="flex items-center justify-between border-t border-gray-100 pt-3 mt-2 gap-2">
                                    @if(!$addr->is_utama)
                                        <form method="POST" action="{{ route('customer.address.setPrimary', $addr->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-emerald-50 hover:bg-[#1BBC9A] text-[#1BBC9A] hover:text-white border border-emerald-200 font-bold text-[10px] px-3 py-1.5 rounded-lg transition-all flex items-center gap-1 cursor-pointer">
                                                <i class="fa-solid fa-circle-check"></i> Pilih untuk Order
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-check-double"></i> Digunakan untuk Checkout
                                        </span>
                                    @endif

                                    <form method="POST" action="{{ route('customer.address.destroy', $addr->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat proyek ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-[10px] transition-colors flex items-center gap-1 cursor-pointer p-1">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

<!-- CONTROL JAVASCRIPT SWITCH TAMPILAN MENU -->
<script>
function switchSection(sectionId) {
    document.getElementById('mainAccountMenu').classList.add('hidden');
    document.getElementById('profileSection').classList.add('hidden');
    document.getElementById('addressSection').classList.add('hidden');

    const target = document.getElementById(sectionId);
    if(target) target.classList.remove('hidden');
}

function showMainMenu() {
    document.getElementById('profileSection').classList.add('hidden');
    document.getElementById('addressSection').classList.add('hidden');
    document.getElementById('mainAccountMenu').classList.remove('hidden');
}

document.addEventListener("DOMContentLoaded", function() {
  // Jika datang dari Checkout (ada pesan error/sesi redirect), langsung buka section Alamat
  @if(session('error') || session('checkout_redirect_url'))
      switchSection('addressSection');
  @endif

  const provSelect = document.getElementById('provinsi');
  const kotaSelect = document.getElementById('kota');
  const kecSelect  = document.getElementById('kecamatan');
  const kelSelect  = document.getElementById('kelurahan');

  if (!provSelect) return;

  // 1. AMBIL DATA PROVINSI
  fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
    .then(response => response.json())
    .then(provinces => {
      provSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
      provinces.forEach(prov => {
        let opt = document.createElement('option');
        opt.value = prov.name;     
        opt.dataset.id = prov.id;   
        opt.textContent = prov.name;
        provSelect.appendChild(opt);
      });
    })
    .catch(error => {
      console.error("Error:", error);
      provSelect.innerHTML = '<option value="">Gagal memuat data, coba refresh...</option>';
    });

  // 2. EVENT LISTENER: KOTA / KABUPATEN
  provSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const provId = selectedOption ? selectedOption.dataset.id : null;

    resetDropdown(kotaSelect, 'Pilih Kota/Kabupaten...');
    resetDropdown(kecSelect, 'Pilih Kecamatan...');
    resetDropdown(kelSelect, 'Pilih Kelurahan...');

    if (!provId) return;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
      .then(response => response.json())
      .then(regencies => {
        bukaKunciDropdown(kotaSelect);
        regencies.forEach(reg => {
          let opt = document.createElement('option');
          opt.value = reg.name;
          opt.dataset.id = reg.id;
          opt.textContent = reg.name;
          kotaSelect.appendChild(opt);
        });
      })
      .catch(err => console.error("Gagal memuat kota:", err));
  });

  // 3. EVENT LISTENER: KECAMATAN
  kotaSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const kotaId = selectedOption ? selectedOption.dataset.id : null;

    resetDropdown(kecSelect, 'Pilih Kecamatan...');
    resetDropdown(kelSelect, 'Pilih Kelurahan...');

    if (!kotaId) return;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaId}.json`)
      .then(response => response.json())
      .then(districts => {
        bukaKunciDropdown(kecSelect);
        districts.forEach(dist => {
          let opt = document.createElement('option');
          opt.value = dist.name;
          opt.dataset.id = dist.id;
          opt.textContent = dist.name;
          kecSelect.appendChild(opt);
        });
      })
      .catch(err => console.error("Gagal memuat kecamatan:", err));
  });

  // 4. EVENT LISTENER: KELURAHAN / DESA
  kecSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const kecId = selectedOption ? selectedOption.dataset.id : null;

    resetDropdown(kelSelect, 'Pilih Kelurahan...');

    if (!kecId) return;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecId}.json`)
      .then(response => response.json())
      .then(villages => {
        bukaKunciDropdown(kelSelect);
        villages.forEach(vill => {
          let opt = document.createElement('option');
          opt.value = vill.name;
          opt.textContent = vill.name;
          kelSelect.appendChild(opt);
        });
      })
      .catch(err => console.error("Gagal memuat kelurahan:", err));
  });

  // HELPER UTILITY
  function resetDropdown(element, placeholderText) {
    if(!element) return;
    element.innerHTML = `<option value="">${placeholderText}</option>`;
    element.setAttribute('disabled', 'true');
    element.classList.remove('bg-white');
    element.classList.add('bg-gray-50');
  }

  function bukaKunciDropdown(element) {
    if(!element) return;
    element.removeAttribute('disabled');
    element.classList.remove('bg-gray-50');
    element.classList.add('bg-white');
  }
});
</script>
@endsection