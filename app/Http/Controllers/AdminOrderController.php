<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * 1. Menampilkan Daftar Semua Pesanan Masuk (Berdasarkan Kategori Tab Status)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        // AMBIL JUMLAH KONTAN (COUNTER BADGE) UNTUK MASING-MASING TAB
        $counts = [
            'all' => Order::count(),
            'unpaid' => Order::whereIn('status_pembayaran', ['menunggu_pembayaran', 'unpaid', 'menunggu'])->count(),
            'verifikasi' => Order::whereIn('status_pembayaran', ['menunggu_konfirmasi_admin', 'verifikasi'])->count(),
            'diproses' => Order::whereIn('status_pembayaran', ['dibayar', 'paid', 'lunas'])
                ->whereIn('status_pesanan', ['pending', 'diproses', 'sedang_dikemas', 'processing'])->count(),
            'dikirim' => Order::whereIn('status_pesanan', ['dikirim', 'shipped'])->count(),
            'selesai' => Order::whereIn('status_pesanan', ['selesai', 'completed'])->count(),
            'batal' => Order::where(function ($q) {
                $q->whereIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])
                  ->orWhereIn('status_pembayaran', ['dibatalkan', 'cancelled', 'batal']);
            })->count(),
        ];

        $query = Order::with(['customer', 'address'])->latest();

        // LOGIKA FILTER KATEGORI TAB PESANAN
        if ($status && $status !== 'all') {
            switch ($status) {
                case 'unpaid':
                    $query->whereIn('status_pembayaran', ['menunggu_pembayaran', 'unpaid', 'menunggu']);
                    break;

                case 'verifikasi':
                    $query->whereIn('status_pembayaran', ['menunggu_konfirmasi_admin', 'verifikasi']);
                    break;

                case 'diproses':
                    $query->whereIn('status_pembayaran', ['dibayar', 'paid', 'lunas'])
                          ->whereIn('status_pesanan', ['pending', 'diproses', 'sedang_dikemas', 'processing']);
                    break;

                case 'dikirim':
                    $query->whereIn('status_pesanan', ['dikirim', 'shipped']);
                    break;

                case 'selesai':
                    $query->whereIn('status_pesanan', ['selesai', 'completed']);
                    break;

                case 'batal':
                    $query->where(function ($q) {
                        $q->whereIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])
                          ->orWhereIn('status_pembayaran', ['dibatalkan', 'cancelled', 'batal']);
                    });
                    break;
            }
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders', 'status', 'counts'));
    }

    /**
     * 2. Menampilkan Rincian Detail Pesanan & Bukti Transfer
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'address', 'items.produk'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * 3. Memproses Aksi Status Pesanan (Proses Pesanan / Batalkan Pesanan)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $aksi = $request->input('aksi');

        switch ($aksi) {
            case 'proses':
                $order->update([
                    'status_pembayaran' => 'lunas',
                    'status_pesanan'    => 'diproses',
                ]);
                $pesan = 'Pesanan berhasil dikonfirmasi dan status diperbarui menjadi Diproses!';
                break;

            case 'kirim':
                $order->update([
                    'status_pesanan' => 'dikirim',
                ]);
                $pesan = 'Status pesanan berhasil diperbarui menjadi Dikirim!';
                break;

            case 'selesai':
                $order->update([
                    'status_pesanan' => 'selesai',
                ]);
                $pesan = 'Status pesanan telah diselesaikan!';
                break;

            case 'batalkan':
                $order->update([
                    'status_pembayaran' => 'dibatalkan',
                    'status_pesanan'    => 'dibatalkan',
                ]);
                $pesan = 'Pesanan telah dibatalkan.';
                break;

            default:
                return back()->with('error', 'Aksi tidak valid.');
        }

        return back()->with('success', $pesan);
    }
}