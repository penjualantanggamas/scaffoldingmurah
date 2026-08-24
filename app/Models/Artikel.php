<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    // Mengizinkan kolom-kolom ini diisi secara massal saat Artikel::create()
    protected $fillable = [
        'judul', 
        'slug', 
        'kategori', 
        'ringkasan', 
        'konten', 
        'gambar', 
        'faqs',
        'views',
        'meta_title', 
        'meta_keywords', 
        'meta_author', 
        'meta_description'
    ];

    protected $casts = [
        'faqs' => 'array',
    ];
}