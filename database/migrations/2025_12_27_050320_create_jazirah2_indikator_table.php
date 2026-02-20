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
        Schema::create('jazirah2_indikator', function (Blueprint $table) {
            $table->id();
            $table->string('kode_1', 50)->nullable();
            $table->string('kode_2', 50)->nullable();
            $table->string('kode_3', 50)->nullable();
            $table->string('kode_4', 50)->nullable();
            $table->string('kode_5', 250)->nullable();
            $table->text('rencana_kerja')->nullable();
            $table->unsignedTinyInteger('level')->default(0);
            $table->boolean('pengisian')->default(false);
            $table->string('pedoman', 2500)->nullable();
            $table->string('contoh_link', 2500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jazirah2_indikator');
    }
};
