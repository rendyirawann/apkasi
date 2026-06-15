<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Penanda halal untuk kuliner (toggle on/off). Bila on -> tampil logo halal di Peta Lokasi.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kuliner')) {
            return;
        }
        Schema::table('kuliner', function (Blueprint $table) {
            if (! Schema::hasColumn('kuliner', 'halal')) {
                $table->boolean('halal')->default(false)->after('jenis_kuliner');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('kuliner') && Schema::hasColumn('kuliner', 'halal')) {
            Schema::table('kuliner', function (Blueprint $table) {
                $table->dropColumn('halal');
            });
        }
    }
};
