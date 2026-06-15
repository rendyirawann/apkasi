<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Daftar kuliner (rumah makan / restoran) untuk halaman Peta Lokasi.
 * jenis_kuliner: teks bebas (mis. Nusantara, Internasional, Fast Food).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuliner', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis_kuliner')->nullable();   // Nusantara / Internasional / Fast Food
            $table->string('alamat')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('image')->nullable();
            $table->string('maps_url')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuliner');
    }
};
