<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_armada',
        'max_berat_kg',
        'max_volume_m3',
        'urutan',
        'is_aktif',
    ];

    /**
     * Relasi One-to-Many ke ShippingRate:
     * 1 Jenis Armada memiliki banyak tarif pengiriman di berbagai kota.
     */
    public function shippingRates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}