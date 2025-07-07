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
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->time('pukul');
            $table->string('peserta');
            $table->string('tempat_kegiatan');
            $table->string('pemateri')->nullable();
            $table->string('presensi_kegiatan')->nullable();
            $table->string('zoom_kegiatan')->nullable();
            $table->string('rekaman_kegiatan')->nullable();
            $table->string('materi_kegiatan')->nullable();
            $table->string('sertifikat_kegiatan')->nullable();
            $table->string('lok_flyer')->nullable();
            $table->string('name_flyer')->nullable();
            $table->string('lok_backdrop')->nullable();
            $table->string('name_backdrop')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinemas');
    }
};
