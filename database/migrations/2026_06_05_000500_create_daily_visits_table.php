<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pencatat kunjungan UNIK per IP per hari (1 IP = 1x/hari).
 * Unique index (ip_hash, visit_on) → insertOrIgnore: kunjungan ke-2 di hari sama diabaikan,
 * besoknya IP yg sama dihitung lagi. IP disimpan sbg hash (privasi). Total = count() (di-cache).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64);
            $table->date('visit_on');
            $table->timestamp('created_at')->nullable();
            $table->unique(['ip_hash', 'visit_on']);
            $table->index('visit_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_visits');
    }
};
