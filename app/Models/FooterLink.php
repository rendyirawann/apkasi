<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $table = 'footer_links';

    protected $fillable = ['kolom', 'label', 'url', 'urut', 'is_active'];

    protected $casts = [
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
