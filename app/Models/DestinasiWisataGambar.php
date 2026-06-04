<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinasiWisataGambar extends Model
{
    protected $table = 'destinasi_wisata_gambar';

    protected $fillable = ['destinasi_wisata_id', 'gambar', 'caption', 'urut'];

    protected $casts = ['urut' => 'integer'];

    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_wisata_id');
    }
}
