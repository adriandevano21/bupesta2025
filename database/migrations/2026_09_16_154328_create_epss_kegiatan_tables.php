<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('epss_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->nullable();
            $table->string('status')->nullable(); // Contoh: 'Ada', 'Tidak'
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('epss_kegiatan');
    }
};