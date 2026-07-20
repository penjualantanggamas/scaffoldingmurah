<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukEditExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Mengatur Judul Kolom (Header) Excel
     */
    public function headings(): array
    {
        return [
            'id_produk',        // ID Produk Utama di database
            'id_varian',        // ID Varian (Kosong jika produk tunggal / baris induk)
            'is_varian',        // Penanda (0 = Produk Utama, 1 = Varian Ukuran)
            'kategori',
            'nama_produk',
            'harga',
            'harga_coret',
            'stok',
            'spesifikasi',
            'deskripsi',
            'warna',
            'ukuran',
            'is_terlaris'
        ];
    }

    /**
     * Memetakan gabungan data Produk Utama dan Varian ke dalam Array Excel
     */
    public function array(): array
    {
        $dataExcel = [];
        
        // Ambil semua produk beserta relasi variansnya
        $produks = Produk::with('varians')->get();

        foreach ($produks as $produk) {
            $hasVariant = $produk->varians->count() > 0;

            // 1. TULIS BARIS UTAMA / INDUK
            $dataExcel[] = [
                $produk->id,
                '', // id_varian kosong untuk baris induk
                '0', // is_varian = 0 (Induk)
                $produk->kategori,
                $produk->nama_produk,
                $produk->harga,
                $produk->harga_coret,
                $hasVariant ? 0 : ($produk->stok ?? 0), // Jika punya varian, stok induk diset 0
                $produk->spesifikasi,
                $produk->deskripsi,
                $produk->warna,
                $produk->ukuran,
                $produk->is_terlaris ? '1' : '0',
            ];

            // 2. TULIS BARIS ANAK / VARIAN (Jika produk memiliki varian)
            if ($hasVariant) {
                foreach ($produk->varians as $v) {
                    $dataExcel[] = [
                        $produk->id, // Tetap bawa ID induk agar tahu ini milik siapa
                        $v->id,      // ID Varian disimpan sebagai kunci update
                        '1',         // is_varian = 1 (Varian)
                        $produk->kategori,
                        $produk->nama_produk . ' - ' . $v->ukuran, // Nama produk bantu dengan teks ukuran
                        $v->harga,
                        $v->harga_coret,
                        $v->stok ?? 0,
                        '', // Spesifikasi varian kosong (ikut induk)
                        '', // Deskripsi varian kosong (ikut induk)
                        $produk->warna,
                        $v->ukuran, // Ukuran varian riil
                        '0', // status terlaris ikut induk saja
                    ];
                }
            }
        }

        return $dataExcel;
    }
}