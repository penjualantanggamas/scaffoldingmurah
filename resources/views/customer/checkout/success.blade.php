@extends('layouts.frontend')

@section('title', 'Instruksi Pembayaran & Konfirmasi | Tangga Mas')

@section('content')
<div class="bg-gray-100 min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <div class="bg-white rounded-2xl shadow-xl border border-gray-150 overflow-hidden p-6 sm:p-10 space-y-6">
            
            <!-- Notifikasi Sukses -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Icon Header Status -->
            <div class="text-center space-y-2">
                <div class="w-16 h-16 bg-emerald-100 text-[#1BBC9A] rounded-full flex items-center justify-center mx-auto text-3xl shadow-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Pesanan Berhasil Dibuat</span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">Kode Transaksi: {{ $order->kode_transaksi }}</h1>
                
                <!-- Badge Status Pembayaran -->
                <div class="pt-1">
                    @if($order->status_pembayaran == 'menunggu_pembayaran')
                        <span class="bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold px-3 py-1 rounded-full">
                            <i class="fa-solid fa-clock mr-1"></i> Menunggu Upload Bukti Transfer
                        </span>
                    @elseif($order->status_pembayaran == 'menunggu_konfirmasi_admin')
                        <span class="bg-blue-100 text-blue-800 border border-blue-300 text-xs font-bold px-3 py-1 rounded-full">
                            <i class="fa-solid fa-[#1BBC9A] fa-spinner animate-spin mr-1"></i> Bukti Terkirim - Menunggu Verifikasi Admin
                        </span>
                    @elseif($order->status_pembayaran == 'dibayar')
                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-bold px-3 py-1 rounded-full">
                            <i class="fa-solid fa-check-double mr-1"></i> Pembayaran Terverifikasi
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 border border-red-300 text-xs font-bold px-3 py-1 rounded-full">
                            Dibatalkan
                        </span>
                    @endif
                </div>
            </div>

            <!-- Box Total Tagihan (Dengan Catatan Kode Unik) -->
            <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-4 text-center text-xs space-y-1">
                <p class="text-gray-600 font-medium">Total Nominal Transfer (Termasuk Kode Unik):</p>
                <p class="text-2xl font-extrabold text-[#1BBC9A]">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                <p class="text-[10px] text-amber-700 font-semibold">*Pastikan mentransfer pas hingga 3 digit terakhir untuk kemudahan verifikasi.</p>
            </div>

            <!-- Detail Rekening Bank -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs space-y-3">
                <p class="font-bold text-gray-800">Transfer ke Rekening Resmi PT Tangga Mas Jaya Makmur:</p>
                <div class="grid grid-cols-1 sm:grid gap-2">
                    <div class="p-3 bg-white rounded-lg border border-gray-200">
                        <span class="font-bold text-blue-800 block">BCA: 123-456-7890</span>
                        <span class="text-[10px] text-gray-500">a.n PT Tangga Mas Jaya Makmur</span>
                    </div>
                </div>
            </div>

            <!-- FORM UPLOAD BUKTI TRANSFER & KONFIRMASI PESANAN -->
            <div class="border-t border-gray-150 pt-5 space-y-4">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider text-center">Konfirmasi Pembayaran</h3>
                
                @if($order->bukti_transfer)
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center space-y-2">
                        <p class="text-xs text-gray-500 font-medium">Bukti transfer yang diunggah:</p>
                        <a href="{{ asset('uploads/bukti_transfer/' . $order->bukti_transfer) }}" target="_blank" class="inline-block">
                            <img src="{{ asset('uploads/bukti_transfer/' . $order->bukti_transfer) }}" class="h-32 object-cover rounded-lg border mx-auto shadow-sm hover:opacity-90 transition-opacity">
                        </a>
                        <p class="text-[10px] text-gray-400">Klik gambar untuk melihat ukuran penuh.</p>
                    </div>
                @endif

                <form action="{{ route('checkout.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            {{ $order->bukti_transfer ? 'Ganti File Bukti Transfer (Opsional)' : 'Unggah Foto / Screenshot Bukti Transfer' }}
                        </label>
                        <input type="file" name="bukti_transfer" accept="image/*" required
                               class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1BBC9A] file:text-white hover:file:bg-[#0C5646] cursor-pointer bg-gray-50 border border-gray-200 rounded-xl p-1">
                        @error('bukti_transfer')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-extrabold text-sm py-3.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i> {{ $order->bukti_transfer ? 'Perbarui Bukti Transfer' : 'Konfirmasi Pesanan' }}
                    </button>
                </form>
            </div>

            <div class="text-center pt-2">
                <a href="{{ url('/') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">
                    Kembali ke Beranda Utama
                </a>
            </div>

        </div>

    </div>
</div>
@endsection