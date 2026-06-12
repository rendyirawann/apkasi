<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Banner gambar di atas peta tab Rental (halaman Panduan).
 *  - panduan_rental_banner : path gambar banner (default pic-rental.png).
 * firstOrCreate => idempoten & tidak menimpa upload admin bila sudah ada.
 */
return new class extends Migration
{
    private array $defaults = [
        'panduan_rental_banner' => 'assets/media/landing/pic-rental.jpg',
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
