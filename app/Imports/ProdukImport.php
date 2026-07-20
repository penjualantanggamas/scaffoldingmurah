<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\ProdukVarian;
use Illuminate\Support5\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Menyimpan instance produk induk aktif untuk baris varian baru
        $currentParentProduct = null;

        foreach ($rows as $row) {
            if (empty($row['nama_produk'])) {
                continue;
            }

            $isVariant  = isset($row['is_varian']) && $row['is_varian'] == 1;
            $idProduk   = isset($row['id_produk']) ? $row['id_produk'] : null;
            $idVarian   = isset($row['id_varian']) ? $row['id_varian'] : null;

            // -----------------------------------------------------------------
            // KONDISI A: PENGOLAHAN PRODUK UTAMA / INDUK (is_varian = 0)
            // -----------------------------------------------------------------
            if (!$isVariant) {
                
                // Skenario A1: UPDATE DATA EKSISTING (Jika ada id_produk)
                if ($idProduk && $produkExisting = Produk::find($idProduk)) {
                    $produkExisting->update([
                        'kategori'    => strtolower($row['kategori']),
                        'nama_produk' => $row['nama_produk'],
                        'harga'       => $row['harga'],
                        'harga_coret' => $row['harga_coret'] ?? null,
                        'stok'        => isset($row['stok']) ? (int)$row['stok'] : 0,
                        'spesifikasi' => $row['spesifikasi'] ?? null,
                        'deskripsi'   => $row['deskripsi'] ?? null,
                        'warna'       => $row['warna'] ?? null,
                        'ukuran'      => $row['ukuran'] ?? null,
                        'is_terlaris' => $row['is_terlaris'] ?? 0,
                    ]);
                    $currentParentProduct = $produkExisting;
                } 
                
                // Skenario A2: TAMBAH PRODUK INDUK BARU
                else {
                    $slug = Str::slug($row['nama_produk']) . '-' . rand(100, 999);
                    $currentParentProduct = Produk::create([
                        'kategori'    => strtolower($row['kategori']),
                        'nama_produk' => $row['nama_produk'],
                        'harga'       => $row['harga'],
                        'harga_coret' => $row['harga_coret'] ?? null,
                        'stok'        => isset($row['stok']) ? (int)$row['stok'] : 0,
                        'spesifikasi' => $row['spesifikasi'] ?? null,
                        'deskripsi'   => $row['deskripsi'] ?? null,
                        'warna'       => $row['warna'] ?? null,
                        'ukuran'      => $row['ukuran'] ?? null,
                        'is_terlaris' => $row['is_terlaris'] ?? 0,
                        'slug'        => $slug,
                    ]);
                }
            } 
            
            // -----------------------------------------------------------------
            // KONDISI B: PENGOLAHAN DATA VARIAN UKURAN (is_varian = 1)
            // -----------------------------------------------------------------
            else {
                
                // Skenario B1: UPDATE DATA VARIAN EKSISTING (Jika ada id_varian)
                if ($idVarian && $varianExisting = ProdukVarian::find($idVarian)) {
                    $varianExisting->update([
                        'ukuran'      => $row['ukuran'],
                        'harga'       => $row['harga'],
                        'harga_coret' => $row['harga_coret'] ?? null,
                        'stok'        => isset($row['stok']) ? (int)$row['stok'] : 0,
                    ]);
                } 
                
                // Skenario B2: BARIS INPUT VARIAN BARU UNTUK INDUK DI ATASNYA
                else {
                    // Cari induk: dari variabel memori atau dari id_produk di baris Excel
                    $parent = $currentParentProduct ?: ($idProduk ? Produk::find($idProduk) : null);
                    
                    if ($parent) {
                        $parent->varians()->create([
                            'ukuran'      => $row['ukuran'],
                            'harga'       => $row['harga'],
                            'harga_coret' => $row['harga_coret'] ?? null,
                            'stok'        => isset($row['stok']) ? (int)$row['stok'] : 0,
                        ]);

                        // Jika ini baru varian pertama yang masuk, paksa stok induk bernilai 0
                        if ($parent->varians()->count() === 1) {
                            $parent->update(['stok' => 0, 'ukuran' => null]);
                        }
                    }
                }
            }
        }
    }
}