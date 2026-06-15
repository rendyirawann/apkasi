<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Klasifikasi bintang hotel (1-5). Tampil sebagai ikon bintang di samping nama hotel.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'bintang')) {
                $table->unsignedTinyInteger('bintang')->nullable()->after('rating');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('hotels') && Schema::hasColumn('hotels', 'bintang')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('bintang');
            });
        }
    }
};
