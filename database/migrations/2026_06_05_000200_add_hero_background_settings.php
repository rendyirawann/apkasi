<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Menyimpan pilihan latar belakang Hero (gambar / video) di tabel settings (key-value).
 *  - lp_hero_bg_type  : 'image' | 'video'
 *  - lp_hero_bg_image : path gambar latar (default logo hero-bg.png)
 *  - lp_hero_bg_video : path video latar (default mars-hero.mp4 hasil compress)
 * firstOrCreate => idempoten & tidak menimpa pilihan admin bila sudah ada.
 */
return new class extends Migration
{
    private array $defaults = [
        'lp_hero_bg_type'  => 'image',
        'lp_hero_bg_image' => 'logos/hero-bg.png',
        'lp_hero_bg_video' => 'assets/apkasi/mars-hero.mp4',
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
