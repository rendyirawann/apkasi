<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master provinsi (mengikuti struktur wilayah_provinsi.sql; id = kode BPS, bukan auto-increment).
        if (! Schema::hasTable('wilayah_provinsi')) {
            Schema::create('wilayah_provinsi', function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->string('nama', 50);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_provinsi');
    }
};
