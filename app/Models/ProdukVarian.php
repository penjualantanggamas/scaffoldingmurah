<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVarian extends Model
{
    protected $table = 'produk_varians';

    // Perbarui fillable untuk mengizinkan kolom dimensi logistik per item varian
    protected $fillable = [
        'produk_id', 
        'ukuran', 
        'harga', 
        'harga_coret', 
        'stok', 
        'gambar',
        
        // SUNTIKAN KOLOM BARU (LOGISTIK)
        'berat',
        'panjang',
        'lebar',
        'tinggi'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}