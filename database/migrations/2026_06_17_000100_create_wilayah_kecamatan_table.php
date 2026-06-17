<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // nama kecamatan (tanpa prefix "Kec.")
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_kecamatan');
    }
};
