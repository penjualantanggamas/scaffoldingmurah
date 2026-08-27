<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'provinsi',
        'kota',
        'vehicle_id',
        'biaya_pengiriman',
        'biaya_per_km',
        'is_aktif',
    ];

    /**
     * Relasi Many-to-One ke Vehicle:
     * Setiap record tarif pengiriman terikat pada 1 Jenis Armada.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}