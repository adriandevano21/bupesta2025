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
        Schema::create('bupesta_user', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('tahun', 50)->nullable();
            $table->string('no_hp', 100)->nullable();
            $table->string('nip_pegawai', 100)->nullable();
            $table->string('kode_satker', 100)->nullable();
            $table->string('golongan', 150)->nullable();
            $table->string('jabatan', 150)->nullable();
            $table->string('urlfoto', 150)->nullable();
            $table->string('bupesta', 100)->nullable();
            $table->string('jazirah', 100)->nullable();
            $table->string('cinema', 100)->nullable();
            $table->string('kinerja', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bupesta_user');
    }
};
