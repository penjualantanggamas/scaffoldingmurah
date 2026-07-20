<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. Menampilkan Halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // 2. Menambahkan Produk ke Dalam Keranjang
    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya (quantity)
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->input('quantity', 1);
        } else {
            // Jika produk baru, masukkan data fundamentalnya ke session
            $cart[$id] = [
                "nama_produk" => $produk->nama_produk,
                "quantity" => $request->input('quantity', 1),
                "harga" => $produk->harga,
                "gambar" => $produk->gambar,
                "spesifikasi" => $produk->spesifikasi,
                "slug" => $produk->slug
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => count($cart)
        ]);
    }

    // 3. Memperbarui Jumlah Produk di Halaman Keranjang
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart', []);
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui!'
            ]);
        }
    }

    // 4. Menghapus Item dari Keranjang
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart', []);
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang!',
                'cart_count' => count($cart)
            ]);
        }
    }
}