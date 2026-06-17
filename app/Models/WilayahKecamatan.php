<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKecamatan extends Model
{
    protected $table = 'wilayah_kecamatan';

    // id = kode BPS (di-set eksplisit, bukan auto-increment)
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'wilayah_kabupaten_id', 'nama'];

    protected $casts = [
        'id'                   => 'integer',
        'wilayah_kabupaten_id' => 'integer',
    ];

    /** Kode BPS Kabupaten Deli Serdang (tuan rumah acara). */
    public const KABUPATEN_DELI_SERDANG = 1212;

    public function scopeDeliSerdang($q)
    {
        return $q->where('wilayah_kabupaten_id', self::KABUPATEN_DELI_SERDANG);
    }
}
