<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

/**
 * Kompatibilitas Safari/iOS: arahkan semua aset DB yang masih .webp / berspasi (z_04) ke .png.
 * Safari versi lama tak mendukung WebP, dan URL berspasi sering gagal dimuat. Idempotent: hanya
 * mengubah nilai LAMA yang persis cocok, jadi aman di-run berulang & tidak menimpa upload admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_logos')) {
            $logoSwaps = [
                'logos/hut26-outline.webp'   => 'logos/hut26-outline.png',
                'logos/hutds80-outline.webp' => 'logos/hutds80-outline.png',
                'logos/poi-outline.webp'     => 'logos/poi-outline.png',
            ];
            foreach ($logoSwaps as $old => $new) {
                DB::table('site_logos')->where('gambar', $old)->update(['gambar' => $new]);
            }
        }

        if (Schema::hasTable('settings')) {
            $settingSwaps = [
                'lp_hero_leaders_img' => ['assets/apkasi/leaders.webp'        => 'assets/apkasi/leaders.png'],
                'lp_hero_leader1_img' => ['assets/apkasi/bupati-samping.webp' => 'assets/apkasi/bupati-samping.png'],
                'lp_hero_leader2_img' => ['assets/apkasi/wabup-samping.webp'  => 'assets/apkasi/wabup-samping.png'],
                'lp_bupati_foto'      => ['assets/apkasi/z_04_LOGO-LOGO APKASI/BUPATI.png'   => 'logos/bupati.png'],
                'lp_wabup_foto'       => ['assets/apkasi/z_04_LOGO-LOGO APKASI/WABUPATI.png' => 'logos/wabup.png'],
            ];
            foreach ($settingSwaps as $key => $map) {
                foreach ($map as $old => $new) {
                    DB::table('settings')->where('key', $key)->where('value', $old)->update(['value' => $new]);
                }
            }
            Setting::clearCache();
        }
    }

    public function down(): void
    {
        // Tidak dibalik (png universal; tak perlu kembali ke webp/berspasi).
    }
};
