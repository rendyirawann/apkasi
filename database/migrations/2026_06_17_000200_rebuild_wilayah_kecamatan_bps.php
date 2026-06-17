<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bangun ulang wilayah_kecamatan mengikuti master resmi BPS (file database/sql/wilayah_kecamatan.sql):
     * id = kode BPS kecamatan, wilayah_kabupaten_id = kode BPS kabupaten (Deli Serdang = 1212), nama.
     */
    public function up(): void
    {
        Schema::dropIfExists('wilayah_kecamatan');

        Schema::create('wilayah_kecamatan', function (Blueprint $table) {
            $table->integer('id')->primary();                  // kode BPS kecamatan (mis. 1212300)
            $table->integer('wilayah_kabupaten_id')->index();  // kode BPS kabupaten (Deli Serdang = 1212)
            $table->string('nama', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_kecamatan');
    }
};
