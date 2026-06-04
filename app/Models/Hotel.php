<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'nama', 'alamat', 'lat', 'lng', 'ketersediaan_kamar',
        'contact_wa', 'contact_email', 'rating', 'image', 'maps_url',
        'is_lokasi_acara', 'urut', 'is_active',
    ];

    protected $casts = [
        'lat'                => 'float',
        'lng'                => 'float',
        'rating'             => 'float',
        'ketersediaan_kamar' => 'integer',
        'urut'               => 'integer',
        'is_lokasi_acara'    => 'boolean',
        'is_active'          => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
