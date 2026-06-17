<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterColumn extends Model
{
    protected $table = 'footer_columns';

    protected $fillable = ['judul', 'urut', 'is_active'];

    protected $casts = [
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    public function links()
    {
        return $this->hasMany(FooterLink::class)->orderBy('urut')->orderBy('id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
