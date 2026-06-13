<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Dokumen rental terbaru tidak mencantumkan jumlah unit per mobil -> jumlah_unit di-null-kan.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rental_mobil')) {
            return;
        }
        Schema::table('rental_mobil', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_unit')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('rental_mobil')) {
            return;
        }
        DB::table('rental_mobil')->whereNull('jumlah_unit')->update(['jumlah_unit' => 0]);
        Schema::table('rental_mobil', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_unit')->default(0)->change();
        });
    }
};
