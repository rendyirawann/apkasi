<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['nama', 'alamat', 'telepon', 'kontak_wa', 'deskripsi', 'lat', 'lng', 'maps_url', 'urut', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'urut'      => 'integer',
        'lat'       => 'float',
        'lng'       => 'float',
    ];

    public function mobil()
    {
        return $this->hasMany(RentalMobil::class, 'rental_id')->orderBy('urut');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
