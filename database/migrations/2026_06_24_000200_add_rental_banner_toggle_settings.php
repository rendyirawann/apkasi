<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Toggle tampil/sembunyikan untuk tiap banner rental (halaman Panduan).
 *  - panduan_rental_banner_enabled  : '1' tampil / '0' sembunyi (default tampil)
 *  - panduan_rental_banner2_enabled : '1' tampil / '0' sembunyi (default tampil)
 * firstOrCreate => idempoten & tidak menimpa pengaturan admin bila sudah ada.
 */
return new class extends Migration
{
    private array $defaults = [
        'panduan_rental_banner_enabled'  => '1',
        'panduan_rental_banner2_enabled' => '1',
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
