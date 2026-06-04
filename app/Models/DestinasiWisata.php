<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinasiWisata extends Model
{
    protected $table = 'destinasi_wisata';

    protected $fillable = [
        'nama', 'alamat', 'deskripsi', 'rating', 'harga_tiket',
        'lat', 'lng', 'thumbnail', 'maps_url', 'is_lokasi_acara',
        'urut', 'is_active',
    ];

    protected $casts = [
        'rating'          => 'float',
        'lat'             => 'float',
        'lng'             => 'float',
        'is_lokasi_acara' => 'boolean',
        'is_active'       => 'boolean',
        'urut'            => 'integer',
    ];

    protected $appends = ['thumbnail_url'];

    /** URL thumbnail: http(s) apa adanya, atau dari storage jika hasil upload. */
    public function getThumbnailUrlAttribute(): ?string
    {
        $v = $this->thumbnail;
        if (! $v) return null;
        return \Illuminate\Support\Str::startsWith($v, ['http://', 'https://']) ? $v : asset('storage/' . ltrim($v, '/'));
    }

    public function gambar()
    {
        return $this->hasMany(DestinasiWisataGambar::class, 'destinasi_wisata_id')->orderBy('urut');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
