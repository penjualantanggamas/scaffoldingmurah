<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'judul',
        'prefix_url',
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

    /**
     * Accessor URL otomatis untuk Artikel
     */
    public function getUrlAttribute()
    {
        return route('blog.show', [
            'prefix' => $this->prefix_url ?? 'jualscaffolding',
            'slug'   => $this->slug,
        ]);
    }
}