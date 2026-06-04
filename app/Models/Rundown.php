<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rundown extends Model
{
    protected $table = 'rundown';

    protected $fillable = ['tanggal', 'label', 'urut', 'is_active'];

    protected $casts = [
        'tanggal'   => 'date',
        'is_active' => 'boolean',
        'urut'      => 'integer',
    ];

    protected $appends = ['tanggal_format', 'tanggal_input'];

    /** Tanggal format Indonesia: "Rabu, 01 Juli 2026". */
    public function getTanggalFormatAttribute(): ?string
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->locale('id')->translatedFormat('l, d F Y') : null;
    }

    /** Tanggal untuk input date (Y-m-d). */
    public function getTanggalInputAttribute(): ?string
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('Y-m-d') : null;
    }

    public function kegiatan()
    {
        return $this->hasMany(RundownKegiatan::class, 'rundown_id')->orderBy('urut');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
