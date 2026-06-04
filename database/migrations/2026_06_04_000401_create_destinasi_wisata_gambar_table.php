<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel child: galeri gambar lain untuk satu destinasi wisata (parent).
        Schema::create('destinasi_wisata_gambar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinasi_wisata_id')->constrained('destinasi_wisata')->cascadeOnDelete();
            $table->string('gambar');
            $table->string('caption')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_wisata_gambar');
    }
};
