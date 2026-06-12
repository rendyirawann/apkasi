<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolusi URL gambar yang aman untuk masa transisi public/ -> storage/app/public.
 * - URL absolut (http/https) dikembalikan apa adanya.
 * - Jika file ADA di disk 'public' (storage/app/public) -> /storage/... (hasil upload baru).
 * - Selain itu -> asset() biasa (file lama/seed di public/), spasi di-encode utk Safari.
 */
class Media
{
    public static function url(?string $path, ?string $default = null): ?string
    {
        $v = ($path !== null && $path !== '') ? $path : $default;
        if (! $v) return null;
        if (Str::startsWith($v, ['http://', 'https://'])) return $v;

        $rel = ltrim($v, '/');
        try {
            if (Storage::disk('public')->exists($rel)) {
                return asset('storage/' . $rel);
            }
        } catch (\Throwable $e) {
            // abaikan; jatuh ke asset() biasa
        }
        return asset(str_replace(' ', '%20', $rel));
    }
}
