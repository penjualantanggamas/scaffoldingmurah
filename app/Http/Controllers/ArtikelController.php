<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    /* ==========================================================================
       A. FUNGSI FRONTEND (UNTUK PEMBELI / PENGUNJUNG)
       ========================================================================== */

    // 1. Menampilkan katalog semua artikel
    public function frontendIndex(Request $request)
    {
        $kategori = $request->get('kategori');
        
        $artikels = Artikel::when($kategori, function ($query, $kategori) {
                return $query->where('kategori', $kategori);
            })
            ->latest()
            ->paginate(6); // Menampilkan 6 artikel per halaman

        return view('blog.index', compact('artikels'));
    }

    // 2. Menampilkan isi detail bacaan artikel
    public function frontendShow($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        
        // Naikkan hit pembaca setiap kali halaman dibuka
        $artikel->increment('views');

        // Mengambil 3 artikel lainnya untuk rekomendasi bacaan
        $relatedArticles = Artikel::where('id', '!=', $artikel->id)->latest()->take(3)->get();

        return view('blog.show', compact('artikel', 'relatedArticles'));
    }

    /* ==========================================================================
       B. FUNGSI BACKEND (CRUD PANEL ADMIN)
       ========================================================================== */

    public function index()
    {
        $artikels = Artikel::latest()->get();
        return view('admin.artikel.index', compact('artikels'));
    }

    public function create()
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request)
    {
        // Validasi bersih tanpa input penulis
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'ringkasan' => 'required|string|max:500',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp,svg'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->judul) . '-' . rand(100, 999);

        if ($request->hasFile('gambar')) {
            $nama_gambar = time() . '_blog.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/blog'), $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        Artikel::create($data);

        return redirect()->route('artikel.index')->with('success', 'Artikel edukasi sukses diterbitkan!');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        // Validasi bersih tanpa input penulis
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'ringkasan' => 'required|string|max:500',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->judul) . '-' . rand(100, 999);

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar && file_exists(public_path('images/blog/' . $artikel->gambar))) {
                unlink(public_path('images/blog/' . $artikel->gambar));
            }

            $nama_gambar = time() . '_blog.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/blog'), $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        $artikel->update($data);

        return redirect()->route('artikel.index')->with('success', 'Artikel sukses diperbarui!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->gambar && file_exists(public_path('images/blog/' . $artikel->gambar))) {
            unlink(public_path('images/blog/' . $artikel->gambar));
        }

        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel sukses dihapus dari database!');
    }
}