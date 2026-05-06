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
        Schema::create('bupesta_kegiatan', function (Blueprint $table) {
            $table->string('kode_kegiatan', 20)->nullable();
            $table->string('kode_eselon', 10)->nullable();
            $table->string('nama_kegiatan', 150)->nullable();
            $table->string('tahun_kegiatan', 50)->nullable();
            $table->string('kode_tim_kerja', 20)->nullable();
            $table->text('detail_anggota_tim')->nullable();
            $table->text('detail_informasi_penting')->nullable();
            $table->text('detail_jadwal')->nullable();
            $table->string('timestamp', 50)->nullable();
            $table->string('nip_pegawai', 20)->nullable();
            $table->string('flag_kabkot', 20)->nullable();
            $table->string('pjk_1100', 30)->nullable();
            $table->string('pjk_1101', 30)->nullable();
            $table->string('pjk_1102', 30)->nullable();
            $table->string('pjk_1103', 30)->nullable();
            $table->string('pjk_1104', 30)->nullable();
            $table->string('pjk_1105', 30)->nullable();
            $table->string('pjk_1106', 30)->nullable();
            $table->string('pjk_1107', 30)->nullable();
            $table->string('pjk_1108', 30)->nullable();
            $table->string('pjk_1109', 30)->nullable();
            $table->string('pjk_1110', 30)->nullable();
            $table->string('pjk_1111', 30)->nullable();
            $table->string('pjk_1112', 30)->nullable();
            $table->string('pjk_1113', 30)->nullable();
            $table->string('pjk_1114', 30)->nullable();
            $table->string('pjk_1115', 30)->nullable();
            $table->string('pjk_1116', 30)->nullable();
            $table->string('pjk_1117', 30)->nullable();
            $table->string('pjk_1118', 30)->nullable();
            $table->string('pjk_1171', 30)->nullable();
            $table->string('pjk_1172', 30)->nullable();
            $table->string('pjk_1173', 30)->nullable();
            $table->string('pjk_1174', 30)->nullable();
            $table->string('pjk_1175', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bupesta_kegiatan');
    }
};
