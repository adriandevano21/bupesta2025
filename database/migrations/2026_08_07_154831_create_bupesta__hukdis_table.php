<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bupesta_hukdis', function (Blueprint $table) {
            $table->id();
            $table->string('nip_bps', 50)->nullable();
            $table->string('nama', 150)->nullable();
            $table->string('satker', 100)->nullable();
            $table->string('jenis', 100)->nullable();
            $table->text('hukuman')->nullable();
            $table->string('tmt_mulai', 50)->nullable(); // Pakai string agar aman dari berbagai format tanggal excel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bupesta__hukdis');
    }
};
