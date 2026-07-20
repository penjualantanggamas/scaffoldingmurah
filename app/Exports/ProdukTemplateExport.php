<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Membuat Baris Pertama (Header / Nama Kolom)
     * Kolom disamakan dengan template edit (tanpa kolom ID)
     */
    public function headings(): array
    {
        return [
            'kategori', 
            'nama_produk', 
            'harga', 
            'harga_coret',
            'stok',
            'spesifikasi', 
            'deskripsi', 
            'warna', 
            'ukuran',
            'is_terlaris',
            'is_varian' // <-- Tambahkan Kolom Detektor Status Varian Masal
        ];
    }

    public function array(): array
    {
        return [
            // STRUKTUR PRODUK MULTI-VARIAN (Contoh: Main Frame Set)
            // Baris 1 (Induk): is_varian diisi 0
            [
                'frame', 'Main Frame Set T190 Black', '295000', '340000', '0', 
                'Baja Hitam SNI Pipe 1.2 Inch', 'Satu set main frame kokoh', 'Hitam', '', '1', '0'
            ],
            // Baris 2 (Anak/Varian 1): is_varian diisi 1, nama_produk dikosongkan/diulang, ukuran diisi
            [
                'frame', 'Main Frame Set T190 Black', '295000', '340000', '45', 
                '', '', 'Hitam', 'Tinggi 1.9m', '0', '1'
            ],
            // Baris 3 (Anak/Varian 2): is_varian diisi 1
            [
                'frame', 'Main Frame Set T190 Black', '270000', '', '20', 
                '', '', 'Hitam', 'Tinggi 1.7m', '0', '1'
            ],
        ];
    }
}