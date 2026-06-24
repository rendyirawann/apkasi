<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Banner gambar KEDUA di atas peta tab Rental (halaman Panduan), di bawah banner pertama.
 *  - panduan_rental_banner2 : path gambar banner kedua (default rentalnagahitam.png).
 * firstOrCreate => idempoten & tidak menimpa upload admin bila sudah ada.
 * Catatan: hanya slot tetap (tidak ada tambah/hapus) — admin cukup mengganti gambarnya.
 */
return new class extends Migration
{
    private array $defaults = [
        'panduan_rental_banner2' => 'assets/media/landing/rentalnagahitam.png',
    ];

    public function up(): void
    {
        foreach ($this->defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        Setting::clearCache();
    }

    public function down(): void
    {
        Setting::whereIn('key', array_keys($this->defaults))->delete();
        Setting::clearCache();
    }
};
