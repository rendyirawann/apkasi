<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedung';

    protected $fillable = [
        'nama', 'alamat', 'lat', 'lng', 'maps_url',
        'image', 'is_lokasi_acara', 'urut', 'is_active',
    ];

    protected $casts = [
        'lat'             => 'float',
        'lng'             => 'float',
        'urut'            => 'integer',
        'is_lokasi_acara' => 'boolean',
        'is_active'       => 'boolean',
    ];

    protected $appends = ['image_url'];

    /** URL gambar: pakai apa adanya jika http(s), atau dari storage jika hasil upload. */
    public function getImageUrlAttribute(): ?string
    {
        $v = $this->image;
        if (! $v) return null;
        return \Illuminate\Support\Str::startsWith($v, ['http://', 'https://']) ? $v : asset('storage/' . ltrim($v, '/'));
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
