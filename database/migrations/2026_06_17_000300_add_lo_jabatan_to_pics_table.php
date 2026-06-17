<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pics', function (Blueprint $table) {
            // Override sebutan/jabatan kepala instansi LO (opsional). Kosong = otomatis dari nama instansi.
            $table->string('lo_jabatan', 150)->nullable()->after('lo_instansi');
        });
    }

    public function down(): void
    {
        Schema::table('pics', function (Blueprint $table) {
            $table->dropColumn('lo_jabatan');
        });
    }
};
