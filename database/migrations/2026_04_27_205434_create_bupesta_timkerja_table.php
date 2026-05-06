<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bupesta_timkerja', function (Blueprint $table) {
            $table->string('kode_tim_kerja', 50)->nullable();
            $table->string('nama_tim_kerja', 150)->nullable();
            $table->string('icon_tim_kerja', 150)->nullable();
            $table->string('tahun', 50)->nullable();
            $table->string('nip_ketua_tim', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bupesta_timkerja');
    }
};
