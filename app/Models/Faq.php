<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $fillable = ['pertanyaan', 'jawaban', 'urut', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'urut' => 'integer'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('urut')->orderBy('id');
    }
}
