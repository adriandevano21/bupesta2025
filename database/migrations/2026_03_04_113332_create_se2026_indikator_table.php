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
        Schema::create('se2026_indikator', function (Blueprint $table) {
            $table->id();
            $table->string('kode_1', 50)->nullable();
            $table->string('kode_2', 50)->nullable();
            $table->string('kinerja', 50)->nullable();
            $table->string('kegiatan', 500)->nullable();
            $table->string('uraian_kegaitan', 250)->nullable();
            $table->string('target', 100)->nullable();
            $table->string('Feb', 100)->nullable();
            $table->string('Mar', 100)->nullable();
            $table->string('Apr', 100)->nullable();
            $table->string('Mei', 100)->nullable();
            $table->string('Jun', 100)->nullable();
            $table->string('Jul', 100)->nullable();
            $table->string('Ags', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('se2026_indikator');
    }
};
