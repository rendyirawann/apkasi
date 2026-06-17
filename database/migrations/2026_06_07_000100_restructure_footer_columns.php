<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Versi 1 (footer_links berbasis string "kolom") dibuang -> struktur kolom dinamis.
        // Data akan di-seed ulang oleh FooterLinkSeeder (guarded).
        Schema::dropIfExists('footer_links');

        Schema::create('footer_columns', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_column_id')->constrained('footer_columns')->cascadeOnDelete();
            $table->string('label');
            $table->string('url')->default('#');
            $table->string('icon')->nullable(); // slug Simple Icons utk link sosmed (auto-deteksi dari URL)
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
        Schema::dropIfExists('footer_columns');
    }
};
