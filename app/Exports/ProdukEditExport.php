<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukEditExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Mengatur Judul Kolom Excel Lengkap dengan Aturan Main Modul Kargo
     */
    public function headings(): array
    {
        return [
            'id_produk (JANGAN DIUBAH)',        
            'id_varian (JANGAN DIUBAH)',        
            'is_varian (0=Baris Induk Utama, 1=Baris Anak Varian)',        
            'kategori (frame/tubular/ringlock/kwikstage/bekisting)',
            'nama_produk',
            'harga (Angka Tanpa Titik)',
            'harga_coret',
            'stok',
            'spesifikasi',
            'deskripsi',
            'warna',
            'ukuran',
            'is_terlaris (0=Biasa, 1=Home Best Seller)',
            
            // ATRIBUT LOGISTIK & PRE-ORDER + PANDUAN
            'berat_gr (Wajib Angka Gram. Contoh: 15kg = 15000)',         
            'panjang_cm (Angka Bulat CM)',       
            'lebar_cm (Angka Bulat CM)',         
            'tinggi_cm (Angka Bulat CM)',        
            'maks_pembelian (Batas Order Kargo Truk per Transaksi)',   
            'is_preorder (0=Ready Stock Gudang, 1=Pre-Order Pabrik)',      
            'waktu_preorder (Pilih Angka Hari: 3 / 5 / 7 / 14 jika PO)'    
        ];
    }

    /**
     * Memetakan gabungan data Produk Utama dan Varian ke dalam Array Excel
     */
    public function array(): array
    {
        $dataExcel = [];
        $produks = Produk::with('varians')->get();

        foreach ($produks as $produk) {
            $hasVariant = $produk->varians->count() > 0;

            // 1. BARIS INDUK (is_varian = 0)
            $dataExcel[] = [
                $produk->id,
                '', 
                '0', 
                $produk->kategori,
                $produk->nama_produk,
                $produk->harga,
                $produk->harga_coret,
                $hasVariant ? 0 : ($produk->stok ?? 0), 
                $produk->spesifikasi,
                $produk->deskripsi,
                $produk->warna,
                $produk->ukuran,
                $produk->is_terlaris ? '1' : '0',
                $hasVariant ? '' : $produk->berat,        
                $hasVariant ? '' : $produk->panjang,      
                $hasVariant ? '' : $produk->lebar,        
                $hasVariant ? '' : $produk->tinggi,       
                $produk->maks_pembelian,                  
                $produk->is_preorder ? '1' : '0',         
                $produk->is_preorder ? $produk->waktu_preorder : '', 
            ];

            // 2. BARIS ANAK VARIAN (is_varian = 1)
            if ($hasVariant) {
                foreach ($produk->varians as $v) {
                    $dataExcel[] = [
                        $produk->id, 
                        $v->id,      
                        '1',         
                        $produk->kategori,
                        $produk->nama_produk . ' - ' . $v->ukuran, 
                        $v->harga,
                        $v->harga_coret,
                        $v->stok ?? 0,
                        '', 
                        '', 
                        $produk->warna,
                        $v->ukuran, 
                        '0', 
                        $v->berat,    
                        $v->panjang,  
                        $v->lebar,    
                        $v->tinggi,   
                        '',           
                        '',           
                        ''            
                    ];
                }
            }
        }

        return $dataExcel;
    }
}