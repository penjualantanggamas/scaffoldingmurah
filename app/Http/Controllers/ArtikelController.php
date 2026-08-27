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

    public function frontendIndex(Request $request)
    {
        $kategori = $request->get('kategori');
        
        $artikels = Artikel::when($kategori, function ($query, $kategori) {
                return $query->where('kategori', $kategori);
            })
            ->latest()
            ->paginate(6);

        return view('blog.index', compact('artikels'));
    }

    /**
     * Menampilkan detail artikel berdasarkan Prefix URL & Slug Dinamis
     */
    public function frontendShowCustom($prefix, $slug)
    {
        $artikel = Artikel::where('prefix_url', $prefix)
            ->where('slug', $slug)
            ->firstOrFail();
        
        $artikel->increment('views');

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
        $request->validate([
            'judul'            => 'required|string|max:255',
            'prefix_url'       => 'required|string|max:100',
            'slug'             => 'nullable|string|max:255|unique:artikels,slug',
            'kategori'         => 'required|string',
            'ringkasan'        => 'required|string|max:500',
            'konten'           => 'required|string',
            'gambar'           => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10072',
            'faqs'             => 'nullable|array',
            'faqs.*.pertanyaan'=> 'nullable|string',
            'faqs.*.jawaban'   => 'nullable|string',

            // Validasi Meta Tags SEO (Optional / Nullable)
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_author'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ], [
            'judul.required'      => 'Judul artikel wajib diisi.',
            'prefix_url.required' => 'Prefix URL wajib diisi.',
            'slug.unique'         => 'Slug URL sudah digunakan artikel lain.',
            'ringkasan.required'  => 'Ringkasan singkat wajib diisi.',
            'konten.required'     => 'Isi konten artikel lengkap wajib diisi.',
            'gambar.required'     => 'Banner utama artikel wajib diunggah.',
            'gambar.image'        => 'File banner harus berupa gambar.',
            'gambar.mimes'        => 'Format banner harus JPEG, PNG, JPG, WEBP, atau SVG.',
            'gambar.max'          => 'Ukuran banner terlalu besar! Maksimal 10 MB.',
        ]);

        $data = $request->except('faqs');

        // Normalisasi Prefix URL & Slug SEO
        $data['prefix_url'] = Str::slug($request->prefix_url);
        $data['slug']       = $request->filled('slug') 
            ? Str::slug($request->slug) 
            : Str::slug($request->judul);

        // Filter simpan FAQ yang tidak kosong saja
        $formattedFaqs = [];
        if ($request->has('faqs') && is_array($request->faqs)) {
            foreach ($request->faqs as $faq) {
                if (!empty($faq['pertanyaan']) && !empty($faq['jawaban'])) {
                    $formattedFaqs[] = [
                        'pertanyaan' => trim($faq['pertanyaan']),
                        'jawaban'    => trim($faq['jawaban']),
                    ];
                }
            }
        }
        $data['faqs'] = $formattedFaqs;

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

        $request->validate([
            'judul'            => 'required|string|max:255',
            'prefix_url'       => 'required|string|max:100',
            'slug'             => 'required|string|max:255|unique:artikels,slug,' . $id,
            'kategori'         => 'required|string',
            'ringkasan'        => 'required|string|max:500',
            'konten'           => 'required|string',
            'gambar'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10072',
            'faqs'             => 'nullable|array',
            'faqs.*.pertanyaan'=> 'nullable|string',
            'faqs.*.jawaban'   => 'nullable|string',

            // Validasi Meta Tags SEO (Optional / Nullable)
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string',
            'meta_author'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ], [
            'judul.required'      => 'Judul artikel wajib diisi.',
            'prefix_url.required' => 'Prefix URL wajib diisi.',
            'slug.required'       => 'Slug URL wajib diisi.',
            'slug.unique'         => 'Slug URL sudah digunakan oleh artikel lain.',
            'ringkasan.required'  => 'Ringkasan singkat wajib diisi.',
            'konten.required'     => 'Isi konten artikel lengkap wajib diisi.',
            'gambar.image'        => 'File banner harus berupa gambar.',
            'gambar.mimes'        => 'Format banner harus JPEG, PNG, JPG, WEBP, atau SVG.',
            'gambar.max'          => 'Ukuran banner terlalu besar! Maksimal 10 MB.',
        ]);

        $data = $request->except('faqs');

        // Normalisasi Prefix URL & Slug SEO saat Update
        $data['prefix_url'] = Str::slug($request->prefix_url);
        $data['slug']       = Str::slug($request->slug);

        // Filter simpan FAQ yang tidak kosong saja
        $formattedFaqs = [];
        if ($request->has('faqs') && is_array($request->faqs)) {
            foreach ($request->faqs as $faq) {
                if (!empty($faq['pertanyaan']) && !empty($faq['jawaban'])) {
                    $formattedFaqs[] = [
                        'pertanyaan' => trim($faq['pertanyaan']),
                        'jawaban'    => trim($faq['jawaban']),
                    ];
                }
            }
        }
        $data['faqs'] = $formattedFaqs;

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