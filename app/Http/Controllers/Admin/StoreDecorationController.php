<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreDecoration;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StoreDecorationController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Builder Dekorasi Toko
     */
    public function index()
    {
        if (StoreDecoration::count() == 0) {
            $this->seedDefaultBlocks();
        }

        // 1. Filter tipe banner non-beranda
        $categoryTypes = [
            'catalog_header_banner', 
            'catalog_middle_banner',
            'category_banner_frame',
            'category_banner_ringlock',
            'category_banner_tubular',
            'category_banner_kwikstage',
            'category_banner_bekisting'
        ];

        // Filter blok khusus Beranda
        $blocks = StoreDecoration::whereNotIn('type', $categoryTypes)
            ->orderBy('urutan', 'asc')
            ->get();

        // 2. Banner Katalog Utama
        $catalogHeaderBanner = StoreDecoration::firstOrCreate(
            ['type' => 'catalog_header_banner'],
            ['judul' => 'Top Banner Header Katalog', 'content' => [], 'urutan' => 99, 'is_active' => true]
        );

        $catalogMiddleBanner = StoreDecoration::firstOrCreate(
            ['type' => 'catalog_middle_banner'],
            ['judul' => 'Banner Penengah Katalog', 'content' => [], 'urutan' => 100, 'is_active' => true]
        );

        // 3. Banner Kategori Spesifik
        $categories = ['frame', 'ringlock', 'tubular', 'kwikstage', 'bekisting'];
        $categoryBanners = [];
        foreach ($categories as $cat) {
            $categoryBanners[$cat] = StoreDecoration::firstOrCreate(
                ['type' => 'category_banner_' . $cat],
                [
                    'judul' => 'Banner Kategori ' . ucfirst($cat) . ' System', 
                    'content' => [
                        'judul_banner' => ucfirst($cat) . ' System',
                        'subjudul_banner' => 'Pilihan perancah & komponen ' . ucfirst($cat) . ' berkualitas standar industri'
                    ], 
                    'urutan' => 101, 
                    'is_active' => true
                ]
            );
        }

        $produks = Produk::select('id', 'nama_produk', 'harga', 'harga_coret', 'gambar', 'kategori')->get();

        return view('admin.dekorasi.index', compact(
            'blocks', 
            'catalogHeaderBanner', 
            'catalogMiddleBanner', 
            'categoryBanners',
            'produks'
        ));
    }

    /**
     * Menambah Blok Dekorasi Baru (Khusus Beranda)
     */
    public function storeBlock(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'judul' => 'nullable|string|max:255',
        ]);

        $lastUrutan = StoreDecoration::whereNotIn('type', ['catalog_header_banner', 'catalog_middle_banner'])->max('urutan') ?? 0;

        $defaultJudul = [
            'banner_slider'     => 'Hero Banner Slider Utama',
            'category_pills'    => 'Kategori Sistem Scaffolding',
            'product_promo'     => 'Rekomendasi Promo Terbaik',
            'product_hot'       => 'Produk Terlaris',
            'single_banner'     => 'Banner Iklan Promosi',
            'usp_text'          => 'Kenapa Pilih Tangga Mas',
        ];

        StoreDecoration::create([
            'type'      => $request->type,
            'judul'     => $request->judul ?? ($defaultJudul[$request->type] ?? 'Blok Baru'),
            'content'   => [],
            'urutan'    => $lastUrutan + 1,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Komponen dekorasi beranda berhasil ditambahkan!');
    }

    /**
     * Memperbarui Isi Konten Blok (Beranda, Katalog, & Banner Kategori)
     */
    public function updateBlock(Request $request, $id)
    {
        $block = StoreDecoration::findOrFail($id);
        $content = $block->content ?? [];

        // Safe decode jika di DB masih tersimpan sebagai string JSON
        while (is_string($content)) {
            $content = json_decode($content, true) ?? [];
        }

        if (!is_array($content)) {
            $content = [];
        }

        $block->judul = $request->input('judul', $block->judul);

        // 1. BLOK BANNER SLIDER
        if ($block->type === 'banner_slider') {
            if ($request->has('slides') && is_array($request->slides)) {
                foreach ($content as $key => $slide) {
                    $slideId = $slide['id'] ?? null;
                    if ($slideId && isset($request->slides[$slideId])) {
                        $content[$key]['link'] = $request->slides[$slideId]['link'] ?? '#';
                        $content[$key]['alt']  = $request->slides[$slideId]['alt'] ?? '';
                    }
                }
            }

            if ($request->hasFile('new_banner')) {
                $request->validate([
                    'new_banner' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
                ]);

                $uploadPath = public_path('images/banners/webp');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true, true);
                }

                $file = $request->file('new_banner');
                $fileName = 'slider_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                $content[] = [
                    'id'    => uniqid(),
                    'image' => $fileName,
                    'link'  => $request->input('banner_link', '#'),
                    'alt'   => $request->input('banner_alt', 'Banner Tangga Mas Scaffolding'),
                ];
            }
        } 
        // 2. BLOK BANNER TUNGGAL
        elseif ($block->type === 'single_banner') {
            if ($request->hasFile('single_banner_image')) {
                $request->validate([
                    'single_banner_image' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
                ]);

                $uploadPath = public_path('images/banners/webp');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true, true);
                }

                if (isset($content['image']) && !empty($content['image']) && File::exists($uploadPath . '/' . $content['image'])) {
                    File::delete($uploadPath . '/' . $content['image']);
                }

                $file = $request->file('single_banner_image');
                $fileName = 'single_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);

                $content['image'] = $fileName;
            }
            $content['link'] = $request->input('single_banner_link', '#');
            $content['alt']  = $request->input('single_banner_alt', 'Banner Promosi Tangga Mas');
        } 
        // 3. BLOK BANNER KATALOG & BANNER KATEGORI SPESIFIK
        elseif (in_array($block->type, ['catalog_header_banner', 'catalog_middle_banner']) || strpos($block->type, 'category_banner_') === 0) {
            
            // Cek jika ada error upload dari PHP
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_INI_SIZE) {
                return redirect()->back()
                    ->withErrors(['banner_image' => 'Ukuran file gambar terlalu besar untuk server PHP Anda. Silakan kompres foto Anda atau gunakan format JPG/WEBP di bawah 2MB.'])
                    ->with('active_tab', $request->input('active_tab', 'categories'));
            }

            if ($request->hasFile('banner_image')) {
                $request->validate([
                    'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
                ], [
                    'banner_image.required' => 'Pilih file gambar banner terlebih dahulu.',
                    'banner_image.image'    => 'File harus berupa gambar.',
                    'banner_image.mimes'    => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
                    'banner_image.max'      => 'Ukuran gambar maksimal 10 MB.',
                ]);

                $uploadPath = public_path('images/banners/webp');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true, true);
                }

                if (isset($content['image']) && !empty($content['image']) && File::exists($uploadPath . '/' . $content['image'])) {
                    File::delete($uploadPath . '/' . $content['image']);
                }

                $file = $request->file('banner_image');
                $extension = $file->getClientOriginalExtension() ?: 'webp';
                $fileName = $block->type . '_' . time() . '.' . $extension;
                $file->move($uploadPath, $fileName);

                $content['image'] = $fileName;
            }

            $content['judul_banner']    = $request->input('judul_banner', $block->judul);
            $content['subjudul_banner'] = $request->input('subjudul_banner', '');
            $content['link']            = $request->input('banner_link', '#');
            $content['alt']             = $request->input('banner_alt', 'Banner Tangga Mas Scaffolding');
        }
        // 4. BLOK PRODUK (PROMO / HOT)
        elseif (in_array($block->type, ['product_promo', 'product_hot'])) {
            $content['product_ids'] = $request->input('product_ids', []);
        } 
        // 5. BLOK TEKS USP
        elseif ($block->type === 'usp_text') {
            $content['deskripsi'] = $request->input('usp_deskripsi', '');
        }
        // 6. BLOK KATEGORI PILLS
        elseif ($block->type === 'category_pills') {
            $content['categories'] = $request->input('categories', []);
        }

        $block->content = is_array($content) && isset($content[0]) ? array_values($content) : $content;
        $block->save();

        $activeTab = $request->input('active_tab', 'home');

        return redirect()->back()
            ->with('success', 'Perubahan banner dekorasi berhasil disimpan!')
            ->with('active_tab', $activeTab);
    }

    /**
     * Hapus Slide Gambar Spesifik dari Banner Slider
     */
    public function deleteSlide($id, $slideId)
    {
        $block = StoreDecoration::findOrFail($id);
        $content = $block->content ?? [];

        $updatedContent = [];
        foreach ($content as $slide) {
            if (isset($slide['id']) && $slide['id'] == $slideId) {
                if (isset($slide['image']) && File::exists(public_path('images/banners/webp/' . $slide['image']))) {
                    File::delete(public_path('images/banners/webp/' . $slide['image']));
                }
            } else {
                $updatedContent[] = $slide;
            }
        }

        $block->content = array_values($updatedContent);
        $block->save();

        return redirect()->back()->with('success', 'Slide banner berhasil dihapus!');
    }

    /**
     * Mengubah Status Aktif / Sembunyi Blok
     */
    public function toggleActive($id)
    {
        $block = StoreDecoration::findOrFail($id);
        $block->is_active = !$block->is_active;
        $block->save();

        $statusText = $block->is_active ? 'ditampilkan' : 'disembunyikan';
        return redirect()->back()->with('success', "Blok '{$block->judul}' berhasil {$statusText}!");
    }

    /**
     * Menghapus Blok Komponen
     */
    public function destroy($id)
    {
        $block = StoreDecoration::findOrFail($id);

        if ($block->type === 'banner_slider' && is_array($block->content)) {
            foreach ($block->content as $slide) {
                if (isset($slide['image']) && File::exists(public_path('images/banners/webp/' . $slide['image']))) {
                    File::delete(public_path('images/banners/webp/' . $slide['image']));
                }
            }
        } elseif ($block->type === 'single_banner' && isset($block->content['image'])) {
            if (File::exists(public_path('images/banners/webp/' . $block->content['image']))) {
                File::delete(public_path('images/banners/webp/' . $block->content['image']));
            }
        }

        $block->delete();

        return redirect()->back()->with('success', 'Blok komponen berhasil dihapus!');
    }

    /**
     * Menyimpan Urutan Baru Blok Komponen
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
        ]);

        foreach ($request->orders as $index => $id) {
            StoreDecoration::where('id', $id)->update(['urutan' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan tata letak berhasil diperbarui!']);
    }

    /**
     * Private Helper: Seed Blok Awal Mengikuti Template Eksisting
     */
    private function seedDefaultBlocks()
    {
        $defaults = [
            [
                'type' => 'banner_slider',
                'judul' => 'Hero Banner Slider Utama',
                'urutan' => 1,
                'content' => [
                    ['id' => 's1', 'image' => 'mainbanner2.webp', 'link' => '#', 'alt' => 'Banner Tangga Mas Scaffolding 1'],
                    ['id' => 's2', 'image' => 'mainbanner1.webp', 'link' => '#', 'alt' => 'Banner Tangga Mas Scaffolding 2'],
                    ['id' => 's3', 'image' => 'mainbanner3.webp', 'link' => '#', 'alt' => 'Banner Tangga Mas Scaffolding 3'],
                ]
            ],
            [
                'type' => 'category_pills',
                'judul' => 'Kategori Sistem Scaffolding',
                'urutan' => 2,
                'content' => []
            ],
            [
                'type' => 'product_promo',
                'judul' => 'Rekomendasi Promo Terbaik',
                'urutan' => 3,
                'content' => ['product_ids' => []]
            ],
            [
                'type' => 'product_hot',
                'judul' => 'Produk Terlaris',
                'urutan' => 4,
                'content' => ['product_ids' => []]
            ],
            [
                'type' => 'single_banner',
                'judul' => 'Banner Promosi Bawah',
                'urutan' => 5,
                'content' => ['image' => 'bannerbawah.webp', 'link' => '#', 'alt' => 'Banner Promosi Tangga Mas Scaffolding']
            ],
            [
                'type' => 'usp_text',
                'judul' => 'Kenapa Pilih Tangga Mas',
                'urutan' => 6,
                'content' => ['deskripsi' => 'Sebagai produsen perancah terbesar nomor satu di Jawa Timur, PT Tangga Mas Jaya Makmur memproduksi produk berkualitas standar proyek konstruksi besar dengan sertifikasi resmi dan harga yang sangat bersaing.']
            ]
        ];

        foreach ($defaults as $def) {
            StoreDecoration::create([
                'type' => $def['type'],
                'judul' => $def['judul'],
                'content' => $def['content'],
                'urutan' => $def['urutan'],
                'is_active' => true,
            ]);
        }
    }
}