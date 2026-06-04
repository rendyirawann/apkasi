<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rundown rangkaian kegiatan — parent per tanggal.
        Schema::create('rundown', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('label')->nullable(); // mis. "Hari 1"
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rundown');
    }
};
