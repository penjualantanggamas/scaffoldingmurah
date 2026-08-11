<?php

use Illuminate\Support\Facades\Route;
use App\Models\Produk;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminShippingRateController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE FRONTEND (HALAMAN TOKO UTAMA)
|--------------------------------------------------------------------------
*/

// Halaman Utama / Beranda
Route::get('/', [ProdukController::class, 'home']);

// Halaman Tentang Kami
Route::get('/about', function () {
    $artikels = \App\Models\Artikel::latest()->take(3)->get();
    return view('about', compact('artikels'));
});

// Katalog Semua Produk & Live Search
Route::get('/products', [ProdukController::class, 'frontendIndex']);
Route::get('/api/search-products', [ProdukController::class, 'apiSearch']);

// Halaman Detail Produk Berdasarkan Slug
Route::get('/products/detail/{slug}', function ($slug) {
    $produk = Produk::where('slug', $slug)->firstOrFail();
    
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
| 2. ROUTE DETAIL KATEGORI (FILTER STOK)
|--------------------------------------------------------------------------
*/

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

Route::get('/blog', [ArtikelController::class, 'frontendIndex'])->name('blog.index');
Route::get('/blog/detail/{slug}', [ArtikelController::class, 'frontendShow'])->name('blog.show');


/*
|--------------------------------------------------------------------------
| 4. ROUTE MANAGEMENT ADMIN (PANEL CONTROL - PROTECTED BY BREEZE)
|--------------------------------------------------------------------------
*/

// Semua rute di dalam grup middleware ini wajib login Admin (Guard: web / Breeze)
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirection Dashboard Breeze ke Admin Dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Dashboard Utama Admin
    Route::get('/admin/dashboard', [ProdukController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD Admin Produk
    Route::resource('admin/produk', ProdukController::class)->names([
        'index' => 'produk.index'
    ])->except(['show']); 

    // Opsi Fitur Produk
    Route::post('/admin/produk/{id}/toggle-terlaris', [ProdukController::class, 'toggleTerlaris'])->name('produk.toggleTerlaris');
    Route::post('/admin/produk/import', [ProdukController::class, 'importExcel'])->name('produk.import');
    Route::get('/admin/produk/template', [ProdukController::class, 'downloadTemplate'])->name('produk.template');
    Route::get('/admin/produk/template-edit', [ProdukController::class, 'exportTemplateEdit'])->name('produk.templateEdit');
    Route::post('/admin/produk/delete-massal', [ProdukController::class, 'deleteMassal'])->name('admin.produk.deleteMassal');
    
    // CRUD Admin Artikel
    Route::resource('admin/artikel', ArtikelController::class);

    // Live Chat Dashboard Admin Panel
    Route::get('/admin/live-chat', function () {
        return view('admin.live-chat');
    })->name('admin.livechat');

    // Manajemen Akun Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Pesanan Admin Panel
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Manajemen Jangkauan Ongkir Armada Gudang
    Route::get('/admin/shipping-rates', [AdminShippingRateController::class, 'index'])->name('admin.shipping.index');
    Route::post('/admin/shipping-rates', [AdminShippingRateController::class, 'store'])->name('admin.shipping.store');
    Route::patch('/admin/shipping-rates/{id}', [AdminShippingRateController::class, 'update'])->name('admin.shipping.update');
    Route::patch('/admin/shipping-rates/{id}/toggle', [AdminShippingRateController::class, 'toggleStatus'])->name('admin.shipping.toggle');
    Route::delete('/admin/shipping-rates/{id}', [AdminShippingRateController::class, 'destroy'])->name('admin.shipping.destroy');

    // Pengaturan Mode Transaksi Admin (Toggle Switch Web vs WhatsApp)
    Route::get('/admin/settings/transaction', [SettingController::class, 'index'])->name('admin.settings.transaction');
    Route::patch('/admin/settings/transaction', [SettingController::class, 'update'])->name('admin.settings.transaction.update');
});


/*
|--------------------------------------------------------------------------
| 5. ROUTE AUTOMATIC BREEZE AUTHENTICATION (ADMIN ONLY)
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


/*
|--------------------------------------------------------------------------
| 7. ROUTE KHUSUS CUSTOMER AUTHENTICATION & ORDERS
|--------------------------------------------------------------------------
*/

// Rute untuk Customer yang BELUM LOGIN (Guest Customer)
Route::middleware('guest:customer')->group(function () {
    Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);
    
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register']);
});

// Logout Customer
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// RUTE PUBLIK DAFTAR PESANAN SAYA (Dapat dibuka oleh Guest/Customer belum login)
Route::get('/customer/orders', [CheckoutController::class, 'myOrders'])->name('customer.orders.index');

// Rute Terproteksi Khusus Customer (Wajib Login)
Route::middleware('auth:customer')->group(function () {
    
    // Profil & Pengaturan Akun Customer
    Route::get('/customer/profile', [CustomerAuthController::class, 'showProfile'])->name('customer.profile');
    Route::patch('/customer/profile', [CustomerAuthController::class, 'updateProfile'])->name('customer.profile.update');

    // Multi-Alamat Proyek Customer
    Route::post('/customer/profile/address', [CustomerAuthController::class, 'storeAddress'])->name('customer.address.store');
    Route::delete('/customer/profile/address/{id}', [CustomerAuthController::class, 'destroyAddress'])->name('customer.address.destroy');
    Route::patch('/customer/address/{id}/set-primary', [CustomerAuthController::class, 'setPrimaryAddress'])->name('customer.address.setPrimary');

    // Checkout Transaksi
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/cancel-buy-now', [CheckoutController::class, 'cancelBuyNow'])->name('checkout.cancelBuyNow');

    // Upload Bukti Transfer
    Route::post('/checkout/upload-proof/{id}', [CheckoutController::class, 'uploadProof'])->name('checkout.uploadProof');
    
    // Halaman Detail Pesanan (Wajib Login)
    Route::get('/customer/orders/{id}', [CheckoutController::class, 'showOrder'])->name('customer.orders.show');

});