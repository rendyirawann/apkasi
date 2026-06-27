<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalBanner extends Model
{
    protected $fillable = ['judul', 'gambar', 'urut', 'is_active'];

    protected $casts = [
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? \App\Support\Media::url($this->gambar) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
