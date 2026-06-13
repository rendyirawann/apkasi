<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelKamar extends Model
{
    protected $table = 'hotel_kamar';

    protected $fillable = ['hotel_id', 'tipe', 'harga', 'urut'];

    protected $casts = [
        'harga' => 'integer',
        'urut'  => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
