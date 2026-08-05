<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ist_periode', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4);
            $table->boolean('is_active')->default(false);
            $table->text('informasi_persiapan')->nullable();
            $table->date('mulai_tahap1_1')->nullable();
            $table->date('akhir_tahap1_1')->nullable();
            $table->date('mulai_tahap1_2')->nullable();
            $table->date('akhir_tahap1_2')->nullable();
            $table->date('mulai_tahap2_1')->nullable();
            $table->date('akhir_tahap2_1')->nullable();
            $table->date('mulai_tahap2_2')->nullable();
            $table->date('akhir_tahap2_2')->nullable();
            $table->date('tanggal_pengumuman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ist__periode');
    }
};
