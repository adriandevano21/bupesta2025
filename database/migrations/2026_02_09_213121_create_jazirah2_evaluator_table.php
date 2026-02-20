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
        Schema::create('jazirah2_evaluator', function (Blueprint $table) {
            $table->id(); // Primary Key (BigInt Auto Increment)
            $table->string('tahun', 4)->nullable(); // Kolom Tahun (YYYY)
            $table->string('satker', 10)->nullable(); // Satuan Kerja
            $table->string('kode_3', 50)->nullable(); // Pilar Evaluasi
            $table->string('id_evaluator', 50)->unique(); // Gabungan tahun.satker.pilar
            $table->string('evaluator', 50)->nullable(); // Nama/ID Penilai
            $table->timestamps(); // Menghasilkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jazirah2_evaluator');
    }
};
