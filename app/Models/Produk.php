<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'slug',
        'kategori',
        'spesifikasi',
        'deskripsi',
        'harga',
        'harga_coret',
        'stok',
        'warna',
        'ukuran',
        'gambar',
        'is_terlaris',
        'berat',
        'panjang',
        'lebar',
        'tinggi',
        'maks_pembelian',
        'is_preorder',
        'waktu_preorder'
    ];

    // Relasi Varian
    public function varians()
    {
        return $this->hasMany(ProdukVarian::class, 'produk_id');
    }

    // Relasi Galeri Foto Multiple
    public function galeri()
    {
        return $this->hasMany(ProductImage::class, 'produk_id')->orderBy('urutan', 'asc');
    }
}