<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Artikel; 
use App\Models\ProdukVarian;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukTemplateExport;
use App\Exports\ProdukEditExport;
use Illuminate\Support\Facades\File;
use App\Models\StoreDecoration;

class ProdukController extends Controller
{
    /**
     * 1. Menampilkan semua produk di halaman utama admin (Tabel CRUD)
     */
    public function index(Request $request)
    {
        if ($request->has('reset')) {
            session()->forget(['last_search', 'last_kategori']);
            return redirect()->route('produk.index');
        }

        $keyword = $request->has('search') ? $request->get('search') : session('last_search');
        $category = $request->has('kategori') ? $request->get('kategori') : session('last_kategori');

        session([
            'last_search' => $keyword,
            'last_kategori' => $category
        ]);

        $produks = Produk::query()
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->when($category, function ($query, $category) {
                return $query->where('kategori', $category);
            })
            ->latest()
            ->get();

        return view('admin.produk.index', compact('produks'));
    }

    /**
     * 2. Menampilkan halaman form untuk menambah produk baru
     */
    public function create()
    {
        return view('admin.produk.create');
    }

    /**
     * 3. Memproses penyimpanan data produk baru ke database (Cover + Galeri + Varian + SEO)
     */
    public function store(Request $request)
    {
        // Aturan validasi dasar untuk produk utama + Fitur Pre-Order Dinamis + Meta SEO
        $rules = [
            'kategori'       => 'required|string',
            'nama_produk'    => 'required|string|max:255',
            'spesifikasi'    => 'nullable|string',
            'deskripsi'      => 'nullable|string',
            'is_preorder'    => 'required|in:0,1',
            'waktu_preorder' => 'required_if:is_preorder,1|nullable|integer',
            'maks_pembelian' => 'nullable|integer|min:0',
            'gambar'         => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'galeri.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',

            // Validasi Meta Tags SEO
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_author'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];

        // Validasi bersyarat kargo logistik berdasarkan ada tidaknya varian ukuran
        if ($request->has('has_variant')) {
            $rules['varians']            = 'required|array|min:1';
            $rules['varians.*.ukuran']   = 'required|string|max:255';
            $rules['varians.*.harga']    = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.stok']     = 'required|integer|min:0';
            $rules['varians.*.berat']    = 'required|integer|min:0';
            $rules['varians.*.panjang']  = 'required|integer|min:0';
            $rules['varians.*.lebar']    = 'required|integer|min:0';
            $rules['varians.*.tinggi']   = 'required|integer|min:0';
            $rules['varians.*.gambar']   = 'nullable|image|mimes:jpeg,png,jpg,webp';
        } else {
            $rules['harga']       = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
            $rules['stok']        = 'required|integer|min:0';
            $rules['warna']       = 'nullable|string|max:255';
            $rules['ukuran']      = 'nullable|string|max:255';
            $rules['berat']       = 'required|integer|min:0';
            $rules['panjang']     = 'required|integer|min:0';
            $rules['lebar']       = 'required|integer|min:0';
            $rules['tinggi']      = 'required|integer|min:0';
        }

        $request->validate($rules);

        $slug = Str::slug($request->nama_produk) . '-' . rand(100, 999);
        $is_terlaris = $request->has('is_terlaris') ? true : false;

        // 1. UPLOAD FOTO COVER UTAMA
        $nama_gambar_cover = null;
        if ($request->hasFile('gambar')) {
            $nama_gambar_cover = 'cover_' . time() . '_' . rand(100, 999) . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move(public_path('images/products'), $nama_gambar_cover);
        }

        // KONDISI A: JIKA ADALAH PRODUK DENGAN VARIAN
        if ($request->has('has_variant')) {
            $produk = Produk::create([
                'kategori'         => $request->kategori,
                'nama_produk'      => $request->nama_produk,
                'spesifikasi'      => $request->spesifikasi,
                'deskripsi'        => $request->deskripsi,
                'warna'            => $request->warna ?? null,
                'ukuran'           => null, 
                'harga'            => $request->varians[0]['harga'],
                'harga_coret'      => $request->varians[0]['harga_coret'] ?? null,
                'stok'             => 0,
                'slug'             => $slug,
                'is_terlaris'      => $is_terlaris,
                'gambar'           => $nama_gambar_cover,
                'berat'            => null,
                'panjang'          => null,
                'lebar'            => null,
                'tinggi'           => null,
                'maks_pembelian'   => $request->maks_pembelian,
                'is_preorder'      => $request->is_preorder,
                'waktu_preorder'   => $request->is_preorder == 1 ? $request->waktu_preorder : null,

                // SEO Data
                'meta_title'       => $request->meta_title,
                'meta_keywords'    => $request->meta_keywords,
                'meta_author'      => $request->meta_author,
                'meta_description' => $request->meta_description,
            ]);

            foreach ($request->varians as $index => $varianData) {
                $nama_gambar_varian = null;
                
                if ($request->hasFile("varians.$index.gambar")) {
                    $file = $request->file("varians.$index.gambar");
                    $nama_gambar_varian = time() . '_var_' . $index . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/products'), $nama_gambar_varian);
                }

                $produk->varians()->create([
                    'ukuran'      => $varianData['ukuran'],
                    'harga'       => $varianData['harga'],
                    'harga_coret' => $varianData['harga_coret'] ?? null,
                    'stok'        => $varianData['stok'],
                    'gambar'      => $nama_gambar_varian,
                    'berat'       => $varianData['berat'],
                    'panjang'     => $varianData['panjang'],
                    'lebar'       => $varianData['lebar'],
                    'tinggi'      => $varianData['tinggi'],
                ]);
            }
        } 
        // KONDISI B: JIKA PRODUK TUNGGAL (TANPA VARIAN)
        else {
            $produk = Produk::create([
                'kategori'         => $request->kategori,
                'nama_produk'      => $request->nama_produk,
                'spesifikasi'      => $request->spesifikasi,
                'deskripsi'        => $request->deskripsi,
                'harga'            => $request->harga,
                'harga_coret'      => $request->harga_coret,
                'stok'             => $request->stok, 
                'warna'            => $request->warna,
                'ukuran'           => $request->ukuran,
                'slug'             => $slug,
                'is_terlaris'      => $is_terlaris,
                'gambar'           => $nama_gambar_cover,
                'berat'            => $request->berat,
                'panjang'          => $request->panjang,
                'lebar'            => $request->lebar,
                'tinggi'           => $request->tinggi,
                'maks_pembelian'   => $request->maks_pembelian,
                'is_preorder'      => $request->is_preorder,
                'waktu_preorder'   => $request->is_preorder == 1 ? $request->waktu_preorder : null,

                // SEO Data
                'meta_title'       => $request->meta_title,
                'meta_keywords'    => $request->meta_keywords,
                'meta_author'      => $request->meta_author,
                'meta_description' => $request->meta_description,
            ]);
        }

        // 2. UPLOAD FOTO GALERI TAMBAHAN
        if ($request->hasFile('galeri')) {
            foreach ($request->file('galeri') as $index => $file) {
                $galeriName = 'galeri_' . $produk->id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $galeriName);

                ProductImage::create([
                    'produk_id' => $produk->id,
                    'foto'      => $galeriName,
                    'urutan'    => $index + 1,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk Tangga Mas dan galeri foto sukses disimpan!');
    }

    /**
     * 4. Menampilkan halaman form edit berdasarkan ID produk
     */
    public function edit($id)
    {
        $produk = Produk::with(['varians', 'galeri'])->findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * 5. Memproses pembaruan/update data produk di database (Cover + Galeri + Varian + SEO)
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'kategori'       => 'required|string',
            'nama_produk'    => 'required|string|max:255',
            'spesifikasi'    => 'nullable|string',
            'deskripsi'      => 'required|string',
            'is_preorder'    => 'required|in:0,1',
            'waktu_preorder' => 'required_if:is_preorder,1|nullable|integer',
            'maks_pembelian' => 'nullable|integer|min:0',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'galeri.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',

            // Validasi Meta Tags SEO
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_author'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];

        if ($request->input('has_variant') == '1') {
            $rules['varians']            = 'required|array|min:1';
            $rules['varians.*.ukuran']   = 'required|string|max:255';
            $rules['varians.*.harga']    = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.stok']     = 'required|integer|min:0';
            $rules['varians.*.berat']    = 'required|integer|min:0';
            $rules['varians.*.panjang']  = 'required|integer|min:0';
            $rules['varians.*.lebar']    = 'required|integer|min:0';
            $rules['varians.*.tinggi']   = 'required|integer|min:0';
        } else {
            $rules['harga']       = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
            $rules['stok']        = 'required|integer|min:0';
            $rules['warna']       = 'nullable|string|max:255';
            $rules['ukuran']      = 'nullable|string|max:255';
            $rules['berat']       = 'required|integer|min:0';
            $rules['panjang']     = 'required|integer|min:0';
            $rules['lebar']       = 'required|integer|min:0';
            $rules['tinggi']      = 'required|integer|min:0';
        }

        $request->validate($rules);

        $slug = Str::slug($request->nama_produk) . '-' . rand(100, 999);
        
        // 1. UPDATE FOTO COVER UTAMA JIKA ADA FILE BARU
        if ($request->hasFile('gambar')) {
            if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
                File::delete(public_path('images/products/' . $produk->gambar));
            }

            $nama_gambar_cover = 'cover_' . time() . '_' . rand(100, 999) . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move(public_path('images/products'), $nama_gambar_cover);
            $produk->gambar = $nama_gambar_cover;
        }

        // 2. HAPUS FOTO GALERI LAMA YANG DIHAPUS ADMIN
        if ($request->filled('deleted_images')) {
            $deletedIds = json_decode($request->deleted_images, true);
            if (is_array($deletedIds) && count($deletedIds) > 0) {
                $imagesToDelete = ProductImage::whereIn('id', $deletedIds)->get();
                foreach ($imagesToDelete as $img) {
                    if ($img->foto && File::exists(public_path('images/products/' . $img->foto))) {
                        File::delete(public_path('images/products/' . $img->foto));
                    }
                    $img->delete();
                }
            }
        }

        // 3. TAMBAH FOTO GALERI BARU JIKA ADA
        if ($request->hasFile('galeri')) {
            $lastOrder = $produk->galeri()->max('urutan') ?? 0;
            foreach ($request->file('galeri') as $index => $file) {
                $galeriName = 'galeri_' . $produk->id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $galeriName);

                ProductImage::create([
                    'produk_id' => $produk->id,
                    'foto'      => $galeriName,
                    'urutan'    => $lastOrder + $index + 1,
                ]);
            }
        }

        // Perbarui data dasar utama
        $produk->kategori       = $request->kategori;
        $produk->nama_produk    = $request->nama_produk;
        $produk->spesifikasi    = $request->spesifikasi;
        $produk->deskripsi      = $request->deskripsi;
        $produk->slug           = $slug;
        $produk->maks_pembelian = $request->maks_pembelian;
        $produk->is_preorder    = $request->is_preorder;
        $produk->waktu_preorder = $request->is_preorder == 1 ? $request->waktu_preorder : null;

        // Perbarui data SEO
        $produk->meta_title       = $request->meta_title;
        $produk->meta_keywords    = $request->meta_keywords;
        $produk->meta_author      = $request->meta_author;
        $produk->meta_description = $request->meta_description;

        // KONDISI A: JIKA DISIMPAN SEBAGAI PRODUK BER-VARIAN
        if ($request->input('has_variant') == '1') {
            $produk->warna       = $request->warna ?? null;
            $produk->ukuran      = null;
            $produk->harga       = $request->varians[0]['harga'];
            $produk->harga_coret = $request->varians[0]['harga_coret'] ?? null;
            $produk->stok        = 0; 
            
            $produk->berat       = null;
            $produk->panjang     = null;
            $produk->lebar       = null;
            $produk->tinggi      = null;

            $keptVariantIds = [];

            foreach ($request->varians as $index => $varianData) {
                $nama_gambar_varian = null;

                if ($request->hasFile("varians.$index.gambar")) {
                    $file = $request->file("varians.$index.gambar");
                    $nama_gambar_varian = time() . '_var_' . $index . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/products'), $nama_gambar_varian);
                }

                if (isset($varianData['id'])) {
                    $varianExisting = $produk->varians()->find($varianData['id']);
                    if ($varianExisting) {
                        $varianExisting->ukuran      = $varianData['ukuran'];
                        $varianExisting->harga       = $varianData['harga'];
                        $varianExisting->harga_coret = $varianData['harga_coret'] ?? null;
                        $varianExisting->stok        = $varianData['stok'];
                        
                        $varianExisting->berat       = $varianData['berat'];
                        $varianExisting->panjang     = $varianData['panjang'];
                        $varianExisting->lebar       = $varianData['lebar'];
                        $varianExisting->tinggi      = $varianData['tinggi'];
                        
                        if ($nama_gambar_varian) {
                            if ($varianExisting->gambar && File::exists(public_path('images/products/' . $varianExisting->gambar))) {
                                File::delete(public_path('images/products/' . $varianExisting->gambar));
                            }
                            $varianExisting->gambar = $nama_gambar_varian;
                        }
                        $varianExisting->save();
                        $keptVariantIds[] = $varianExisting->id;
                    }
                } else {
                    $newVarian = $produk->varians()->create([
                        'ukuran'      => $varianData['ukuran'],
                        'harga'       => $varianData['harga'],
                        'harga_coret' => $varianData['harga_coret'] ?? null,
                        'stok'        => $varianData['stok'],
                        'gambar'      => $nama_gambar_varian,
                        'berat'       => $varianData['berat'],
                        'panjang'     => $varianData['panjang'],
                        'lebar'       => $varianData['lebar'],
                        'tinggi'      => $varianData['tinggi'],
                    ]);
                    $keptVariantIds[] = $newVarian->id;
                }
            }

            $deletedVariants = $produk->varians()->whereNotIn('id', $keptVariantIds)->get();
            foreach ($deletedVariants as $dv) {
                if ($dv->gambar && File::exists(public_path('images/products/' . $dv->gambar))) {
                    File::delete(public_path('images/products/' . $dv->gambar));
                }
                $dv->delete();
            }
        } 
        // KONDISI B: JIKA DISIMPAN SEBAGAI PRODUK TUNGGAL (TANPA VARIAN)
        else {
            foreach ($produk->varians as $oldVarian) {
                if ($oldVarian->gambar && File::exists(public_path('images/products/' . $oldVarian->gambar))) {
                    File::delete(public_path('images/products/' . $oldVarian->gambar));
                }
                $oldVarian->delete();
            }

            $produk->harga       = $request->harga;
            $produk->harga_coret = $request->harga_coret;
            $produk->stok        = $request->stok; 
            $produk->warna       = $request->warna;
            $produk->ukuran      = $request->ukuran;
            
            $produk->berat       = $request->berat;
            $produk->panjang     = $request->panjang;
            $produk->lebar       = $request->lebar;
            $produk->tinggi      = $request->tinggi;
        }

        $produk->save();

        $searchParams = [];
        if (session()->has('last_search') && session('last_search') != '') {
            $searchParams['search'] = session('last_search');
        }
        if (session()->has('last_kategori') && session('last_kategori') != '') {
            $searchParams['kategori'] = session('last_kategori');
        }

        return redirect()->route('produk.index', $searchParams)->with('success', 'Data produk dan galeri sukses diperbarui!');
    }

    /**
     * 6. Memproses penghapusan data produk dari database
     */
    public function destroy($id)
    {
        $produk = Produk::with(['varians', 'galeri'])->findOrFail($id);

        if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
            File::delete(public_path('images/products/' . $produk->gambar));
        }

        foreach ($produk->varians as $v) {
            if ($v->gambar && File::exists(public_path('images/products/' . $v->gambar))) {
                File::delete(public_path('images/products/' . $v->gambar));
            }
        }

        foreach ($produk->galeri as $g) {
            if ($g->foto && File::exists(public_path('images/products/' . $g->foto))) {
                File::delete(public_path('images/products/' . $g->foto));
            }
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk sukses dihapus dari database!');
    }

    /**
     * 7. Menampilkan produk di halaman utama toko (Frontend - Disaring Berdasarkan Ketersediaan Stok)
     */
    public function frontendIndex(Request $request)
    {
        $keyword = $request->get('search');

        // AMBIL BANNER KATALOG DARI DATABASE (STORE DECORATION)
        $catalogHeaderBanner = StoreDecoration::where('type', 'catalog_header_banner')->first();
        $catalogMiddleBanner = StoreDecoration::where('type', 'catalog_middle_banner')->first();

        $filterStokKatalog = function($query) use ($keyword) {
            return $query->where(function($q) {
                $q->where('stok', '>', 0)
                  ->orWhereHas('varians', function($subQuery) {
                      $subQuery->where('stok', '>', 0);
                  });
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest();
        };

        $frameProducts = $filterStokKatalog(Produk::where('kategori', 'frame'))->take(4)->get();
        $ringlockProducts = $filterStokKatalog(Produk::where('kategori', 'ringlock'))->take(4)->get();
        $tubularProducts = $filterStokKatalog(Produk::where('kategori', 'tubular'))->take(4)->get();
        $kwikstageProducts = $filterStokKatalog(Produk::where('kategori', 'kwikstage'))->take(4)->get();
        $bekistingProducts = $filterStokKatalog(Produk::where('kategori', 'bekisting'))->take(4)->get();

        return view('products', compact(
            'catalogHeaderBanner',
            'catalogMiddleBanner',
            'frameProducts', 
            'ringlockProducts', 
            'tubularProducts', 
            'kwikstageProducts', 
            'bekistingProducts'
        ));
    }

    /**
     * 8. Mengubah status produk terlaris
     */
    public function toggleTerlaris($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->is_terlaris = !$produk->is_terlaris;
        $produk->save();

        $statusText = $produk->is_terlaris ? 'ditambahkan ke Produk Terlaris!' : 'dihapus dari Produk Terlaris!';
        return redirect()->back()->with('success', "Produk '{$produk->nama_produk}' sukses " . $statusText);
    }

    /**
     * 9. Menampilkan halaman depan toko (Home)
     */
    public function home()
    {
        // 1. Ambil semua blok dekorasi toko yang aktif berdasarkan urutan
        $decorations = StoreDecoration::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->get();

        // 2. Query Fallback untuk Produk Promo Diskon
        $recommendedProducts = Produk::whereNotNull('harga_coret')
            ->whereColumn('harga_coret', '>', 'harga')
            ->where(function($q) {
                $q->where('stok', '>', 0)
                ->orWhereHas('varians', function($subQuery) {
                    $subQuery->where('stok', '>', 0);
                });
            })
            ->orderByRaw('(harga_coret - harga) DESC')
            ->latest()
            ->take(4)
            ->get();

        // 3. Query Fallback untuk Produk Terlaris (HOT)
        $bestSellerProducts = Produk::where('is_terlaris', true)
            ->where(function($q) {
                $q->where('stok', '>', 0)
                ->orWhereHas('varians', function($subQuery) {
                    $subQuery->where('stok', '>', 0);
                });
            })
            ->latest()
            ->take(4)
            ->get();

        return view('index', compact('decorations', 'recommendedProducts', 'bestSellerProducts'));
    }

    /**
     * 10. Menampilkan Dashboard Admin
     */
    public function dashboard() 
    {
        $countVerifikasi = Order::whereIn('status_pembayaran', ['menunggu_konfirmasi_admin', 'verifikasi'])->count();
        $countDiproses   = Order::whereIn('status_pembayaran', ['dibayar', 'paid', 'lunas'])
                                ->whereIn('status_pesanan', ['pending', 'diproses', 'sedang_dikemas', 'processing'])->count();
        $countDikirim    = Order::whereIn('status_pesanan', ['dikirim', 'shipped'])->count();
        $countSelesai    = Order::whereIn('status_pesanan', ['selesai', 'completed'])->count();
        $countBatal      = Order::where(function ($q) {
                                $q->whereIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])
                                  ->orWhereIn('status_pembayaran', ['dibatalkan', 'cancelled', 'batal']);
                            })->count();

        $totalProduk    = Produk::count();
        $totalFrame     = Produk::where('kategori', 'frame')->count();
        $totalRinglock  = Produk::where('kategori', 'ringlock')->count();
        $totalTubular   = Produk::where('kategori', 'tubular')->count();
        $produkTerbaru  = Produk::latest()->take(5)->get();

        $totalArtikel   = Artikel::count(); 
        $artikelTerbaru = Artikel::latest()->take(5)->get(); 

        $totalPenjualan = Order::whereNotIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])->sum('grand_total');
        $totalPesanan   = Order::count();
        
        $totalPengunjung = 51; 
        $totalKlikProduk = 73;
        
        $tingkatKonversi = $totalPengunjung > 0 ? ($totalPesanan / $totalPengunjung) * 100 : 0;

        return view('admin.dashboard', compact(
            'countVerifikasi',
            'countDiproses',
            'countDikirim',
            'countSelesai',
            'countBatal',
            'totalProduk', 
            'totalFrame', 
            'totalRinglock', 
            'totalTubular', 
            'produkTerbaru',
            'totalArtikel',    
            'artikelTerbaru',
            'totalPenjualan',
            'totalPesanan',
            'totalPengunjung',
            'totalKlikProduk',
            'tingkatKonversi'
        ));
    }

