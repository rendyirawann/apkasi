<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            // venue = lokasi acara, hotel, wisata, rental (siap dipakai utk halaman lain)
            $table->enum('category', ['venue', 'hotel', 'wisata', 'rental'])->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('price_range')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('image')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('maps_url')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
