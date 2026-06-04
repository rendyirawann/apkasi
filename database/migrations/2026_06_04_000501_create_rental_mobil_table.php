<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel child: daftar mobil yang tersedia di sebuah rental + jumlah unit.
        Schema::create('rental_mobil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->cascadeOnDelete();
            $table->string('nama_mobil');
            $table->unsignedInteger('jumlah_unit')->default(0);
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_mobil');
    }
};
