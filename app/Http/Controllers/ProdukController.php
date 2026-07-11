<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Artikel; // <-- Required for dashboard article stats
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    /**
     * 1. Menampilkan semua produk di halaman utama admin (Tabel CRUD)
     */
    public function index()
    {
        $produks = Produk::latest()->get();
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

        // Validasi bersyarat berdasarkan ada tidaknya varian ukuran
        if ($request->has('has_variant')) {
            $rules['varians'] = 'required|array|min:1';
            $rules['varians.*.ukuran'] = 'required|string|max:255';
            $rules['varians.*.harga'] = 'required|numeric';
            $rules['varians.*.harga_coret'] = 'nullable|numeric';
            $rules['varians.*.gambar'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $rules['harga'] = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
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
        } else {
            $rules['harga'] = 'required|numeric';
            $rules['harga_coret'] = 'nullable|numeric';
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

        return redirect()->route('produk.index')->with('success', 'Data produk sukses diperbarui!');
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
     * 7. Menampilkan produk di halaman utama toko (Frontend)
     */
    public function frontendIndex(Request $request)
    {
        $keyword = $request->get('search');

        $frameProducts = Produk::where('kategori', 'frame')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest()->take(4)->get();

        $ringlockProducts = Produk::where('kategori', 'ringlock')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest()->take(4)->get();

        $tubularProducts = Produk::where('kategori', 'tubular')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest()->take(4)->get();

        $kwikstageProducts = Produk::where('kategori', 'kwikstage')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest()->take(4)->get();

        $bekistingProducts = Produk::where('kategori', 'bekisting')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('spesifikasi', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderByRaw('CASE WHEN harga_coret IS NOT NULL THEN (harga_coret - harga) ELSE 0 END DESC')
            ->latest()->take(4)->get();

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
     * 9. Menampilkan halaman depan toko (Home)
     */
    public function home()
    {
        $bestSellerProducts = Produk::where('is_terlaris', true)->latest()->take(4)->get();

        $recommendedProducts = Produk::whereNotNull('harga_coret')
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

        // 3. Kirim ke View (pastikan nama view sesuai, e.g., 'admin.dashboard' atau 'dashboard')
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
}