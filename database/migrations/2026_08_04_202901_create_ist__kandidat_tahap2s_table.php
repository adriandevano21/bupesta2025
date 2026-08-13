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
        Schema::create('ist_kandidat_tahap2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->string('nip_kandidat', 50);
            $table->string('link_sk')->nullable();
            $table->string('validasi_sk', 20)->nullable()->default(null); // null=menunggu, 'tervalidasi', 'ditolak'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ist__kandidat_tahap2');
    }
};
