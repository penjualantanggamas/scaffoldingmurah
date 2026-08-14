<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['produk_id', 'foto', 'urutan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}