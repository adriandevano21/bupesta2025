<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('epss_usulan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kegiatan')->constrained('epss_kegiatan')->cascadeOnDelete();
            $table->string('kode_satker', 10)->nullable();
            $table->text('kegiatan_1')->nullable();
            $table->string('nama_dinas_1')->nullable();
            $table->string('tahun_1', 10)->nullable();
            $table->text('kegiatan_2')->nullable();
            $table->string('nama_dinas_2')->nullable();
            $table->string('tahun_2', 10)->nullable();
            $table->string('kode_satker_penilai', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('epss_usulan_kegiatan');
    }
};