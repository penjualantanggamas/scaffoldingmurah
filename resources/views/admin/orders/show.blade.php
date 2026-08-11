@extends('layouts.admin')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    
    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
        <span class="text-xs font-extrabold text-gray-400 uppercase">Detail Transaksi</span>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-150 shadow-sm p-6 space-y-6">
        
        <!-- Header Info Kode & Status -->
        <div class="border-b border-gray-100 pb-4 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Kode: {{ $order->kode_transaksi }}</h1>
                <p class="text-xs text-gray-400">Tanggal Transaksi: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase px-3 py-1 bg-gray-100 rounded-full border border-gray-200">
                    Status Pesanan: {{ str_replace('_', ' ', $order->status_pesanan) }}
                </span>
                <span class="text-xs font-bold uppercase px-3 py-1 bg-emerald-50 text-[#1BBC9A] rounded-full border border-emerald-200">
                    Pembayaran: {{ str_replace('_', ' ', $order->status_pembayaran) }}
                </span>
            </div>
        </div>

        <!-- 2 Kolom Data Customer & Alamat -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="bg-gray-50 p-4 rounded-xl space-y-1">
                <h3 class="font-bold text-gray-700 uppercase mb-2">Informasi Pembeli</h3>
                <p><strong>Nama:</strong> {{ $order->customer->nama_lengkap ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email ?? '-' }}</p>
                <p><strong>WhatsApp:</strong> {{ $order->customer->no_hp ?? '-' }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-xl space-y-1">
                <h3 class="font-bold text-gray-700 uppercase mb-2">Alamat Pengiriman Proyek</h3>
                @if($order->address)
                    <p class="font-bold text-gray-900">{{ $order->address->label_alamat }}</p>
                    <p>{{ $order->address->detail_jalan }}</p>
                    <p>Kel. {{ $order->address->kelurahan }}, Kec. {{ $order->address->kecamatan }}</p>
                    <p>{{ $order->address->kota }}, {{ $order->address->provinsi }} - {{ $order->address->kode_pos }}</p>
                @else
                    <p class="text-gray-400">Alamat tidak ditemukan.</p>
                @endif
            </div>
        </div>

        <!-- Table Item Pesanan -->
        <div>
            <h3 class="text-xs font-bold text-gray-800 uppercase mb-3">Item Material Dipesan</h3>
            <div class="border border-gray-150 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-150 font-bold text-gray-600">
                        <tr>
                            <th class="p-3 w-1/2">Produk</th>
                            <th class="p-3 text-center">Harga Satuan</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            @php
                                $prod = $item->produk ?? $item->product ?? null;
                                $rawGambar = $item->gambar ?? $item->foto ?? ($prod->foto ?? $prod->gambar ?? null);
                                $imgUrl = asset('images/logotm.png');
                                if (!empty($rawGambar)) {
                                    $imgUrl = str_contains($rawGambar, '/') ? asset($rawGambar) : asset('images/products/' . $rawGambar);
                                }
                            @endphp
                            <tr>
                                <td class="p-3">
                                    <div class="flex items-center gap-3">
                                        <!-- Gambar Produk -->
                                        <img src="{{ $imgUrl }}" 
                                             alt="{{ $item->nama_produk }}" 
                                             onerror="this.onerror=null; this.src='{{ asset('images/logotm.png') }}';" 
                                             class="w-10 h-10 object-cover rounded-lg border border-gray-200 shrink-0 bg-gray-50">
                                        
                                        <!-- Nama & Varian Produk Ringkas -->
                                        <div class="min-w-0 max-w-[220px] sm:max-w-[280px]">
                                            <span class="font-semibold text-gray-800 block truncate leading-snug" title="{{ $item->nama_produk }}">
                                                {{ $item->nama_produk }}
                                            </span>
                                            @if($item->ukuran_varian)
                                                <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5 border border-gray-200 font-medium">
                                                    Varian: {{ $item->ukuran_varian }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-center whitespace-nowrap">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="p-3 text-center whitespace-nowrap">{{ $item->jumlah }} Pcs</td>
                                <td class="p-3 text-right font-bold text-[#1BBC9A] whitespace-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BUKTI TRANSFER & TOMBOL EKSEKUSI ADMIN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start border-t border-gray-150 pt-6">
            
            <!-- Foto Bukti Transfer -->
            <div>
                <h3 class="text-xs font-bold text-gray-800 uppercase mb-2">Bukti Transfer Customer</h3>
                @if($order->bukti_transfer)
                    <a href="{{ asset('uploads/bukti_transfer/' . $order->bukti_transfer) }}" target="_blank" class="block">
                        <img src="{{ asset('uploads/bukti_transfer/' . $order->bukti_transfer) }}" class="w-full max-h-64 object-cover rounded-xl border border-gray-200 shadow-sm hover:opacity-90 transition-opacity">
                    </a>
                    <p class="text-[10px] text-gray-400 mt-1">Klik foto untuk melihat ukuran penuh.</p>
                @else
                    <div class="p-6 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center text-xs text-gray-400">
                        Customer belum mengunggah foto bukti transfer.
                    </div>
                @endif
            </div>

            <!-- Tombol Opsi Konfirmasi Pesanan -->
            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-4">
                <h3 class="text-xs font-bold text-gray-800 uppercase">Konfirmasi Status Pesanan</h3>
                <p class="text-xs text-gray-500">Pilih tindakan di bawah ini untuk mengubah status transaksi secara resmi:</p>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <!-- Opsi Dinamis Sesuai Status Pesanan Saat Ini -->
                    @if(in_array($order->status_pesanan, ['pending', 'menunggu_konfirmasi']))
                        <!-- Status 1: Konfirmasi Lunas & Proses Pesanan -->
                        <button type="submit" name="aksi" value="proses" onclick="return confirm('Proses pesanan ini? Status akan diperbarui menjadi Diproses.')"
                                class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-xs py-3 px-4 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-box font-semibold"></i> Konfirmasi Pembayaran & Diproses
                        </button>
                    @elseif(in_array($order->status_pesanan, ['diproses', 'sedang_dikemas', 'processing']))
                        <!-- Status 2: Lanjut ke Dikirim -->
                        <button type="submit" name="aksi" value="kirim" onclick="return confirm('Kirim pesanan ini? Status pesanan akan diperbarui menjadi Dikirim.')"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3 px-4 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-truck-fast"></i> Kirim Pesanan (Perbarui ke Dikirim)
                        </button>
                    @elseif(in_array($order->status_pesanan, ['dikirim', 'shipped']))
                        <!-- Status 3: Tandai Selesai -->
                        <button type="submit" name="aksi" value="selesai" onclick="return confirm('Tandai pesanan ini selesai?')"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 px-4 rounded-xl shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Pesanan Selesai
                        </button>
                    @endif

                    @if(!in_array($order->status_pesanan, ['selesai', 'completed', 'dibatalkan', 'cancelled']))
                        <!-- Tombol Batalkan Pesanan -->
                        <button type="submit" name="aksi" value="batalkan" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                                class="w-full bg-red-600 hover:bg-red-800 text-white font-bold text-xs py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-circle-xmark text-sm"></i> Batalkan Pesanan
                        </button>
                    @endif
                </form>
            </div>

        </div>

    </div>
</div>
@endsection