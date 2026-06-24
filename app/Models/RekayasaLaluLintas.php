<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekayasaLaluLintas extends Model
{
    protected $table = 'rekayasa_lalu_lintas';

    protected $fillable = ['judul', 'deskripsi', 'gambar', 'urut', 'is_active'];

    protected $casts = [
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    public function lokasi()
    {
        return $this->hasMany(RekayasaLokasi::class, 'rekayasa_id')->orderBy('urut')->orderBy('id');
    }

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? \App\Support\Media::url($this->gambar) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
