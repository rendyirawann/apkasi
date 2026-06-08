<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah koordinat (lat/lng) + maps_url ke tabel rentals agar bisa dipetakan di Panduan (Mapbox GeoJSON).
 * Pin perkiraan dari alamat; maps_url query nama+area agar tombol "Rute" tetap akurat (pola sama spt hotel).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rentals')) {
            return;
        }

        Schema::table('rentals', function (Blueprint $table) {
            if (! Schema::hasColumn('rentals', 'lat'))      $table->decimal('lat', 10, 7)->nullable()->after('kontak_wa');
            if (! Schema::hasColumn('rentals', 'lng'))      $table->decimal('lng', 10, 7)->nullable()->after('lat');
            if (! Schema::hasColumn('rentals', 'maps_url')) $table->string('maps_url')->nullable()->after('lng');
        });

        // Backfill koordinat 3 rental yang sudah ada (idempotent, by nama).
        $coords = [
            ['PT. Seribu Nusantara Rental',   3.5292, 98.6726, 'https://maps.google.com/?q=PT.+Seribu+Nusantara+Rental+Medan+Johor'],
            ['PT. Sanobar Gunajaya Car Rental', 3.5618, 98.6905, 'https://maps.google.com/?q=Sanobar+Gunajaya+Car+Rental+Brigjend+Katamso+Medan'],
            ['PT. Stasiun Rental Mobil',      3.5861, 98.7943, 'https://maps.google.com/?q=Stasiun+Rental+Mobil+Batang+Kuis+Deli+Serdang'],
        ];
        foreach ($coords as [$nama, $lat, $lng, $url]) {
            DB::table('rentals')->where('nama', $nama)->update(['lat' => $lat, 'lng' => $lng, 'maps_url' => $url]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('rentals')) {
            return;
        }
        Schema::table('rentals', function (Blueprint $table) {
            foreach (['lat', 'lng', 'maps_url'] as $col) {
                if (Schema::hasColumn('rentals', $col)) $table->dropColumn($col);
            }
        });
    }
};
