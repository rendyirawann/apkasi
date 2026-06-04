<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Tag on/off: tandai hotel ini juga sebagai Lokasi Acara
            $table->boolean('is_lokasi_acara')->default(false)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('is_lokasi_acara');
        });
    }
};
