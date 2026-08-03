<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bupesta_datasimpeg', function (Blueprint $table) {
            $table->id();
            $table->string('nip_bps', 50)->nullable();
            $table->string('nip', 50)->nullable();
            $table->string('nama', 150)->nullable();
            $table->string('kode_org', 50)->nullable();
            $table->string('jabatan', 150)->nullable();
            $table->string('wilayah', 150)->nullable();
            $table->string('tmt_jab', 50)->nullable();
            $table->string('gol_akhir', 50)->nullable();
            $table->string('tmt_gol', 50)->nullable();
            $table->string('status', 200)->nullable();
            $table->string('pend_sk', 100)->nullable();
            $table->string('mks_thn', 10)->nullable();
            $table->string('mks_bln', 10)->nullable();
            $table->string('tempat_lahir', 100)->nullable();

            // Kolom Tambahan Dinamis saat Upload
            $table->date('tanggal_versidata')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bupesta_datasimpeg');
    }
};