    /**
     * Memproses file Excel yang diunggah Admin
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProdukImport, $request->file('file_excel'));
            return redirect()->route('produk.index')->with('success', 'Data produk sukses diimpor dari Excel!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data. Error: ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh Template Excel Kosong
     */
    public function downloadTemplate()
    {
        return Excel::download(new ProdukTemplateExport, 'Template_Upload_Produk_TanggaMas.xlsx');
    }
    
    public function apiSearch(Request $request)
    {
        $query = $request->get('search');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $produks = Produk::where('nama_produk', 'LIKE', '%' . $query . '%')
            ->where(function($q) {
                $q->where('stok', '>', 0)
                  ->orWhereHas('varians', function($subQuery) {
                      $subQuery->where('stok', '>', 0);
                  });
            })
            ->select('id', 'nama_produk as nama', 'slug', 'gambar')
            ->take(7)
            ->get();

        return response()->json($produks);
    }

    public function exportTemplateEdit()
    {
        return Excel::download(new ProdukEditExport, 'Template_Edit_Produk_TanggaMas.xlsx');
    }

    public function deleteMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:produks,id'
        ]);

        $ids = $request->ids;

        try {
            $produks = Produk::with(['varians', 'galeri'])->whereIn('id', $ids)->get();
            foreach ($produks as $produk) {
                if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
                    File::delete(public_path('images/products/' . $produk->gambar));
                }
                foreach ($produk->varians as $v) {
                    if ($v->gambar && File::exists(public_path('images/products/' . $v->gambar))) {
                        File::delete(public_path('images/products/' . $v->gambar));
                    }
                }
                foreach ($produk->galeri as $g) {
                    if ($g->foto && File::exists(public_path('images/products/' . $g->foto))) {
                        File::delete(public_path('images/products/' . $g->foto));
                    }
                }
            }

            Produk::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' produk scaffolding berhasil dihapus secara massal.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.'
            ], 500);
        }
    }

    /**
     * Menampilkan Produk Berdasarkan Kategori Spesifik (Frontend)
     */
    public function produkPerKategori(Request $request, $kategori = null)
    {
        if (empty($kategori)) {
            $kategori = $request->segment(2);
        }

        $kategoriClean = strtolower(trim(str_replace('-system', '', $kategori)));

        $categoryBanner = StoreDecoration::where('type', 'category_banner_' . $kategoriClean)
            ->orWhere('type', 'category_banner_' . strtolower($kategori))
            ->orWhere('type', 'LIKE', '%' . $kategoriClean . '%')
            ->first();

        $produks = Produk::with(['varians', 'galeri'])
            ->where('kategori', $kategoriClean)
            ->where(function($query) {
                $query->where('stok', '>', 0)
                    ->orWhereHas('varians', function($subQuery) {
                        $subQuery->where('stok', '>', 0);
                    });
            })
            ->latest()
            ->get();

        $possibleViews = [
            'products.' . $kategoriClean,          
            'frontend.' . $kategoriClean,          
            'frontend.' . strtolower($kategori),   
            $kategoriClean,                        
            strtolower($kategori),                 
            'frontend.products',                   
            'products'
        ];

        foreach ($possibleViews as $view) {
            if (view()->exists($view)) {
                return view($view, compact('produks', 'kategoriClean', 'categoryBanner'));
            }
        }

        return view('frontend.products', compact('produks', 'kategoriClean', 'categoryBanner'));
    }
}