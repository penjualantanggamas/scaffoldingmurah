<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDecoration extends Model
{
    use HasFactory;

    protected $table = 'store_decorations';

    protected $fillable = [
        'type',
        'judul',
        'content',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];
}