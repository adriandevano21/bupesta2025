<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rencana_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_1', 10)->nullable();
            $table->string('kode_2', 10)->nullable();
            $table->string('kode_3', 10)->nullable();
            $table->string('kode_4', 10)->nullable();
            $table->string('kode_5', 10)->nullable();
            $table->text('rencana_kerja');
            $table->unsignedTinyInteger('level')->default(0);
            $table->boolean('pengisian')->default(false);
            $table->timestamps();

            $table->index(['kode_1', 'kode_2', 'kode_3', 'kode_4', 'kode_5']);
            $table->index('level');
            $table->index('pengisian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_kerjas');
    }
};
