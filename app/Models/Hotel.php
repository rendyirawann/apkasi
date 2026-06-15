<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'nama', 'kategori', 'alamat', 'lat', 'lng', 'ketersediaan_kamar',
        'contact_wa', 'contact_person', 'contact_email', 'jarak', 'rating', 'bintang', 'image', 'maps_url',
        'is_lokasi_acara', 'urut', 'is_active',
    ];

    protected $casts = [
        'lat'                => 'float',
        'lng'                => 'float',
        'rating'             => 'float',
        'bintang'            => 'integer',
        'ketersediaan_kamar' => 'integer',
        'urut'               => 'integer',
        'is_lokasi_acara'    => 'boolean',
        'is_active'          => 'boolean',
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

    /** Daftar tipe kamar + harga. */
    public function kamar()
    {
        return $this->hasMany(HotelKamar::class, 'hotel_id')->orderBy('urut');
    }

    /** Harga termurah (Rp) dari tipe kamar yang sudah dimuat, atau null. */
    public function getHargaMulaiAttribute(): ?int
    {
        if (! $this->relationLoaded('kamar')) return null;
        $min = $this->kamar->whereNotNull('harga')->min('harga');
        return $min !== null ? (int) $min : null;
    }
}
