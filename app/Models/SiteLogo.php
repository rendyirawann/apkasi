<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiteLogo extends Model
{
    protected $table = 'site_logos';

    protected $fillable = ['grup', 'gambar', 'alt', 'urut', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'urut' => 'integer'];

    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute(): ?string
    {
        $v = $this->gambar;
        if (! $v) return null;
        return Str::startsWith($v, ['http://', 'https://']) ? $v : asset(ltrim($v, '/'));
    }

    public function scopeGrup($q, string $grup)
    {
        return $q->where('grup', $grup)->where('is_active', true)->orderBy('urut')->orderBy('id');
    }
}
