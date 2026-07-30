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
        Schema::create('bupesta_timkerja', function (Blueprint $table) {
            $table->string('kode_tim_kerja', 50)->nullable();
            $table->string('nama_tim_kerja', 150)->nullable();
            $table->string('icon_tim_kerja', 150)->nullable();
            $table->string('tahun', 50)->nullable();
            $table->string('nip_ketua_tim', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('ketuatim_1100', 30)->nullable();
            $table->string('ketuatim_1101', 30)->nullable();
            $table->string('ketuatim_1102', 30)->nullable();
            $table->string('ketuatim_1103', 30)->nullable();
            $table->string('ketuatim_1104', 30)->nullable();
            $table->string('ketuatim_1105', 30)->nullable();
            $table->string('ketuatim_1106', 30)->nullable();
            $table->string('ketuatim_1107', 30)->nullable();
            $table->string('ketuatim_1108', 30)->nullable();
            $table->string('ketuatim_1109', 30)->nullable();
            $table->string('ketuatim_1110', 30)->nullable();
            $table->string('ketuatim_1111', 30)->nullable();
            $table->string('ketuatim_1112', 30)->nullable();
            $table->string('ketuatim_1113', 30)->nullable();
            $table->string('ketuatim_1114', 30)->nullable();
            $table->string('ketuatim_1115', 30)->nullable();
            $table->string('ketuatim_1116', 30)->nullable();
            $table->string('ketuatim_1117', 30)->nullable();
            $table->string('ketuatim_1118', 30)->nullable();
            $table->string('ketuatim_1171', 30)->nullable();
            $table->string('ketuatim_1172', 30)->nullable();
            $table->string('ketuatim_1173', 30)->nullable();
            $table->string('ketuatim_1174', 30)->nullable();
            $table->string('ketuatim_1175', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bupesta_timkerja');
    }
};
