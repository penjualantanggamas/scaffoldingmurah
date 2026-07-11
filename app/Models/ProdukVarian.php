<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVarian extends Model
{
    protected $fillable = ['produk_id', 'ukuran', 'harga', 'harga_coret', 'gambar'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}