<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('epss_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kegiatan')->constrained('epss_kegiatan')->cascadeOnDelete();
            $table->foreignId('id_tahapan')->constrained('epss_tahapan_kegiatan')->cascadeOnDelete();
            $table->foreignId('id_usulan_kegiatan')->constrained('epss_usulan_kegiatan')->cascadeOnDelete();
            
            // Kolom Indikator (Tipe tinyInteger karena nilainya hanya 1-5)
            $indikator = [
                '10101','10201','10301','10401',
                '20101','20102','20201','20301','20302','20401','20402','20403','20501','20502',
                '30101','30102','30103','30201','30301','30302','30401',
                '40101','40102','40103','40104','40201','40202','40301','40302','40303','40304',
                '50101','50102','50103','50201','50301','50302','50303'
            ];

            foreach ($indikator as $kolom) {
                $table->tinyInteger($kolom)->nullable();
            }

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('epss_nilai');
    }
};