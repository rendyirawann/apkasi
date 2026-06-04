<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'category', 'name', 'slug', 'address', 'description',
        'phone', 'price_range', 'rating', 'image',
        'lat', 'lng', 'maps_url', 'link', 'sort', 'is_active',
    ];

    protected $casts = [
        'lat'       => 'float',
        'lng'       => 'float',
        'rating'    => 'float',
        'is_active' => 'boolean',
        'sort'      => 'integer',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeCategory($q, string $cat)
    {
        return $q->where('category', $cat);
    }
}
