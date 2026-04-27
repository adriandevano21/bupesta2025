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
            $table->string('nip_pegawai', 100)->nullable();
            $table->string('kode_satker', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('role_se2026', 100)->nullable();
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
