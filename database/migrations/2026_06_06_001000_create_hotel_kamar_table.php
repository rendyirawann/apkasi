<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel anak: daftar tipe kamar + harga per hotel (dokumen memuat banyak tipe/harga).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->string('tipe');                              // tipe kamar (mis. "Deluxe King")
            $table->unsignedBigInteger('harga')->nullable();     // harga per malam (Rp)
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_kamar');
    }
};
