<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jazirah_hasil', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Sesuaikan dengan kode satker string (mis. "1100")
            $table->string('satker', 10)
                ->collation('utf8mb4_unicode_ci');

            // Tahun disimpan sebagai string 4 digit (kamu sering filter "2025" sebagai string)
            $table->string('tahun', 4)
                ->collation('utf8mb4_unicode_ci');

            // Ikuti pola kode indikator string
            $table->string('id_indikator', 50)
                ->collation('utf8mb4_unicode_ci');

            $table->string('bulan_target', 2048)->nullable();
            $table->string('bulan_realisasi', 2048)->nullable();
            // Kolom nullable sesuai permintaan

            $table->string('link_buktidukung', 2048)->nullable();
            $table->string('status_approval', 50)->nullable();
            $table->string('status_tindaklanjut', 50)->nullable();
            $table->string('komentar_evaluator1', 2048)->nullable();
            $table->string('komentar_operator1', 2048)->nullable();

            $table->timestamps();

            // Cegah duplikasi entri untuk kombinasi ini
            $table->unique(['satker', 'tahun', 'id_indikator'], 'jazirah_hasil_unique');

            // Indeks bantu untuk pencarian/filter
            $table->index(['satker', 'tahun'], 'jazirah_hasil_satker_tahun_idx');

            // Jika nanti ingin strict FK, aktifkan sesuai kolasi & tipe kolom pada tabel referensi:
            // $table->foreign('satker')->references('kode_satker')->on('satkers')
            //       ->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreign('id_indikator')->references('kode_indikator')->on('indikator_kinerjas')
            //       ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jazirah_hasil');
    }
};
