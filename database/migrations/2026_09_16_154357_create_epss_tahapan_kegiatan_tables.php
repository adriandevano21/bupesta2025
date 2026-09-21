<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('epss_tahapan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kegiatan')->constrained('epss_kegiatan')->cascadeOnDelete();
            $table->string('tahapan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->nullable(); // Contoh: 'Buka', 'Tutup'
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('epss_tahapan_kegiatan');
    }
};