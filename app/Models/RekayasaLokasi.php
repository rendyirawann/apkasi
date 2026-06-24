<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekayasaLokasi extends Model
{
    protected $table = 'rekayasa_lokasi';

    protected $fillable = ['rekayasa_id', 'nama', 'urut'];

    protected $casts = [
        'urut' => 'integer',
    ];

    public function rekayasa()
    {
        return $this->belongsTo(RekayasaLaluLintas::class, 'rekayasa_id');
    }
}
