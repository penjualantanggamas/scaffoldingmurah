<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVarian extends Model
{
    // Tambahkan 'stok' di dalam array fillable
    protected $fillable = ['produk_id', 'ukuran', 'harga', 'harga_coret', 'stok', 'gambar'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}