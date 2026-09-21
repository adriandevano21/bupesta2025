<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('epss_referensi', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 20); // Berisi: 'domain', 'aspek', atau 'indikator'
            $table->string('kode', 10);
            $table->text('penjelasan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('epss_referensi');
    }
};