<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalMobil extends Model
{
    protected $table = 'rental_mobil';

    protected $fillable = ['rental_id', 'nama_mobil', 'jumlah_unit', 'urut'];

    protected $casts = [
        'jumlah_unit' => 'integer',
        'urut'        => 'integer',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }
}
