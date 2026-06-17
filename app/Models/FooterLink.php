<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $table = 'footer_links';

    protected $fillable = ['footer_column_id', 'label', 'url', 'icon', 'urut', 'is_active'];

    protected $casts = [
        'footer_column_id' => 'integer',
        'urut'             => 'integer',
        'is_active'        => 'boolean',
    ];

    public function column()
    {
        return $this->belongsTo(FooterColumn::class, 'footer_column_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    /**
     * Deteksi platform sosial media dari URL -> slug Simple Icons (utk tampilkan logo).
     * Mengembalikan null jika bukan link sosmed yang dikenal.
     */
    public static function detectIcon(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host);
        if ($host === '') {
            return null;
        }

        $map = [
            'facebook.com'  => 'facebook',
            'fb.com'        => 'facebook',
            'fb.me'         => 'facebook',
            'instagram.com' => 'instagram',
            'twitter.com'   => 'x',
            'x.com'         => 'x',
            'youtube.com'   => 'youtube',
            'youtu.be'      => 'youtube',
            'tiktok.com'    => 'tiktok',
            'wa.me'         => 'whatsapp',
            'whatsapp.com'  => 'whatsapp',
            't.me'          => 'telegram',
            'telegram.me'   => 'telegram',
            'telegram.org'  => 'telegram',
            'linkedin.com'  => 'linkedin',
            'threads.net'   => 'threads',
            'threads.com'   => 'threads',
        ];

        foreach ($map as $domain => $slug) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return $slug;
            }
        }

        return null;
    }
}
