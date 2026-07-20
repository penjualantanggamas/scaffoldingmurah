<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Artikel; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukTemplateExport;
use App\Exports\ProdukEditExport;

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

        // Jika ada request filter baru, ambil dari request. Jika tidak ada, cek apakah ada histori di session.
        $keyword = $request->has('search') ? $request->get('search') : session('last_search');
        $category = $request->has('kategori') ? $request->get('kategori') : session('last_kategori');

        // Simpan filter saat ini ke dalam session untuk diingat nanti
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
        // Aturan validasi dasar untuk produk utama
        $rules = [
            'kategori' => 'required|string',
            'nama_produk' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ];

        // Validasi bersyarat berdasarkan ada tidaknya varian ukuran (Termasuk Aturan Validasi Stok)
        if ($request->has('has_variant')) {
            $rules['varians'] = 'required|array|min:1';
            $rules['varians.*.ukuran'] = 'required|string|max:255';
            $rules['varians.*.harga'] = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.stok'] = 'required|integer|min:0'; // Tambahan validasi stok varian
            $rules['varians.*.gambar'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $rules['harga'] = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
            $rules['stok'] = 'required|integer|min:0'; // Tambahan validasi stok produk tunggal
            $rules['warna'] = 'nullable|string|max:255';
            $rules['ukuran'] = 'nullable|string|max:255';
            $rules['gambar'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        }

        $request->validate($rules);

        // Siapkan kerangka data produk utama
        $slug = Str::slug($request->nama_produk) . '-' . rand(100, 999);
        $is_terlaris = $request->has('is_terlaris') ? true : false;

        // KONDISI A: JIKA ADALAH PRODUK DENGAN VARIAN
        if ($request->has('has_variant')) {
            $produk = Produk::create([
                'kategori' => $request->kategori,
                'nama_produk' => $request->nama_produk,
                'spesifikasi' => $request->spesifikasi,
                'deskripsi' => $request->deskripsi,
                'warna' => $request->warna ?? null,
                'ukuran' => null, 
                'harga' => $request->varians[0]['harga'],
                'harga_coret' => $request->varians[0]['harga_coret'] ?? null,
                'stok' => 0, // Nilai default produk utama bermulti-varian dialihkan ke 0
                'slug' => $slug,
                'is_terlaris' => $is_terlaris,
                'gambar' => null 
            ]);

            $gambarUtamaSet = false;

            foreach ($request->file('varians') as $index => $varianFile) {
                $nama_gambar_varian = null;
                
                if (isset($varianFile['gambar'])) {
                    $nama_gambar_varian = time() . '_var_' . $index . '.' . $varianFile['gambar']->extension();
                    $varianFile['gambar']->move(public_path('images/products'), $nama_gambar_varian);
                }

                $produk->varians()->create([
                    'ukuran' => $request->varians[$index]['ukuran'],
                    'harga' => $request->varians[$index]['harga'],
                    'harga_coret' => $request->varians[$index]['harga_coret'] ?? null,
                    'stok' => $request->varians[$index]['stok'], // Menyimpan nilai inventaris stok varian
                    'gambar' => $nama_gambar_varian
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
                'kategori' => $request->kategori,
                'nama_produk' => $request->nama_produk,
                'spesifikasi' => $request->spesifikasi,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'harga_coret' => $request->harga_coret,
                'stok' => $request->stok, // Menyimpan nilai inventaris stok tunggal
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'slug' => $slug,
                'is_terlaris' => $is_terlaris,
                'gambar' => $nama_gambar
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk Tangga Mas sukses disimpan!');
    }

    /**
     * 4. Menampilkan halaman form edit berdasarkan ID produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * 5. Memproses pembaruan/update data produk di database (Opsional Varian)
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'kategori' => 'required|string',
            'nama_produk' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ];

        if ($request->input('has_variant') == '1') {
            $rules['varians'] = 'required|array|min:1';
            $rules['varians.*.ukuran'] = 'required|string|max:255';
            $rules['varians.*.harga'] = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.stok'] = 'required|integer|min:0'; // Validasi stok varian saat update
        } else {
            $rules['harga'] = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
            $rules['stok'] = 'required|integer|min:0'; // Validasi stok tunggal saat update
            $rules['warna'] = 'nullable|string|max:255';
            $rules['ukuran'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $slug = Str::slug($request->nama_produk) . '-' . rand(100, 999);
        $produk->kategori = $request->kategori;
        $produk->nama_produk = $request->nama_produk;
        $produk->spesifikasi = $request->spesifikasi;
        $produk->deskripsi = $request->deskripsi;
        $produk->slug = $slug;

        if ($request->input('has_variant') == '1') {
            $produk->warna = $request->warna ?? null;
            $produk->ukuran = null;
            $produk->harga = $request->varians[0]['harga'];
            $produk->harga_coret = $request->varians[0]['harga_coret'] ?? null;
            $produk->stok = 0; // Set stok produk utama ke 0 jika memiliki varian ukuran

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
                        $varianExisting->ukuran = $varianData['ukuran'];
                        $varianExisting->harga = $varianData['harga'];
                        $varianExisting->harga_coret = $varianData['harga_coret'] ?? null;
                        $varianExisting->stok = $varianData['stok']; // Update nilai stok varian aktif
                        
                        if ($nama_gambar_varian) {
                            if ($varianExisting->gambar && file_exists(public_path('images/products/' . $varianExisting->gambar))) {
                                unlink(public_path('images/products/' . $varianExisting->gambar));
                            }
                            $varianExisting->gambar = $nama_gambar_varian;
                        }
                        $varianExisting->save();
                        $keptVariantIds[] = $varianExisting->id;
                    }
                } else {
                    $newVarian = $produk->varians()->create([
                        'ukuran' => $varianData['ukuran'],
                        'harga' => $varianData['harga'],
                        'harga_coret' => $varianData['harga_coret'] ?? null,
                        'stok' => $varianData['stok'], // Simpan stok varian baru
                        'gambar' => $nama_gambar_varian
                    ]);
                    $keptVariantIds[] = $newVarian->id;
                }
            }

            $deletedVariants = $produk->varians()->whereNotIn('id', $keptVariantIds)->get();
            foreach ($deletedVariants as $dv) {
                if ($dv->gambar && file_exists(public_path('images/products/' . $dv->gambar))) {
                    unlink(public_path('images/products/' . $dv->gambar));
                }
                $dv->delete();
            }

            $varianPertama = $produk->varians()->first();
            if ($varianPertama) {
                $produk->gambar = $varianPertama->gambar;
            }

        } else {
            $produk->harga = $request->harga;
            $produk->harga_coret = $request->harga_coret;
            $produk->stok = $request->stok; // Update nilai stok produk tunggal
            $produk->warna = $request->warna;
            $produk->ukuran = $request->ukuran;

            if ($request->hasFile('gambar')) {
                if ($produk->gambar && file_exists(public_path('images/products/' . $produk->gambar))) {
                    unlink(public_path('images/products/' . $produk->gambar));
                }

                $nama_gambar = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('images/products'), $nama_gambar);
                $produk->gambar = $nama_gambar;
            }
        }

        $produk->save();

        // AMBIL HISTORI FILTER DARI SESSION SEBELUM REDIRECT
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
        $produk = Produk::findOrFail($id);

        if ($produk->gambar && file_exists(public_path('images/products/' . $produk->gambar))) {
            unlink(public_path('images/products/' . $produk->gambar));
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

        // Fungsi makro penanganan filter ketersediaan inventaris fisik (Stok > 0)
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
        // Pastikan produk terlaris & rekomendasi promo diskon yang tampil juga memiliki stok fisik
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
     * 10. Menampilkan Dashboard Admin (Tergabung dengan data Artikel)
     */
    public function dashboard() 
    {
        // 1. Data Produk
        $totalProduk = Produk::count();
        $totalFrame = Produk::where('kategori', 'frame')->count();
        $totalRinglock = Produk::where('kategori', 'ringlock')->count();
        $totalTubular = Produk::where('kategori', 'tubular')->count();
        $produkTerbaru = Produk::latest()->take(5)->get();

        // 2. Data Artikel K3
        $totalArtikel = Artikel::count(); 
        $artikelTerbaru = Artikel::latest()->take(5)->get(); 

        // 3. Kirim ke View
        return view('admin.dashboard', compact(
            'totalProduk', 
            'totalFrame', 
            'totalRinglock', 
            'totalTubular', 
            'produkTerbaru',
            'totalArtikel',    
            'artikelTerbaru'   
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

        // Fitur auto-complete search box juga disaring hanya menampilkan barang yang ada stoknya
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
            $produks = Produk::whereIn('id', $ids)->get();
            foreach ($produks as $produk) {
                if ($produk->gambar && file_exists(public_path('images/products/' . $produk->gambar))) {
                    @unlink(public_path('images/products/' . $produk->gambar));
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
        // Bersihkan string kategori jika ada embel-embel '-system' dari URL
        $kategoriClean = str_replace('-system', '', $kategori);

        // Ambil produk yang stok utamanya > 0 ATAU variannya ada yang > 0
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