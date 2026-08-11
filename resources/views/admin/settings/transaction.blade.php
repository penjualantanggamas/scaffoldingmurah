@extends('layouts.admin')

@section('title', 'Pengaturan Mode Transaksi | Tangga Mas Admin')

@section('content')
<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Pengaturan Mode Transaksi Website</h1>
                <p class="text-xs text-gray-500 mt-0.5">Kelola apakah customer bisa checkout langsung di website atau dialihkan ke WhatsApp Admin.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.transaction.update') }}" method="POST" class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-6">
            @csrf
            @method('PATCH')

            <!-- TOGGLE SWITCH MODE TRANSAKSI -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="space-y-0.5 max-w-md">
                    <label class="text-sm font-bold text-gray-800 block">Transaksi Langsung di Website</label>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Jika <strong>AKTIF</strong>, customer checkout melalui sistem website.<br>
                        Jika <strong>NON-AKTIF</strong> (Mode Maintenance), tombol checkout dialihkan langsung ke WhatsApp Admin.
                    </p>
                </div>

                <!-- Custom Tailwind Toggle Switch -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="mode_transaksi" value="1" class="sr-only peer" {{ $modeTransaksi === 'web' ? 'checked' : '' }}>
                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#1BBC9A]"></div>
                </label>
            </div>

            <!-- STATUS BADGE INFORMATION -->
            <div class="p-3.5 rounded-xl border text-xs font-bold flex items-center gap-2 {{ $modeTransaksi === 'web' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                <i class="fa-solid {{ $modeTransaksi === 'web' ? 'fa-globe text-emerald-600' : 'fa-whatsapp text-amber-600' }} text-lg"></i>
                <span>Status Saat Ini: <strong>{{ $modeTransaksi === 'web' ? 'Website Checkout Langsung (ONLINE)' : 'Mode Katalog - Alihkan ke WhatsApp Admin (MAINTENANCE)' }}</strong></span>
            </div>

            <!-- INPUT NOMOR WHATSAPP ADMIN -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor WhatsApp Admin (Format 62...)</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-gray-400"><i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i></span>
                    <input type="text" name="whatsapp_admin" value="{{ $whatsappAdmin }}" required placeholder="6281234567890" 
                           class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:outline-none focus:border-[#1BBC9A]">
                </div>
                <span class="text-[10px] text-gray-400 mt-1 block">*Nomor ini akan menerima pesan pesanan otomatis dari customer saat mode website non-aktif.</span>
            </div>

            <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
            </button>
        </form>

    </div>
</div>
@endsection