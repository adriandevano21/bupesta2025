<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jazirah_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('bg');
            $table->string('icon');
            $table->integer('urutan')->default(0); // Untuk mengatur urutan tampil
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jazirah_menus');
    }
};
