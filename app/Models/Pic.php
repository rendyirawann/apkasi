<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $fillable = ['provinsi_id', 'nama', 'lo_kecamatan', 'lo_instansi', 'no_hp', 'urut', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'urut'      => 'integer',
    ];

    public function provinsi()
    {
        return $this->belongsTo(WilayahProvinsi::class, 'provinsi_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
