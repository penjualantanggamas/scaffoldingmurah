<?php

use Illuminate\Support\Facades\Route;
use App\Models\Produk;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ArtikelController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE FRONTEND (HALAMAN TOKO UTAMA)
|--------------------------------------------------------------------------
*/

// Halaman Utama / Beranda (Menampilkan Produk Terlaris secara dinamis)
Route::get('/', [ProdukController::class, 'home']);

// Halaman Tentang Kami
Route::get('/about', function () {
    // Ambil 3 artikel K3 / produk terbaru untuk ditampilkan di halaman About
    $artikels = \App\Models\Artikel::latest()->take(3)->get();
    return view('about', compact('artikels'));
});

// Katalog Semua Produk (Dengan Fitur Search Bar di Navbar & Filter Diskon)
Route::get('/products', [ProdukController::class, 'frontendIndex']);

// Halaman Detail Produk Berdasarkan Slug (Menampilkan Deskripsi & Produk Terkait)
Route::get('/products/detail/{slug}', function ($slug) {
    $produk = Produk::where('slug', $slug)->firstOrFail();
    
    // Ambil produk terkait dari kategori yang sama untuk rekomendasi di bagian bawah
    $relatedProducts = Produk::where('kategori', $produk->kategori)
        ->where('id', '!=', $produk->id)
        ->take(4)
        ->get();

    return view('products.show', compact('produk', 'relatedProducts'));
});


/*
|--------------------------------------------------------------------------
| 2. ROUTE DETAIL KATEGORI (HALAMAN SELENGKAPNYA)
|--------------------------------------------------------------------------
*/
Route::get('/products/frame-system', function () {
    $produks = Produk::where('kategori', 'frame')->latest()->get();
    return view('products.frame', compact('produks'));
});

Route::get('/products/ringlock-system', function () {
    $produks = Produk::where('kategori', 'ringlock')->latest()->get();
    return view('products.ringlock', compact('produks'));
});

Route::get('/products/tubular-system', function () {
    $produks = Produk::where('kategori', 'tubular')->latest()->get();
    return view('products.tubular', compact('produks'));
});

Route::get('/products/kwikstage-system', function () {
    $produks = Produk::where('kategori', 'kwikstage')->latest()->get();
    return view('products.kwikstage', compact('produks'));
});

Route::get('/products/bekisting-system', function () {
    $produks = Produk::where('kategori', 'bekisting')->latest()->get();
    return view('products.bekisting', compact('produks'));
});


/*
|--------------------------------------------------------------------------
| 3. ROUTE MANAGEMENT ADMIN (PANEL CONTROL)
|--------------------------------------------------------------------------
*/

// Dashboard Utama Admin (Sudah mengarah ke method dashboard di ProdukController yang mengambil data artikel)
Route::get('/admin/dashboard', [ProdukController::class, 'dashboard'])->name('admin.dashboard');

// Otomatis Mendaftarkan Route CRUD Admin Produk (Index, Create, Store, Edit, Update, Destroy)
Route::resource('admin/produk', ProdukController::class)->names([
    'index' => 'produk.index'
]);

// Tombol Instan Pengubah Status Produk Terlaris dari Tabel Admin
Route::post('/admin/produk/{id}/toggle-terlaris', [ProdukController::class, 'toggleTerlaris'])->name('produk.toggleTerlaris');


/*
|--------------------------------------------------------------------------
| 4. ROUTE ARTIKEL & EDUKASI K3
|--------------------------------------------------------------------------
*/

// Route Frontend Artikel untuk Pengunjung Toko
Route::get('/blog', [ArtikelController::class, 'frontendIndex'])->name('blog.index');
Route::get('/blog/detail/{slug}', [ArtikelController::class, 'frontendShow'])->name('blog.show');

// Otomatis Mendaftarkan Route CRUD Admin Artikel (Index, Create, Store, Edit, Update, Destroy)
Route::resource('admin/artikel', ArtikelController::class);