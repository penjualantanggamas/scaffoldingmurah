<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * 1. Menampilkan Halaman Form Login Customer
     */
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    /**
     * 2. Memproses Aksi Otentikasi Masuk Sesi Akun (Login) + SINKRONISASI KERANJANG
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $customer = Auth::guard('customer')->user();

            // =========================================================================
            // PEMINDAHAN KERANJANG BELANJA DARI SESSION KE DATABASE MYSQL
            // =========================================================================
            $sessionCart = session('cart', []);

            if (!empty($sessionCart)) {
                foreach ($sessionCart as $item) {
                    $productId = $item['produk_id'] ?? null;
                    if (!$productId) continue;

                    $ukuranVarian = $item['ukuran_varian'] ?? null;
                    $jumlah = $item['quantity'] ?? $item['jumlah'] ?? 1;
                    $harga = $item['harga'] ?? 0;

                    // Cek apakah item sudah ada di DB keranjang customer ini
                    $cartExist = Cart::where('customer_id', $customer->id)
                        ->where('produk_id', $productId)
                        ->where('ukuran_varian', $ukuranVarian)
                        ->first();

                    if ($cartExist) {
                        $cartExist->increment('jumlah', $jumlah);
                    } else {
                        Cart::create([
                            'customer_id'   => $customer->id,
                            'produk_id'     => $productId,
                            'ukuran_varian' => $ukuranVarian,
                            'jumlah'        => $jumlah,
                            'harga'         => $harga,
                        ]);
                    }
                }
                
                // Kosongkan session keranjang setelah berhasil dipindahkan ke Database
                session()->forget('cart');
            }
            // =========================================================================

            return redirect('/')->with('success', 'Selamat datang di Portal Mitra Tangga Mas!');
        }

        return back()->withErrors([
            'email' => 'Alamat email atau kata sandi yang Anda masukkan tidak terdaftar.',
        ])->onlyInput('email');
    }

    /**
     * 3. Menampilkan Halaman Form Registrasi Akun Kontraktor Baru
     */
    public function showRegister()
    {
        return view('customer.auth.register');
    }

    /**
     * 4. Memproses Pendaftaran Data Akun Customer B2B Baru ke Database
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:customers,email',
            'no_hp'        => 'required|string|max:20',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'password'     => Hash::make($request->password), // Memastikan password di-hash secara aman
        ]);

        Auth::guard('customer')->login($customer);

        // Jika ada keranjang session saat register, langsung disinkronkan ke DB
        $sessionCart = session('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $item) {
                $productId = $item['produk_id'] ?? null;
                if (!$productId) continue;

                Cart::create([
                    'customer_id'   => $customer->id,
                    'produk_id'     => $productId,
                    'ukuran_varian' => $item['ukuran_varian'] ?? null,
                    'jumlah'        => $item['quantity'] ?? $item['jumlah'] ?? 1,
                    'harga'         => $item['harga'] ?? 0,
                ]);
            }
            session()->forget('cart');
        }

        return redirect('/')->with('success', 'Akun kemitraan Tangga Mas Anda berhasil didaftarkan!');
    }

    /**
     * 5. Memproses Aksi Keluar Sesi Akun (Logout)
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil keluar dari sesi akun mitra.');
    }

    /**
     * 6. Menampilkan Halaman Profil Customer
     */
    public function showProfile()
    {
        $customer = Customer::with('addresses')->find(Auth::guard('customer')->id());
        return view('customer.profile', compact('customer'));
    }

    /**
     * 7. Memproses Pembaruan Data Profil & Akun Perusahaan
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'required|string|max:20',
            'password'     => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp'        => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        Customer::where('id', $customer->id)->update($data);

        return back()->with('success', 'Informasi profil dasar Anda berhasil diperbarui!');
    }
    
    /**
     * 8. Menyimpan Alamat Proyek Pengiriman Baru
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'label_alamat' => 'required|string|max:100',
            'detail_jalan' => 'required|string',
            'provinsi'     => 'required|string',
            'kota'         => 'required|string',
            'kecamatan'    => 'required|string',
            'kelurahan'    => 'required|string',
            'kode_pos'     => 'required|string|max:10',
        ]);

        $customerId = Auth::guard('customer')->id();

        $isUtama = $request->has('is_utama') ? true : false;
        if ($isUtama) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_utama' => false]);
        }

        $count = CustomerAddress::where('customer_id', $customerId)->count();
        if ($count === 0) {
            $isUtama = true;
        }

        CustomerAddress::create([
            'customer_id'  => $customerId,
            'label_alamat' => $request->label_alamat,
            'detail_jalan' => $request->detail_jalan,
            'provinsi'     => $request->provinsi,
            'kota'         => $request->kota,
            'kecamatan'    => $request->kecamatan,
            'kelurahan'    => $request->kelurahan,
            'kode_pos'     => $request->kode_pos,
            'is_utama'     => $isUtama,
        ]);

        // =========================================================================
        // REDIRECT OTOMATIS KEMBALI KE CHECKOUT JIKA ADA SESI CHECKOUT TERGANTUNG
        // =========================================================================
        if (session()->has('checkout_redirect_url')) {
            $redirectUrl = session()->pull('checkout_redirect_url'); // Ambil & hapus session

            return redirect($redirectUrl)->with('success', 'Alamat pengiriman berhasil disimpan! Silakan lanjutkan proses checkout.');
        }

        return back()->with('success', 'Alamat proyek baru berhasil ditambahkan!');
    }
    
    /**
     * 9. Menghapus Alamat Proyek
     */
    public function destroyAddress($id)
    {
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();
        
        $address->delete();

        // Proteksi: Jika yang dihapus alamat utama, jadikan alamat tersisa yang lain sebagai utama
        $checkUtama = CustomerAddress::where('customer_id', Auth::guard('customer')->id())
            ->where('is_utama', true)
            ->count();
        
        if ($checkUtama === 0) {
            $nextAddress = CustomerAddress::where('customer_id', Auth::guard('customer')->id())->first();
            if ($nextAddress) {
                $nextAddress->update(['is_utama' => true]);
            }
        }

        return back()->with('success', 'Alamat proyek berhasil dihapus.');
    }

    /**
     * Menjadikan Alamat Terpilih sebagai Alamat Utama
     */
    public function setPrimaryAddress($id)
    {
        $customerId = Auth::guard('customer')->id();

        // 1. Reset semua alamat customer ini menjadi bukan utama (false)
        CustomerAddress::where('customer_id', $customerId)->update(['is_utama' => false]);

        // 2. Set alamat terpilih menjadi utama (true)
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        $address->update(['is_utama' => true]);

        // 3. Jika ada sesi redirect checkout, langsung kembali ke checkout
        if (session()->has('checkout_redirect_url')) {
            $redirectUrl = session()->pull('checkout_redirect_url');
            return redirect($redirectUrl)->with('success', 'Alamat utama berhasil diubah! Silakan lanjutkan checkout.');
        }

        return back()->with('success', 'Alamat utama pengiriman berhasil diperbarui!');
    }
}