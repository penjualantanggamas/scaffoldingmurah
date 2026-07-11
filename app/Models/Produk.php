<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';
    
    // Mengizinkan semua kolom diisi (atau sesuaikan jika ingin menggunakan $fillable)
    protected $guarded = [];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function varians()
    {
        return $this->hasMany(ProdukVarian::class, 'produk_id');
    }
    
}