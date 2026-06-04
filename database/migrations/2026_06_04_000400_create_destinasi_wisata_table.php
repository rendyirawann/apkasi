<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi_wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('harga_tiket')->nullable(); // varchar (mis. "Rp10.000" / "Gratis")
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('maps_url')->nullable();
            // Tag on/off: tandai destinasi ini juga sebagai Lokasi Acara
            $table->boolean('is_lokasi_acara')->default(false);
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_wisata');
    }
};
