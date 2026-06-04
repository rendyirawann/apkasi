<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel child: item kegiatan dalam satu tanggal (waktu, kegiatan, lokasi, rincian).
        Schema::create('rundown_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rundown_id')->constrained('rundown')->cascadeOnDelete();
            $table->string('waktu')->nullable();
            $table->string('kegiatan');
            $table->string('lokasi')->nullable();
            $table->text('rincian')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rundown_kegiatan');
    }
};
