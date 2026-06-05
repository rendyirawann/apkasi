<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Scene Bupati & Wakil di hero kini memakai SATU gambar gabungan (foto + nama + jabatan
 * sudah menyatu, hasil desain). Tambah setting lp_hero_leaders_img (default versi putih
 * untuk background gelap). firstOrCreate → idempotent & tidak menimpa pilihan admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(['key' => 'lp_hero_leaders_img'], ['value' => 'assets/apkasi/leaders.webp']);
        Setting::clearCache();
    }

    public function down(): void
    {
        Setting::where('key', 'lp_hero_leaders_img')->delete();
        Setting::clearCache();
    }
};
