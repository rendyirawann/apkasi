<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RundownKegiatan extends Model
{
    protected $table = 'rundown_kegiatan';

    protected $fillable = ['rundown_id', 'waktu', 'kegiatan', 'lokasi', 'rincian', 'urut'];

    protected $casts = ['urut' => 'integer'];

    public function rundown()
    {
        return $this->belongsTo(Rundown::class, 'rundown_id');
    }
}
