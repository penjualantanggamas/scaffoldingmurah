<?php

use Illuminate\Support\Facades\Route;
use App\Models\Produk;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;

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
// Rute untuk mengambil data Live Search dalam format JSON
Route::get('/api/search-products', [ProdukController::class, 'apiSearch']);

// Halaman Detail Produk Berdasarkan Slug (Menampilkan Deskripsi & Produk Terkait)
Route::get('/products/detail/{slug}', function ($slug) {
    $produk = Produk::where('slug', $slug)->firstOrFail();
    
    // PERBAIKAN: Ambil produk terkait dari kategori yang sama yang HANYA memiliki STOK > 0
    $relatedProducts = Produk::where('kategori', $produk->kategori)
        ->where('id', '!=', $produk->id)
        ->where(function($query) {
            $query->where('stok', '>', 0)
                  ->orWhereHas('varians', function($subQuery) {
                      $subQuery->where('stok', '>', 0);
                  });
        })
        ->take(4)
        ->get();

    return view('products.show', compact('produk', 'relatedProducts'));
});


/*
|--------------------------------------------------------------------------
| 2. ROUTE DETAIL KATEGORI (HALAMAN SELENGKAPNYA - PERBAIKAN FILTER STOK)
|--------------------------------------------------------------------------
*/

// Fungsi pembantu agar kode kueri filter stok per kategori tetap bersih dan ringkas
$getProdukFrontend = function ($kategori) {
    return Produk::with('varians')
        ->where('kategori', $kategori)
        ->where(function($query) {
            $query->where('stok', '>', 0)
                  ->orWhereHas('varians', function($subQuery) {
                      $subQuery->where('stok', '>', 0);
                  });
        })
        ->latest()
        ->get();
};

Route::get('/products/frame-system', function () use ($getProdukFrontend) {
    $produks = $getProdukFrontend('frame');
    return view('products.frame', compact('produks'));
});

Route::get('/products/ringlock-system', function () use ($getProdukFrontend) {
    $produks = $getProdukFrontend('ringlock');
    return view('products.ringlock', compact('produks'));
});

Route::get('/products/tubular-system', function () use ($getProdukFrontend) {
    $produks = $getProdukFrontend('tubular');
    return view('products.tubular', compact('produks'));
});

Route::get('/products/kwikstage-system', function () use ($getProdukFrontend) {
    $produks = $getProdukFrontend('kwikstage');
    return view('products.kwikstage', compact('produks'));
});

Route::get('/products/bekisting-system', function () use ($getProdukFrontend) {
    $produks = $getProdukFrontend('bekisting');
    return view('products.bekisting', compact('produks'));
});


/*
|--------------------------------------------------------------------------
| 3. ROUTE FRONTEND ARTIKEL & EDUKASI K3
|--------------------------------------------------------------------------
*/

// Route Frontend Artikel untuk Pengunjung Toko
Route::get('/blog', [ArtikelController::class, 'frontendIndex'])->name('blog.index');
Route::get('/blog/detail/{slug}', [ArtikelController::class, 'frontendShow'])->name('blog.show');


/*
|--------------------------------------------------------------------------
| 4. ROUTE MANAGEMENT ADMIN (PANEL CONTROL - PROTECTED BY BREEZE)
|--------------------------------------------------------------------------
*/

// Semua rute di dalam grup middleware ini wajib login terlebih dahulu melalui sistem Breeze
Route::middleware(['auth', 'verified'])->group(function () {

    // Jembatan pengaman agar sistem Breeze tidak error mencari nama 'dashboard'
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Dashboard Utama Admin Anda yang asli
    Route::get('/admin/dashboard', [ProdukController::class, 'dashboard'])->name('admin.dashboard');

    // Otomatis Mendaftarkan Route CRUD Admin Produk
    Route::resource('admin/produk', ProdukController::class)->names([
        'index' => 'produk.index'
    ])->except(['show']); 

    // Tombol Instan Pengubah Status Produk Terlaris dari Tabel Admin
    Route::post('/admin/produk/{id}/toggle-terlaris', [ProdukController::class, 'toggleTerlaris'])->name('produk.toggleTerlaris');
    // Route untuk Import Excel Produk
    Route::post('/admin/produk/import', [ProdukController::class, 'importExcel'])->name('produk.import');
    // Route untuk Download Template Excel
    Route::get('/admin/produk/template', [ProdukController::class, 'downloadTemplate'])->name('produk.template');
    Route::get('/produk/template-edit', [ProdukController::class, 'exportTemplateEdit'])->name('produk.templateEdit');
    Route::post('/admin/produk/delete-massal', [ProdukController::class, 'deleteMassal'])->name('admin.produk.deleteMassal');
    // Otomatis Mendaftarkan Route CRUD Admin Artikel
    Route::resource('admin/artikel', ArtikelController::class);

    // Live Chat Dashboard Admin Panel (Tawk.to)
    Route::get('/admin/live-chat', function () {
        return view('admin.live-chat');
    })->name('admin.livechat');

    // Manajemen Akun Profil Admin Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| 5. ROUTE AUTOMATIC BREEZE AUTHENTICATION
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| 6. ROUTE KERANJANG BELANJA
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');