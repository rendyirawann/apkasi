<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_banners');
    }
};
