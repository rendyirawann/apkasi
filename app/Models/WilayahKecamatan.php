<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKecamatan extends Model
{
    protected $table = 'wilayah_kecamatan';

    protected $fillable = ['nama', 'urut', 'is_active'];

    protected $casts = [
        'urut'      => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
