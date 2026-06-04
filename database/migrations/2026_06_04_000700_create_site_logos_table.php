<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kumpulan logo landing page per grup: navbar, hero, partners, footer_brand, footer_side.
        Schema::create('site_logos', function (Blueprint $table) {
            $table->id();
            $table->string('grup')->index();
            $table->string('gambar');
            $table->string('alt')->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_logos');
    }
};
