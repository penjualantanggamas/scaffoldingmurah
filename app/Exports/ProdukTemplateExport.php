<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Membuat Baris Pertama (Header) Lengkap dengan Panduan Aturan Isi
     */
    public function headings(): array
    {
        return [
            'kategori (Pilih: frame/tubular/ringlock/kwikstage/bekisting)', 
            'nama_produk', 
            'harga (Angka Tanpa Titik)', 
            'harga_coret (Kosongkan jika tidak diskon)',
            'stok (Induk diisi 0 jika ada varian)',
            'spesifikasi', 
            'deskripsi', 
            'warna', 
            'ukuran (Isi hanya di baris anak varian)',
            'is_terlaris (0=Produk Biasa, 1=Tampil di Home Best Seller)',
            'is_varian (0=Baris Induk Utama, 1=Baris Anak Varian)', 
            
            // ATRIBUT LOGISTIK & PRE-ORDER + PANDUAN
            'berat_gr (Wajib Angka Gram. Contoh: 12kg = 12000)',          
            'panjang_cm (Angka Bulat CM)',        
            'lebar_cm (Angka Bulat CM)',          
            'tinggi_cm (Angka Bulat CM)',         
            'maks_pembelian (Batas Order Kargo Truk per Transaksi)',    
            'is_preorder (0=Ready Stock Gudang, 1=Pre-Order Pabrik)',       
            'waktu_preorder (Pilih Angka Hari: 3 / 5 / 7 / 14 jika PO)'     
        ];
    }

    /**
     * Contoh Struktur Isian untuk Ditiru Admin (Main Frame Set PO 5 Hari)
     */
    public function array(): array
    {
        return [
            // BARIS 1: KEPALA INDUK (is_varian = 0, is_preorder = 1, waktu_preorder = 5)
            [
                'frame', 
                'Main Frame Set T190 Black', 
                '295000', 
                '340000', 
                '0', 
                'Baja Hitam SNI Pipe 1.2 Inch', 
                'Satu set main frame kokoh standar keamanan proyek K3 nasional.', 
                'Hitam', 
                '', 
                '1', 
                '0', // 0 = Ini baris kepala produk utama
                
                // Logistik dikosongkan karena ikut pecahan anak, setting PO diatur di sini
                '',   
                '',   
                '',   
                '',   
                '50', 
                '1', // 1 = Produk ini diatur sebagai Pre-Order
                '5'  // 5 = Estimasi pengerjaan pabrik 5 hari kerja
            ],
            
            // BARIS 2: ANAK VARIAN 1 (is_varian = 1, Berat & Dimensi wajib diisi riil)
            [
                'frame', 
                'Main Frame Set T190 Black', 
                '295000', 
                '340000', 
                '45', 
                '', 
                '', 
                'Hitam', 
                'Tinggi 1.9m', 
                '0', 
                '1', // 1 = Ini anak pecahan ukuran varian
                
                // Data fisik kargo varian 1
                '11000', 
                '190',   
                '120',   
                '10',    
                '',      // Parameter PO kosong (sudah ikut kepala induk di atas)
                '',      
                ''       
            ],
            
            // BARIS 3: ANAK VARIAN 2
            [
                'frame', 
                'Main Frame Set T190 Black', 
                '270000', 
                '', 
                '20', 
                '', 
                '', 
                'Hitam', 
                'Tinggi 1.7m', 
                '0', 
                '1', // 1 = Ini anak pecahan ukuran varian
                
                // Data fisik kargo varian 2
                '9500',  
                '170',   
                '120',   
                '10',    
                '',      
                '',      
                ''       
            ],
        ];
    }
}