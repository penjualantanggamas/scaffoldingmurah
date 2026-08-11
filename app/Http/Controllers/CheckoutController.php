<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\Produk;
use App\Models\Cart;
use App\Models\ShippingRate;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Helper privat untuk mengambil isi keranjang belanja
     * (Mendukung Database untuk pengguna Login & Session untuk Guest)
     */
    private function getCartItemsData($customerId)
    {
        $cartItems = [];
        $subtotal = 0;
        $hasPreorder = false;

        if ($customerId) {
            // 1. JIKA LOGIN: Ambil dari Database MySQL
            $dbCarts = Cart::with('produk')->where('customer_id', $customerId)->get();

            foreach ($dbCarts as $c) {
                $produk = $c->produk;
                if (!$produk) continue;

                $harga = $c->harga;
                $jumlah = $c->jumlah;
                $itemSubtotal = $harga * $jumlah;
                $subtotal += $itemSubtotal;

                if (!empty($produk->is_preorder)) {
                    $hasPreorder = true;
                }

                $cartItems[] = [
                    'cart_id'        => $c->id,
                    'produk_id'      => $produk->id,
                    'nama_produk'    => $produk->nama_produk,
                    'ukuran_varian'  => $c->ukuran_varian,
                    'harga'          => $harga,
                    'harga_satuan'   => $harga,
                    'jumlah'         => $jumlah,
                    'subtotal'       => $itemSubtotal,
                    'gambar'         => $produk->gambar ?? $produk->foto,
                    'is_preorder'    => $produk->is_preorder ?? false,
                    'waktu_preorder' => $produk->waktu_preorder ?? null,
                ];
            }
        } else {
            // 2. JIKA GUEST: Ambil dari Session
            $cart = session('cart', []);
            foreach ($cart as $key => $item) {
                $produk = Produk::find($item['produk_id'] ?? $key);
                if (!$produk) continue;

                $harga = $item['harga'] ?? $produk->harga;
                $jumlah = $item['quantity'] ?? $item['jumlah'] ?? 1;
                $itemSubtotal = $harga * $jumlah;
                $subtotal += $itemSubtotal;

                if (!empty($produk->is_preorder)) {
                    $hasPreorder = true;
                }

                $cartItems[] = [
                    'cart_id'        => $key,
                    'produk_id'      => $produk->id,
                    'nama_produk'    => $produk->nama_produk,
                    'ukuran_varian'  => $item['ukuran_varian'] ?? $item['ukuran'] ?? null,
                    'harga'          => $harga,
                    'harga_satuan'   => $harga,
                    'jumlah'         => $jumlah,
                    'subtotal'       => $itemSubtotal,
                    'gambar'         => $item['gambar'] ?? $produk->gambar,
                    'is_preorder'    => $produk->is_preorder ?? false,
                    'waktu_preorder' => $produk->waktu_preorder ?? null,
                ];
            }
        }

        return [
            'cartItems'   => $cartItems,
            'subtotal'    => $subtotal,
            'hasPreorder' => $hasPreorder,
        ];
    }

    /**
     * 1. Menampilkan Halaman Checkout Pesanan (Hanya Produk Yang Dicentang)
     */
    public function index(Request $request)
    {
        // -----------------------------------------------------------------------
        // CEK MODE TRANSAKSI (WEBSITE VS WHATSAPP MAINTENANCE)
        // -----------------------------------------------------------------------
        $modeTransaksi = AppSetting::getValue('mode_transaksi', 'web');
        if ($modeTransaksi !== 'web') {
            $waAdmin = AppSetting::getValue('whatsapp_admin', '6281234567890');
            $pesanWA = urlencode("Halo Admin Tangga Mas Scaffolding, saya mau melakukan pemesanan material scaffolding/konstruksi via WhatsApp.");
            return redirect()->away("https://wa.me/{$waAdmin}?text={$pesanWA}");
        }

        $customer = Auth::guard('customer')->user();

        // 1. Cek Alamat Customer
        $addresses = CustomerAddress::where('customer_id', $customer->id)->get();

        if ($addresses->isEmpty()) {
            session(['checkout_redirect_url' => $request->fullUrl()]);
            return redirect()->route('customer.profile')
                ->with('error', 'Silakan daftarkan minimal 1 alamat pengiriman proyek terlebih dahulu sebelum melanjutkan checkout.');
        }

        $selectedAddress = $addresses->where('is_utama', true)->first() ?? $addresses->first();

        // 2. Cek Tarif Ongkir Armada
        $armadaFee = null;
        if ($selectedAddress && !empty($selectedAddress->kota)) {
            $userKota = strtoupper(trim($selectedAddress->kota));
            $armadaRate = ShippingRate::where('is_aktif', true)
                ->where(function ($q) use ($userKota) {
                    $q->where('kota', $userKota)
                    ->orWhereRaw('LOWER(kota) LIKE ?', ['%' . strtolower($userKota) . '%']);
                })
                ->first();

            if ($armadaRate) {
                $armadaFee = (float) $armadaRate->biaya_pengiriman;
            }
        }

        // 3. Ambil Keranjang
        $cartData = $this->getCartItemsData($customer->id);
        $allCartItems = $cartData['cartItems'];

        if (empty($allCartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Filter Produk Terpilih
        $selectedIds = $request->query('selected_items', []);
        if (!empty($selectedIds)) {
            $selectedIds = array_map('strval', (array) $selectedIds);
            $cartItems = array_filter($allCartItems, function ($item) use ($selectedIds) {
                return in_array((string) $item['cart_id'], $selectedIds);
            });
        } else {
            $cartItems = $allCartItems;
        }

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal 1 produk yang ingin dibeli.');
        }

        // Hitung Subtotal & Preorder
        $subtotal = 0;
        $hasPreorder = false;
        foreach ($cartItems as $item) {
            $subtotal += $item['subtotal'];
            if (!empty($item['is_preorder'])) {
                $hasPreorder = true;
            }
        }

        // 4. GENERATE KODE UNIK VERIFIKASI (3 Digit Random: 100 - 999)
        $kodeUnik = rand(100, 999);

        return view('customer.checkout.index', compact(
            'customer', 
            'addresses', 
            'selectedAddress', 
            'cartItems', 
            'subtotal', 
            'hasPreorder',
            'armadaFee',
            'kodeUnik'
        ));
    }

    /**
     * 2. Memproses Pembuatan Pesanan Baru
     */
    public function store(Request $request)
    {
        // -----------------------------------------------------------------------
        // CEK MODE TRANSAKSI (WEBSITE VS WHATSAPP MAINTENANCE)
        // -----------------------------------------------------------------------
        $modeTransaksi = AppSetting::getValue('mode_transaksi', 'web');
        if ($modeTransaksi !== 'web') {
            $waAdmin = AppSetting::getValue('whatsapp_admin', '6281234567890');
            $pesanWA = urlencode("Halo Admin Tangga Mas Scaffolding, saya mau melakukan pemesanan material scaffolding/konstruksi via WhatsApp.");
            return redirect()->away("https://wa.me/{$waAdmin}?text={$pesanWA}");
        }

        $customer = Auth::guard('customer')->user();

        $request->validate([
            'customer_address_id' => 'required|exists:customer_addresses,id',
            'metode_pengiriman'   => 'required|in:ambil_sendiri,ekspedisi,armada_gudang',
            'catatan_pembeli'     => 'nullable|string|max:500',
            'cart_ids'            => 'required|array',
            'kode_unik'           => 'nullable|integer|min:100|max:999',
        ]);

        $address = CustomerAddress::where('id', $request->customer_address_id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Validasi Biaya Pengiriman
        $biayaPengiriman = 0;
        if ($request->metode_pengiriman === 'armada_gudang') {
            $userKota = strtoupper(trim($address->kota));
            $armadaRate = ShippingRate::where('is_aktif', true)
                ->where(function ($q) use ($userKota) {
                    $q->where('kota', $userKota)
                    ->orWhereRaw('LOWER(kota) LIKE ?', ['%' . strtolower($userKota) . '%']);
                })->first();

            if (!$armadaRate) {
                return back()->with('error', 'Metode pengiriman Armada Gudang tidak menjangkau kota Anda.');
            }

            $biayaPengiriman = (float) $armadaRate->biaya_pengiriman;
        }

        $cartData = $this->getCartItemsData($customer->id);
        $allCartItems = $cartData['cartItems'];

        $purchasedIds = $request->input('cart_ids', []);
        $itemsData = array_filter($allCartItems, function ($item) use ($purchasedIds) {
            return in_array((string) $item['cart_id'], array_map('strval', $purchasedIds));
        });

        if (empty($itemsData)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang diproses.');
        }

        $subtotal = array_sum(array_column($itemsData, 'subtotal'));
        
        // AMBIL KODE UNIK DARI FORM CHECKOUT (Jika tidak ada, buat acak baru)
        $kodeUnik = (int) $request->input('kode_unik', rand(100, 999));

        // GRAND TOTAL = Subtotal + Biaya Pengiriman + Kode Unik
        $grandTotal = $subtotal + $biayaPengiriman + $kodeUnik;

        // KODE TRANSAKSI & EXPIRED DATE (24 JAM DARI SEKARANG)
        $today = date('Ymd');
        $countToday = Order::whereDate('created_at', date('Y-m-d'))->count() + 1;
        $kodeTransaksi = 'TM-' . $today . '-' . sprintf('%04d', $countToday);
        $expiredAt = now()->addHours(24);

        DB::beginTransaction();
        try {
            // 1. Simpan Header Order
            $order = Order::create([
                'kode_transaksi'      => $kodeTransaksi,
                'customer_id'         => $customer->id,
                'customer_address_id' => $request->customer_address_id,
                'metode_pengiriman'   => $request->metode_pengiriman,
                'biaya_pengiriman'    => $biayaPengiriman,
                'kode_unik'           => $kodeUnik,
                'metode_pembayaran'   => 'transfer_bank',
                'subtotal_produk'     => $subtotal,
                'grand_total'         => $grandTotal,
                'status_pembayaran'   => 'menunggu_pembayaran',
                'status_pesanan'      => 'pending',
                'catatan_pembeli'     => $request->catatan_pembeli,
                'expired_at'          => $expiredAt,
            ]);

            // 2. Simpan Item Detail Pesanan
            foreach ($itemsData as $itemData) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'produk_id'     => $itemData['produk_id'],
                    'nama_produk'   => $itemData['nama_produk'],
                    'ukuran_varian' => $itemData['ukuran_varian'],
                    'harga_satuan'  => $itemData['harga_satuan'],
                    'jumlah'        => $itemData['jumlah'],
                    'subtotal'      => $itemData['subtotal'],
                    'is_preorder'   => $itemData['is_preorder'],
                    'waktu_preorder'=> $itemData['waktu_preorder'],
                ]);
            }

            DB::commit();

            // 3. Hapus Item dari Cart
            if (Auth::guard('customer')->check()) {
                Cart::where('customer_id', $customer->id)->whereIn('id', $purchasedIds)->delete();
            } else {
                $sessionCart = session('cart', []);
                foreach ($purchasedIds as $pId) {
                    unset($sessionCart[$pId]);
                }
                session()->put('cart', $sessionCart);
            }

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan transfer sesuai nominal unik.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * 3. Membatalkan Pembelian 'Beli Sekarang' (Menghapus Item Temporer dari Cart)
     */
    public function cancelBuyNow(Request $request)
    {
        $cartId = $request->input('cart_id');

        if ($cartId) {
            if (Auth::guard('customer')->check()) {
                Cart::where('id', $cartId)
                    ->where('customer_id', Auth::guard('customer')->id())
                    ->delete();
            } else {
                $sessionCart = session('cart', []);
                if (isset($sessionCart[$cartId])) {
                    unset($sessionCart[$cartId]);
                    session()->put('cart', $sessionCart);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembelian langsung dibatalkan.'
        ]);
    }

    /**
     * 4. Halaman Ringkasan / Instruksi Pembayaran Transfer Bank
     */
    public function success($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::with(['items', 'address'])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return view('customer.checkout.success', compact('order'));
    }

    /**
     * 5. Memproses Upload Bukti Transfer dari Customer
     */
    public function uploadProof(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = 'bukti_' . $order->kode_transaksi . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/bukti_transfer'), $fileName);

            $order->update([
                'bukti_transfer'    => $fileName,
                'status_pembayaran' => 'menunggu_konfirmasi_admin',
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diunggah! Pesanan Anda sedang diverifikasi oleh admin.');
    }

    /**
     * 6. Menampilkan Daftar Pesanan Saya milik Customer (Disembunyikan jika Expired)
     */
    public function myOrders(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return view('customer.orders.index', [
                'isGuest' => true,
                'orders'  => collect([])
            ]);
        }

        $customerId = Auth::guard('customer')->id();
        $status = $request->query('status', 'all');

        $query = Order::with(['items.produk', 'address'])
            ->where('customer_id', $customerId)
            ->latest();

        // -----------------------------------------------------------------------
        // LOGIKA UTAMA: SEMBUNYIKAN PESANAN UNPAID YANG SUDAH EXPIRED
        // -----------------------------------------------------------------------
        $query->where(function ($q) {
            $q->whereNotIn('status_pembayaran', ['menunggu_pembayaran', 'unpaid', 'menunggu'])
              ->orWhere(function ($subQ) {
                  $subQ->whereIn('status_pembayaran', ['menunggu_pembayaran', 'unpaid', 'menunggu'])
                       ->where(function ($expQ) {
                           $expQ->whereNull('expired_at')
                                ->orWhere('expired_at', '>=', now());
                       });
              });
        });

        // FILTER TAB STATUS
        if ($status && $status !== 'all') {
            switch ($status) {
                case 'unpaid':
                    $query->whereIn('status_pembayaran', ['menunggu_pembayaran', 'unpaid', 'menunggu']);
                    break;

                case 'verifikasi':
                    $query->whereIn('status_pembayaran', ['menunggu_konfirmasi_admin', 'verifikasi']);
                    break;

                case 'processing':
                    $query->whereIn('status_pesanan', ['diproses', 'sedang_dikemas', 'processing'])
                          ->whereNotIn('status_pembayaran', ['menunggu_pembayaran', 'menunggu_konfirmasi_admin']);
                    break;

                case 'shipped':
                    $query->whereIn('status_pesanan', ['dikirim', 'shipped']);
                    break;

                case 'completed':
                    $query->whereIn('status_pesanan', ['selesai', 'completed']);
                    break;

                case 'cancelled':
                    $query->where(function ($q) {
                        $q->whereIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])
                          ->orWhereIn('status_pembayaran', ['dibatalkan', 'cancelled', 'batal']);
                    });
                    break;
            }
        }

        $orders = $query->paginate(10);

        return view('customer.orders.index', compact('orders', 'status'));
    }

    /**
     * 7. Menampilkan Detail Satu Pesanan (Proteksi Akses Jika Expired)
     */
    public function showOrder($id)
    {
        $customerId = Auth::guard('customer')->id() ?? auth('customer')->id();

        $order = Order::with(['items.produk', 'address'])
            ->where('customer_id', $customerId)
            ->where('id', $id)
            ->firstOrFail();

        // Cek jika pesanan belum dibayar dan sudah melewati batas waktu (Expired)
        $isUnpaid = in_array($order->status_pembayaran, ['menunggu_pembayaran', 'unpaid', 'menunggu']);
        if ($isUnpaid && !empty($order->expired_at) && \Carbon\Carbon::parse($order->expired_at)->isPast()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Batas waktu pembayaran pesanan ini telah habis.');
        }

        return view('customer.orders.show', compact('order'));
    }
}