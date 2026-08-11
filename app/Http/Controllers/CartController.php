<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Menampilkan Halaman Keranjang
    public function index()
    {
        $cartItems = [];
        $subtotal = 0;

        if (Auth::guard('customer')->check()) {
            // JIKA LOGIN: Ambil data dari Database MySQL
            $customerId = Auth::guard('customer')->id();
            $dbCarts = Cart::with('produk')->where('customer_id', $customerId)->get();

            foreach ($dbCarts as $c) {
                $harga = $c->harga;
                $qty = $c->jumlah;
                $itemSubtotal = $harga * $qty;
                $subtotal += $itemSubtotal;

                $cartItems[$c->id] = [
                    'id'            => $c->id,
                    'produk_id'     => $c->produk_id,
                    'nama_produk'   => $c->produk->nama_produk ?? 'Produk',
                    'ukuran_varian' => $c->ukuran_varian,
                    'quantity'      => $qty,
                    'harga'         => $harga,
                    'subtotal'      => $itemSubtotal,
                    'gambar'        => $c->produk->foto ?? $c->produk->gambar ?? 'logotm.png',
                    'slug'          => $c->produk->slug ?? '#',
                ];
            }
        } else {
            // JIKA GUEST: Ambil data dari Session Browser
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $key => $item) {
                $produk = Produk::find($item['produk_id'] ?? $key);
                if (!$produk) continue;

                $harga = $item['harga'] ?? $produk->harga;
                $qty = $item['quantity'] ?? $item['jumlah'] ?? 1;
                $itemSubtotal = $harga * $qty;
                $subtotal += $itemSubtotal;

                $cartItems[$key] = [
                    'id'            => $key,
                    'produk_id'     => $produk->id,
                    'nama_produk'   => $produk->nama_produk ?? ($item['nama_produk'] ?? 'Produk'),
                    'ukuran_varian' => $item['ukuran_varian'] ?? null,
                    'quantity'      => $qty,
                    'harga'         => $harga,
                    'subtotal'      => $itemSubtotal,
                    'gambar'        => $produk->foto ?? $produk->gambar ?? ($item['gambar'] ?? 'logotm.png'),
                    'slug'          => $produk->slug ?? ($item['slug'] ?? '#'),
                ];
            }
        }

        return view('cart', compact('cartItems', 'subtotal'));
    }

    // 2. Menambahkan Produk ke Dalam Keranjang (FIX BUG FIX KLIK PERTAMA)
    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);
        $ukuranVarian = $request->input('ukuran_varian', null);
        
        $insertedCartId = null;

        if (Auth::guard('customer')->check()) {
            // JIKA LOGIN: Simpan/Increment ke Database
            $customerId = Auth::guard('customer')->id();

            $cartItem = Cart::where('customer_id', $customerId)
                ->where('produk_id', $produk->id)
                ->where(function($q) use ($ukuranVarian) {
                    if ($ukuranVarian) {
                        $q->where('ukuran_varian', $ukuranVarian);
                    } else {
                        $q->whereNull('ukuran_varian')->orWhere('ukuran_varian', '');
                    }
                })
                ->first();

            if ($cartItem) {
                $cartItem->increment('jumlah', $quantity);
                $insertedCartId = $cartItem->id;
            } else {
                $newCart = Cart::create([
                    'customer_id'   => $customerId,
                    'produk_id'     => $produk->id,
                    'ukuran_varian' => $ukuranVarian,
                    'jumlah'        => $quantity,
                    'harga'         => $produk->harga,
                ]);
                $insertedCartId = $newCart->id;
            }

            $cartCount = Cart::where('customer_id', $customerId)->count();
        } else {
            // JIKA GUEST: Simpan ke Session
            $cart = session()->get('cart', []);
            $cartKey = $id . ($ukuranVarian ? '_' . md5($ukuranVarian) : '');

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    "produk_id"     => $produk->id,
                    "nama_produk"   => $produk->nama_produk,
                    "quantity"      => $quantity,
                    "harga"         => $produk->harga,
                    "gambar"        => $produk->gambar ?? $produk->foto,
                    "spesifikasi"   => $produk->spesifikasi,
                    "slug"          => $produk->slug,
                    "ukuran_varian" => $ukuranVarian,
                ];
            }

            session()->put('cart', $cart);
            $cartCount = count($cart);
            $insertedCartId = $cartKey;
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => $cartCount,
            'cart_id'    => $insertedCartId
        ]);
    }

    // 3. Memperbarui Jumlah Produk di Halaman Keranjang
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $quantity = (int) $request->quantity;

            if (Auth::guard('customer')->check()) {
                // JIKA LOGIN: Update jumlah di Database
                Cart::where('id', $request->id)
                    ->where('customer_id', Auth::guard('customer')->id())
                    ->update(['jumlah' => $quantity]);
            } else {
                // JIKA GUEST: Update jumlah di Session
                $cart = session()->get('cart', []);
                if (isset($cart[$request->id])) {
                    $cart[$request->id]["quantity"] = $quantity;
                    session()->put('cart', $cart);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui!'
            ]);
        }
    }

    // 4. Menghapus Item dari Keranjang
    public function remove(Request $request)
    {
        if ($request->id) {
            if (Auth::guard('customer')->check()) {
                // JIKA LOGIN: Hapus row dari Database
                Cart::where('id', $request->id)
                    ->where('customer_id', Auth::guard('customer')->id())
                    ->delete();

                $cartCount = Cart::where('customer_id', Auth::guard('customer')->id())->count();
            } else {
                // JIKA GUEST: Hapus key dari Session
                $cart = session()->get('cart', []);
                if (isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    session()->put('cart', $cart);
                }
                $cartCount = count($cart);
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil dihapus dari keranjang!',
                'cart_count' => $cartCount,
            ]);
        }
    }
}