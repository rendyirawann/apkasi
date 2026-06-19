<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalKontak extends Model
{
    protected $table = 'rental_kontak';

    protected $fillable = ['rental_id', 'nama', 'no_hp', 'urut'];

    protected $casts = ['urut' => 'integer'];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }
}
