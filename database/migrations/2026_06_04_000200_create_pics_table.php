<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PIC (Person In Charge) kegiatan APKASI per provinsi.
        Schema::create('pics', function (Blueprint $table) {
            $table->id();
            $table->integer('provinsi_id')->nullable()->index(); // FK ke wilayah_provinsi.id (kode BPS)
            $table->string('nama');
            $table->string('no_hp', 30)->nullable();
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('wilayah_provinsi')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pics');
    }
};
