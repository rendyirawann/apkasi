<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pics', function (Blueprint $table) {
            // LO (Liaison Officer) per provinsi: kecamatan penempatan + instansi/OPD.
            $table->string('lo_kecamatan', 120)->nullable()->after('nama');
            $table->string('lo_instansi', 200)->nullable()->after('lo_kecamatan');
        });
    }

    public function down(): void
    {
        Schema::table('pics', function (Blueprint $table) {
            $table->dropColumn(['lo_kecamatan', 'lo_instansi']);
        });
    }
};
