<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahProvinsi extends Model
{
    protected $table = 'wilayah_provinsi';

    // id = kode BPS (mis. 11, 12, ... 97), bukan auto-increment.
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'nama'];

    public function pic()
    {
        return $this->hasOne(Pic::class, 'provinsi_id');
    }
}
