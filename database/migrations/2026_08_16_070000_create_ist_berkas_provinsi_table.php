<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel master daftar dokumen persyaratan berkas Tahap 2 (Provinsi).
     * Hanya admin yang bisa mengelola isi tabel ini.
     */
    public function up(): void
    {
        Schema::create('ist_berkas_provinsi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->unsignedInteger('urutan')->default(1)->comment('Nomor urut tampilan dokumen');
            $table->string('nama_dokumen', 255)->comment('Nama / judul dokumen yang harus disiapkan');
            $table->text('keterangan')->nullable()->comment('Keterangan atau panduan pengisian dokumen');
            $table->string('link_template', 2000)->nullable()->comment('Link template dokumen (Google Drive / Drive BPS)');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('ist_periode')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ist_berkas_provinsi');
    }
};
