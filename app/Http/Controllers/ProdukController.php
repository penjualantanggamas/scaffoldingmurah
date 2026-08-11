<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Artikel; 
use App\Models\ProdukVarian;
use App\Models\Order; // Import Model Order
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukTemplateExport;
use App\Exports\ProdukEditExport;
use Illuminate\Support\Facades\File;

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
     * 3. Memproses penyimpanan data produk baru ke database (Opsional Varian)
     */
    public function store(Request $request)
    {
        // Aturan validasi dasar untuk produk utama + Fitur Pre-Order Dinamis
        $rules = [
            'kategori'       => 'required|string',
            'nama_produk'    => 'required|string|max:255',
            'spesifikasi'    => 'nullable|string',
            'deskripsi'      => 'nullable|string',
            'is_preorder'    => 'required|in:0,1',
            'waktu_preorder' => 'required_if:is_preorder,1|nullable|integer',
            'maks_pembelian' => 'nullable|integer|min:0',
        ];

        // Validasi bersyarat kargo logistik berdasarkan ada tidaknya varian ukuran
        if ($request->has('has_variant')) {
            $rules['varians']            = 'required|array|min:1';
            $rules['varians.*.ukuran']   = 'required|string|max:255';
            $rules['varians.*.harga']    = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.stok']     = 'required|integer|min:0';
            $rules['varians.*.berat']    = 'required|integer|min:0'; // Wajib di baris varian
            $rules['varians.*.panjang']  = 'required|integer|min:0';
            $rules['varians.*.lebar']    = 'required|integer|min:0';
            $rules['varians.*.tinggi']   = 'required|integer|min:0';
            $rules['varians.*.gambar']   = 'required|image|mimes:jpeg,png,jpg,webp';
        } else {
            $rules['harga']       = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
            $rules['stok']        = 'required|integer|min:0';
            $rules['warna']       = 'nullable|string|max:255';
            $rules['ukuran']      = 'nullable|string|max:255';
            $rules['berat']       = 'required|integer|min:0'; // Wajib jika produk tunggal
            $rules['panjang']     = 'required|integer|min:0';
            $rules['lebar']       = 'required|integer|min:0';
            $rules['tinggi']      = 'required|integer|min:0';
            $rules['gambar']      = 'required|image|mimes:jpeg,png,jpg,webp';
        }

        $request->validate($rules);

        $slug = Str::slug($request->nama_produk) . '-' . rand(100, 999);
        $is_terlaris = $request->has('is_terlaris') ? true : false;

        // KONDISI A: JIKA ADALAH PRODUK DENGAN VARIAN
        if ($request->has('has_variant')) {
            $produk = Produk::create([
                'kategori'       => $request->kategori,
                'nama_produk'    => $request->nama_produk,
                'spesifikasi'    => $request->spesifikasi,
                'deskripsi'      => $request->deskripsi,
                'warna'          => $request->warna ?? null,
                'ukuran'         => null, 
                'harga'          => $request->varians[0]['harga'],
                'harga_coret'    => $request->varians[0]['harga_coret'] ?? null,
                'stok'           => 0, // Nilai default produk utama bermulti-varian dialihkan ke 0
                'slug'           => $slug,
                'is_terlaris'    => $is_terlaris,
                'gambar'         => null,
                
                // DATA LOGISTIK INDUK DI-NULL-KAN KARENA MENGIKUTI VARIAN
                'berat'          => null,
                'panjang'        => null,
                'lebar'          => null,
                'tinggi'         => null,
                'maks_pembelian' => $request->maks_pembelian,
                'is_preorder'    => $request->is_preorder,
                'waktu_preorder' => $request->is_preorder == 1 ? $request->waktu_preorder : null,
            ]);

            $gambarUtamaSet = false;

            foreach ($request->varians as $index => $varianData) {
                $nama_gambar_varian = null;
                
                if ($request->hasFile("varians.$index.gambar")) {
                    $file = $request->file("varians.$index.gambar");
                    $nama_gambar_varian = time() . '_var_' . $index . '.' . $file->extension();
                    $file->move(public_path('images/products'), $nama_gambar_varian);
                }

                $produk->varians()->create([
                    'ukuran'      => $varianData['ukuran'],
                    'harga'       => $varianData['harga'],
                    'harga_coret' => $varianData['harga_coret'] ?? null,
                    'stok'        => $varianData['stok'],
                    'gambar'      => $nama_gambar_varian,
                    
                    // SUNTIKAN DATA LOGISTIK KHUSUS TIAP VARIAN
                    'berat'       => $varianData['berat'],
                    'panjang'     => $varianData['panjang'],
                    'lebar'       => $varianData['lebar'],
                    'tinggi'      => $varianData['tinggi'],
                ]);

                if (!$gambarUtamaSet && $nama_gambar_varian) {
                    $produk->update(['gambar' => $nama_gambar_varian]);
                    $gambarUtamaSet = true;
                }
            }
        } 
        // KONDISI B: JIKA PRODUK TUNGGAL (TANPA VARIAN)
        else {
            $nama_gambar = null;
            if ($request->hasFile('gambar')) {
                $nama_gambar = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('images/products'), $nama_gambar);
            }

            Produk::create([
                'kategori'       => $request->kategori,
                'nama_produk'    => $request->nama_produk,
                'spesifikasi'    => $request->spesifikasi,
                'deskripsi'      => $request->deskripsi,
                'harga'          => $request->harga,
                'harga_coret'    => $request->harga_coret,
                'stok'           => $request->stok, 
                'warna'          => $request->warna,
                'ukuran'         => $request->ukuran,
                'slug'           => $slug,
                'is_terlaris'    => $is_terlaris,
                'gambar'         => $nama_gambar,
                
                // SIMPAN DATA LOGISTIK & PO PADA TABEL UTAMA PRODUK TUNGGAL
                'berat'          => $request->berat,
                'panjang'        => $request->panjang,
                'lebar'          => $request->lebar,
                'tinggi'         => $request->tinggi,
                'maks_pembelian' => $request->maks_pembelian,
                'is_preorder'    => $request->is_preorder,
                'waktu_preorder' => $request->is_preorder == 1 ? $request->waktu_preorder : null,
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk Tangga Mas sukses disimpan!');
    }

    /**
     * 4. Menampilkan halaman form edit berdasarkan ID produk
     */
    public function edit($id)
    {
        $produk = Produk::with('varians')->findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * 5. Memproses pembaruan/update data produk di database (Opsional Varian)
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
        
        // Perbarui data dasar utama
        $produk->kategori       = $request->kategori;
        $produk->nama_produk    = $request->nama_produk;
        $produk->spesifikasi    = $request->spesifikasi;
        $produk->deskripsi      = $request->deskripsi;
        $produk->slug           = $slug;
        $produk->maks_pembelian = $request->maks_pembelian;
        $produk->is_preorder    = $request->is_preorder;
        $produk->waktu_preorder = $request->is_preorder == 1 ? $request->waktu_preorder : null;

        // KONDISI A: JIKA DISIMPAN SEBAGAI PRODUK BER-VARIAN
        if ($request->input('has_variant') == '1') {
            $produk->warna       = $request->warna ?? null;
            $produk->ukuran      = null;
            $produk->harga       = $request->varians[0]['harga'];
            $produk->harga_coret = $request->varians[0]['harga_coret'] ?? null;
            $produk->stok        = 0; 
            
            // Logistik induk dinullkan karena pindah ke tabel varian
            $produk->berat       = null;
            $produk->panjang     = null;
            $produk->lebar       = null;
            $produk->tinggi      = null;

            if ($produk->gambar && !str_contains($produk->gambar, '_var_')) {
                if (File::exists(public_path('images/products/' . $produk->gambar))) {
                    File::delete(public_path('images/products/' . $produk->gambar));
                }
                $produk->gambar = null;
            }

            $keptVariantIds = [];

            foreach ($request->varians as $index => $varianData) {
                $nama_gambar_varian = null;

                if ($request->hasFile("varians.$index.gambar")) {
                    $file = $request->file("varians.$index.gambar");
                    $nama_gambar_varian = time() . '_var_' . $index . '.' . $file->extension();
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

            $varianPertama = $produk->varians()->first();
            if ($varianPertama) {
                $produk->gambar = $varianPertama->gambar;
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

            if ($request->hasFile('gambar')) {
                if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
                    File::delete(public_path('images/products/' . $produk->gambar));
                }

                $nama_gambar = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('images/products'), $nama_gambar);
                $produk->gambar = $nama_gambar;
            }
        }

        $produk->save();

        $searchParams = [];
        if (session()->has('last_search') && session('last_search') != '') {
            $searchParams['search'] = session('last_search');
        }
        if (session()->has('last_kategori') && session('last_kategori') != '') {
            $searchParams['kategori'] = session('last_kategori');
        }

        return redirect()->route('produk.index', $searchParams)->with('success', 'Data produk sukses diperbarui!');
    }

    /**
     * 6. Memproses penghapusan data produk dari database
     */
    public function destroy($id)
    {
        $produk = Produk::with('varians')->findOrFail($id);

        if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
            File::delete(public_path('images/products/' . $produk->gambar));
        }

        foreach ($produk->varians as $v) {
            if ($v->gambar && File::exists(public_path('images/products/' . $v->gambar))) {
                File::delete(public_path('images/products/' . $v->gambar));
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
     * 9. Menampilkan halaman depan toko (Home - Disaring Berdasarkan Ketersediaan Stok)
     */
    public function home()
    {
        $filterFisikAda = function($query) {
            return $query->where(function($q) {
                $q->where('stok', '>', 0)
                  ->orWhereHas('varians', function($subQuery) {
                      $subQuery->where('stok', '>', 0);
                  });
            });
        };

        $bestSellerProducts = $filterFisikAda(Produk::where('is_terlaris', true))->latest()->take(4)->get();

        $recommendedProducts = $filterFisikAda(Produk::whereNotNull('harga_coret'))
            ->whereColumn('harga_coret', '>', 'harga')
            ->orderByRaw('(harga_coret - harga) DESC')
            ->latest()
            ->take(4)
            ->get();

        return view('index', compact('bestSellerProducts', 'recommendedProducts'));
    }

    /**
     * 10. Menampilkan Dashboard Admin (Termasuk Data Performa Toko & Operasional Pesanan)
     */
    public function dashboard() 
    {
        // 1. STATISTIK CUPLIKAN OPERASIONAL PESANAN (SHOPEE SELLER CENTRE STYLE)
        $countVerifikasi = Order::whereIn('status_pembayaran', ['menunggu_konfirmasi_admin', 'verifikasi'])->count();
        $countDiproses   = Order::whereIn('status_pembayaran', ['dibayar', 'paid', 'lunas'])
                                ->whereIn('status_pesanan', ['pending', 'diproses', 'sedang_dikemas', 'processing'])->count();
        $countDikirim    = Order::whereIn('status_pesanan', ['dikirim', 'shipped'])->count();
        $countSelesai    = Order::whereIn('status_pesanan', ['selesai', 'completed'])->count();
        $countBatal      = Order::where(function ($q) {
                                $q->whereIn('status_pesanan', ['dibatalkan', 'cancelled', 'batal'])
                                  ->orWhereIn('status_pembayaran', ['dibatalkan', 'cancelled', 'batal']);
                            })->count();

        // 2. DATA KATALOG PRODUK & ARTIKEL
        $totalProduk    = Produk::count();
        $totalFrame     = Produk::where('kategori', 'frame')->count();
        $totalRinglock  = Produk::where('kategori', 'ringlock')->count();
        $totalTubular   = Produk::where('kategori', 'tubular')->count();
        $produkTerbaru  = Produk::latest()->take(5)->get();

        $totalArtikel   = Artikel::count(); 
        $artikelTerbaru = Artikel::latest()->take(5)->get(); 

        // 3. DATA PERFORMA TOKO
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
            return redirect()->back()->with('error', 'Gagal mengimpor data. Pastikan format kolom Excel sudah benar. Error: ' . $e->getMessage());
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
            $produks = Produk::with('varians')->whereIn('id', $ids)->get();
            foreach ($produks as $produk) {
                if ($produk->gambar && File::exists(public_path('images/products/' . $produk->gambar))) {
                    File::delete(public_path('images/products/' . $produk->gambar));
                }
                foreach ($produk->varians as $v) {
                    if ($v->gambar && File::exists(public_path('images/products/' . $v->gambar))) {
                        File::delete(public_path('images/products/' . $v->gambar));
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

    public function produkPerKategori($kategori)
    {
        $kategoriClean = str_replace('-system', '', $kategori);

        $produks = Produk::with('varians')
            ->where('kategori', $kategoriClean)
            ->where(function($query) {
                $query->where('stok', '>', 0)
                    ->orWhereHas('varians', function($subQuery) {
                        $subQuery->where('stok', '>', 0);
                    });
            })
            ->latest()
            ->get();

        return view('frontend.products', compact('produks', 'kategoriClean'));
    }
}