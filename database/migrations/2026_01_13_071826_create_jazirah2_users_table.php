<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jazirah2_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 255);
            $table->string('username', 50)->nullable();
            $table->string('kode_satker', 4);
            $table->string('nip_pegawai', 20)->nullable();

            $table->enum('role', ['admin', 'kepala', 'kasubbag', 'operator', 'evaluator', 'penilai']);

            // opsional tapi biasanya kepakai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jazirah2_users');
    }
};
