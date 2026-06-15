<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kuliner extends Model
{
    protected $table = 'kuliner';

    protected $fillable = [
        'nama', 'jenis_kuliner', 'halal', 'alamat', 'lat', 'lng', 'rating', 'image', 'maps_url', 'urut', 'is_active',
    ];

    protected $casts = [
        'lat'       => 'float',
        'lng'       => 'float',
        'rating'    => 'float',
        'halal'     => 'boolean',
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        $v = $this->image;
        if (! $v) return null;
        return Str::startsWith($v, ['http://', 'https://']) ? $v : asset('storage/' . ltrim($v, '/'));
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
