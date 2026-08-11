<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\ProdukVarian;
use Illuminate\Support\Collection; // Perbaikan typo namespace dari Support5 ke Support
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
            // -----------------------------------------------------------------
            // FUNGSI PEMBERSIH KUNCI ARRAYS (Mengamankan Teks Penjelasan Excel)
            // -----------------------------------------------------------------
            $cleanedRow = [];
            foreach ($row as $key => $value) {
                // Deteksi awalan kunci array untuk mengabaikan teks panjang di dalam kurung
                if (str_starts_with($key, 'id_produk')) { $cleanedRow['id_produk'] = $value; }
                elseif (str_starts_with($key, 'id_varian')) { $cleanedRow['id_varian'] = $value; }
                elseif (str_starts_with($key, 'is_varian')) { $cleanedRow['is_varian'] = $value; }
                elseif (str_starts_with($key, 'kategori')) { $cleanedRow['kategori'] = $value; }
                elseif (str_starts_with($key, 'nama_produk')) { $cleanedRow['nama_produk'] = $value; }
                elseif (str_starts_with($key, 'harga_coret')) { $cleanedRow['harga_coret'] = $value; } // Wajib sebelum 'harga'
                elseif (str_starts_with($key, 'harga')) { $cleanedRow['harga'] = $value; }
                elseif (str_starts_with($key, 'stok')) { $cleanedRow['stok'] = $value; }
                elseif (str_starts_with($key, 'spesifikasi')) { $cleanedRow['spesifikasi'] = $value; }
                elseif (str_starts_with($key, 'deskripsi')) { $cleanedRow['deskripsi'] = $value; }
                elseif (str_starts_with($key, 'warna')) { $cleanedRow['warna'] = $value; }
                elseif (str_starts_with($key, 'ukuran')) { $cleanedRow['ukuran'] = $value; }
                elseif (str_starts_with($key, 'is_terlaris')) { $cleanedRow['is_terlaris'] = $value; }
                
                // PENERIMAAN DATA ATRIBUT LOGISTIK & PO BARU
                elseif (str_starts_with($key, 'berat_gr')) { $cleanedRow['berat_gr'] = $value; }
                elseif (str_starts_with($key, 'panjang_cm')) { $cleanedRow['panjang_cm'] = $value; }
                elseif (str_starts_with($key, 'lebar_cm')) { $cleanedRow['lebar_cm'] = $value; }
                elseif (str_starts_with($key, 'tinggi_cm')) { $cleanedRow['tinggi_cm'] = $value; }
                elseif (str_starts_with($key, 'maks_pembelian')) { $cleanedRow['maks_pembelian'] = $value; }
                elseif (str_starts_with($key, 'is_preorder')) { $cleanedRow['is_preorder'] = $value; }
                elseif (str_starts_with($key, 'waktu_preorder')) { $cleanedRow['waktu_preorder'] = $value; }
            }

            // Validasi lompat baris jika nama produk kosong
            if (empty($cleanedRow['nama_produk'])) {
                continue;
            }

            $isVariant  = isset($cleanedRow['is_varian']) && $cleanedRow['is_varian'] == 1;
            $idProduk   = isset($cleanedRow['id_produk']) ? $cleanedRow['id_produk'] : null;
            $idVarian   = isset($cleanedRow['id_varian']) ? $cleanedRow['id_varian'] : null;

            // -----------------------------------------------------------------
            // KONDISI A: PENGOLAHAN PRODUK UTAMA / INDUK (is_varian = 0)
            // -----------------------------------------------------------------
            if (!$isVariant) {
                
                // Data mapping terstruktur untuk tabel utama produk
                $produkFields = [
                    'kategori'       => strtolower($cleanedRow['kategori']),
                    'nama_produk'    => $cleanedRow['nama_produk'],
                    'harga'          => $cleanedRow['harga'],
                    'harga_coret'    => $cleanedRow['harga_coret'] ?? null,
                    'stok'           => isset($cleanedRow['stok']) ? (int)$cleanedRow['stok'] : 0,
                    'spesifikasi'    => $cleanedRow['spesifikasi'] ?? null,
                    'deskripsi'      => $cleanedRow['deskripsi'] ?? null,
                    'warna'          => $cleanedRow['warna'] ?? null,
                    'ukuran'         => $cleanedRow['ukuran'] ?? null,
                    'is_terlaris'    => $cleanedRow['is_terlaris'] ?? 0,
                    
                    // SUNTIKAN INTEGRASI DATA BARU (LOGISTIK & PRE-ORDER)
                    'berat'          => $cleanedRow['berat_gr'] ?? null,
                    'panjang'        => $cleanedRow['panjang_cm'] ?? null,
                    'lebar'          => $cleanedRow['lebar_cm'] ?? null,
                    'tinggi'         => $cleanedRow['tinggi_cm'] ?? null,
                    'maks_pembelian' => $cleanedRow['maks_pembelian'] ?? null,
                    'is_preorder'    => $cleanedRow['is_preorder'] ?? 0,
                    'waktu_preorder' => (isset($cleanedRow['is_preorder']) && $cleanedRow['is_preorder'] == 1) ? ($cleanedRow['waktu_preorder'] ?? null) : null,
                ];

                // SCENARIO A1: UPDATE DATA EKSISTING TEMPLATE EDIT MASSAL
                if ($idProduk && $produkExisting = Produk::find($idProduk)) {
                    $produkExisting->update($produkFields);
                    $currentParentProduct = $produkExisting;
                } 
                
                // SCENARIO A2: TAMBAH PRODUK INDUK BARU DARI TEMPLATE KOSONG
                else {
                    $produkFields['slug'] = Str::slug($cleanedRow['nama_produk']) . '-' . rand(100, 999);
                    $currentParentProduct = Produk::create($produkFields);
                }
            } 
            
            // -----------------------------------------------------------------
            // KONDISI B: PENGOLAHAN DATA VARIAN UKURAN (is_varian = 1)
            // -----------------------------------------------------------------
            else {
                
                // Array data untuk disuntikkan ke tabel pecahan varian ukuran aktif
                $varianFields = [
                    'ukuran'      => $cleanedRow['ukuran'],
                    'harga'       => $cleanedRow['harga'],
                    'harga_coret' => $cleanedRow['harga_coret'] ?? null,
                    'stok'        => isset($cleanedRow['stok']) ? (int)$cleanedRow['stok'] : 0,
                    
                    // SUNTIKAN DATA LOGISTIK INDIVIDU PER ITEM VARIAN
                    'berat'       => $cleanedRow['berat_gr'] ?? null,
                    'panjang'     => $cleanedRow['panjang_cm'] ?? null,
                    'lebar'       => $cleanedRow['lebar_cm'] ?? null,
                    'tinggi'      => $cleanedRow['tinggi_cm'] ?? null,
                ];
                
                // SCENARIO B1: UPDATE DATA ROW VARIAN EKSISTING
                if ($idVarian && $varianExisting = ProdukVarian::find($idVarian)) {
                    $varianExisting->update($varianFields);
                } 
                
                // SCENARIO B2: DAFTARKAN ROW BARIS VARIAN BARU KE KEPALA INDUK
                else {
                    $parent = $currentParentProduct ?: ($idProduk ? Produk::find($idProduk) : null);
                    
                    if ($parent) {
                        $parent->varians()->create($varianFields);

                        // Proteksi Otomatis: Jika ini pecahan varian pertama yang didaftarkan, 
                        // ubah stok induk ke 0 dan bersihkan ukuran/logistik induk agar tersinkronisasi.
                        if ($parent->varians()->count() === 1) {
                            $parent->update([
                                'stok'    => 0, 
                                'ukuran'  => null,
                                'berat'   => null,
                                'panjang' => null,
                                'lebar'   => null,
                                'tinggi'  => null
                            ]);
                        }
                    }
                }
            }
        }
    }
}