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
        Schema::create('se2026_hasil', function (Blueprint $table) {
            $table->id();
            $table->string('satker', 50)->nullable();
            $table->string('id_indikator', 50)->nullable();
            $table->string('peng_Feb', 100)->nullable();
            $table->string('peng_Mar', 100)->nullable();
            $table->string('peng_Apr', 100)->nullable();
            $table->string('peng_Mei', 100)->nullable();
            $table->string('peng_Jun', 100)->nullable();
            $table->string('peng_Jul', 100)->nullable();
            $table->string('peng_Ags', 100)->nullable();
            $table->string('app_Feb', 100)->nullable();
            $table->string('app_Mar', 100)->nullable();
            $table->string('app_Apr', 100)->nullable();
            $table->string('app_Mei', 100)->nullable();
            $table->string('app_Jun', 100)->nullable();
            $table->string('app_Jul', 100)->nullable();
            $table->string('app_Ags', 100)->nullable();
            $table->string('link_buktidukung', 500)->nullable();
            $table->string('pj_approval', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('se2026_hasil');
    }
};
