<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah detail hotel dari dokumen resmi:
 *  - kategori        : 'deli_serdang' | 'medan' (pemisah list di halaman Peta & Hotel)
 *  - contact_person  : nama CP (mis. "Irwan") — nomornya tetap di contact_wa
 *  - jarak           : jarak ke lokasi HUT APKASI (teks bebas, mis. "11 Km (13 Menit)")
 * Harga per tipe kamar dipisah ke tabel anak hotel_kamar (migration terpisah).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'kategori'))       $table->string('kategori', 20)->default('deli_serdang')->after('nama');
            if (! Schema::hasColumn('hotels', 'contact_person')) $table->string('contact_person')->nullable()->after('contact_wa');
            if (! Schema::hasColumn('hotels', 'jarak'))          $table->string('jarak')->nullable()->after('contact_person');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['kategori', 'contact_person', 'jarak'] as $col) {
                if (Schema::hasColumn('hotels', $col)) $table->dropColumn($col);
            }
        });
    }
};
