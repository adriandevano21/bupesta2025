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
        Schema::create('jazirah2_hasil', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('satker', 10);
            $table->string('tahun', 4);
            $table->string('id_indikator', 50);
            $table->string('penanggungjawab', 100)->nullable();
            $table->string('bulan_target', 100)->nullable();
            $table->string('bulan_realisasi', 100)->nullable();
            $table->string('link_buktidukung', 1000)->nullable();
            $table->string('jumlah_dokumen', 1000)->nullable();
            $table->string('progres_tw1', 100)->nullable();
            $table->string('progres_tw2', 100)->nullable();
            $table->string('progres_tw3', 100)->nullable();
            $table->string('progres_tw4', 100)->nullable();
            $table->string('progres_th', 100)->nullable();
            $table->string('status_dokumen', 50)->nullable();
            $table->string('komentar_evaluator1', 1000)->nullable();
            $table->string('komentar_operator1', 1000)->nullable();
            $table->string('rencanaaksi', 5000)->nullable();
            $table->string('output', 5000)->nullable();
            $table->string('created_by_1', 100)->nullable();
            $table->timestamp('created_at_1')->nullable();
            $table->string('created_by_2', 100)->nullable();
            $table->timestamp('created_at_2')->nullable();
            $table->string('created_by_3', 100)->nullable();
            $table->timestamp('created_at_3')->nullable();
            $table->string('created_by_4', 100)->nullable();
            $table->timestamp('created_at_4')->nullable();
            $table->string('created_by_5', 100)->nullable();
            $table->timestamp('created_at_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jazirah2_hasil');
    }
};
