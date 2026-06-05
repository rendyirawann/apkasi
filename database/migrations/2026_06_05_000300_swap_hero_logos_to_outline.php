<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ganti logo carousel hero ke versi ber-outline (folder /outline yg sudah dioptimasi jadi webp),
 * dan perbarui teks aksen headline scene leader. Idempotent: hanya menyentuh nilai LAMA,
 * jadi aman di-run berulang & tidak menimpa kustomisasi admin.
 *
 * Pakai migration (bukan seeder firstOrCreate) agar di DB lama path-nya DI-UPDATE,
 * bukan malah menambah baris duplikat.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_logos')) {
            $swaps = [
                ['hero_v2', 'logos/logo_hut26.webp', 'logos/hut26-outline.webp'],
                ['hero_v2', 'logos/hutds80.png',     'logos/hutds80-outline.webp'],
                ['hero_v3', 'logos/poi.png',         'logos/poi-outline.webp'],
            ];
            foreach ($swaps as [$grup, $old, $new]) {
                DB::table('site_logos')->where('grup', $grup)->where('gambar', $old)->update(['gambar' => $new]);
            }
        }

        if (Schema::hasTable('settings')) {
            // Hanya update bila masih default lama (jangan timpa bila admin sudah mengubah).
            DB::table('settings')->where('key', 'lp_hero_leaders_accent')
                ->where('value', 'Tuan Rumah HUT Ke-26 APKASI')
                ->update(['value' => 'para Delegasi dan Pimpinan Kabupaten Se-Nusantara']);
            Setting::clearCache();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_logos')) {
            $revert = [
                ['hero_v2', 'logos/hut26-outline.webp',   'logos/logo_hut26.webp'],
                ['hero_v2', 'logos/hutds80-outline.webp', 'logos/hutds80.png'],
                ['hero_v3', 'logos/poi-outline.webp',     'logos/poi.png'],
            ];
            foreach ($revert as [$grup, $new, $old]) {
                DB::table('site_logos')->where('grup', $grup)->where('gambar', $new)->update(['gambar' => $old]);
            }
        }
    }
};
