<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekayasa_lalu_lintas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('rekayasa_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekayasa_id')->constrained('rekayasa_lalu_lintas')->cascadeOnDelete();
            $table->string('nama');
            $table->unsignedInteger('urut')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekayasa_lokasi');
        Schema::dropIfExists('rekayasa_lalu_lintas');
    }
};
