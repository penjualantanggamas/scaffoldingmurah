@extends('layouts.admin')

@section('title', 'Manajemen Pesanan Masuk | Admin Tangga Mas')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kelola Pesanan</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola konfirmasi pembayaran dan status pengiriman produk Tangga Mas.</p>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- NAVIGASI TAB KATEGORI STATUS PESANAN (GAYA SHOPEE DENGAN BADGE ANGKA) -->
    @php
        $currentStatus = request()->get('status', 'all');
    @endphp

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Tab Menu Filter -->
        <div class="flex items-center gap-1 overflow-x-auto border-b border-gray-200 px-4 pt-2 bg-gray-50/50 scrollbar-none text-xs font-semibold text-gray-600">
            
            <!-- 1. SEMUA PESANAN -->
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'all' ? 'border-[#1BBC9A] text-[#1BBC9A] font-bold' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                <i class="fa-solid fa-list-ul"></i> Semua
                @if(($counts['all'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-gray-200 text-gray-700">
                        {{ $counts['all'] }}
                    </span>
                @endif
            </a>

            <!-- 2. BELUM BAYAR -->
            <a href="{{ route('admin.orders.index', ['status' => 'unpaid']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'unpaid' ? 'border-amber-500 text-amber-600 font-bold' : 'border-transparent text-gray-500 hover:text-amber-600' }}">
                <i class="fa-solid fa-clock"></i> Belum Bayar
                @if(($counts['unpaid'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700">
                        {{ $counts['unpaid'] }}
                    </span>
                @endif
            </a>

            <!-- 3. PERLU VERIFIKASI (URGENT DAHULUKAN) -->
            <a href="{{ route('admin.orders.index', ['status' => 'verifikasi']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'verifikasi' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> Perlu Verifikasi 
                @if(($counts['verifikasi'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-600 text-white shadow-sm animate-pulse">
                        {{ $counts['verifikasi'] }}
                    </span>
                @endif
            </a>

            <!-- 4. PERLU DIKIRIM / DIPROSES -->
            <a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'diproses' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-indigo-600' }}">
                <i class="fa-solid fa-boxes-packing"></i> Perlu Dikirim
                @if(($counts['diproses'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700">
                        {{ $counts['diproses'] }}
                    </span>
                @endif
            </a>

            <!-- 5. DIKIRIM -->
            <a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'dikirim' ? 'border-teal-600 text-teal-600 font-bold' : 'border-transparent text-gray-500 hover:text-teal-600' }}">
                <i class="fa-solid fa-truck-fast"></i> Dikirim
                @if(($counts['dikirim'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-100 text-teal-700">
                        {{ $counts['dikirim'] }}
                    </span>
                @endif
            </a>

            <!-- 6. SELESAI -->
            <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'selesai' ? 'border-emerald-600 text-emerald-600 font-bold' : 'border-transparent text-gray-500 hover:text-emerald-600' }}">
                <i class="fa-solid fa-circle-check"></i> Selesai
                @if(($counts['selesai'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700">
                        {{ $counts['selesai'] }}
                    </span>
                @endif
            </a>

            <!-- 7. BATAL -->
            <a href="{{ route('admin.orders.index', ['status' => 'batal']) }}" 
               class="px-4 py-3 border-b-2 whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'batal' ? 'border-rose-600 text-rose-600 font-bold' : 'border-transparent text-gray-500 hover:text-rose-600' }}">
                <i class="fa-solid fa-circle-xmark"></i> Batal
                @if(($counts['batal'] ?? 0) > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700">
                        {{ $counts['batal'] }}
                    </span>
                @endif
            </a>

        </div>

        <!-- Tabel Pesanan Masuk -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Kode Transaksi</th>
                        <th class="py-3.5 px-4">Nama Customer</th>
                        <th class="py-3.5 px-4">Metode Kirim</th>
                        <th class="py-3.5 px-4">Total Biaya</th>
                        <th class="py-3.5 px-4">Status Bayar</th>
                        <th class="py-3.5 px-4">Status Pesanan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $o)
                        @php
                            $kodeTrans    = $o->kode_transaksi ?? $o->order_number ?? $o->no_pesanan ?? ('#' . $o->id);
                            $namaCustomer = $o->customer->nama_lengkap ?? $o->customer->name ?? $o->nama_penerima ?? 'Customer';
                            $metodeKirim  = $o->metode_pengiriman ?? $o->metode_kirim ?? 'Armada Gudang';
                            $grandTotal   = $o->grand_total ?? $o->total_harga ?? $o->total ?? 0;
                            $statusBayar  = $o->status_pembayaran ?? $o->payment_status ?? 'menunggu';
                            $statusOrder  = $o->status_pesanan ?? $o->status ?? 'pending';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-extrabold text-gray-900">{{ $kodeTrans }}</td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800">{{ $namaCustomer }}</td>
                            <td class="py-3.5 px-4 capitalize font-medium text-gray-600">
                                {{ str_replace('_', ' ', $metodeKirim) }}
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-[#1BBC9A] text-sm">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if(in_array($statusBayar, ['dibayar', 'paid', 'lunas']))
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-md font-bold text-[10px] inline-block uppercase">
                                        Dibayar
                                    </span>
                                @elseif(in_array($statusBayar, ['menunggu_konfirmasi_admin', 'verifikasi']))
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-md font-bold text-[10px] inline-block uppercase animate-pulse">
                                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Perlu Verifikasi
                                    </span>
                                @elseif(in_array($statusBayar, ['dibatalkan', 'cancelled', 'batal']))
                                    <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-md font-bold text-[10px] inline-block uppercase">
                                        Batal
                                    </span>
                                @else
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-md font-bold text-[10px] inline-block uppercase">
                                        Menunggu Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="uppercase font-bold text-[10px] text-gray-700 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                    {{ str_replace('_', ' ', $statusOrder) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.orders.show', $o->id) }}" 
                                   class="inline-flex items-center gap-1.5 bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-[11px] px-3.5 py-1.5 rounded-lg transition-colors shadow-sm">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="w-12 h-12 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="text-xs font-semibold">Belum ada pesanan pada kategori ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection