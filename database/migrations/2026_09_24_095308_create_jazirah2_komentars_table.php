<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jazirah2_komentar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jazirah2_hasil'); 
            $table->string('nip', 20); 
            $table->text('komentar');
            $table->timestamps(); 

            // (Opsional) Jika tabel jazirah2_hasil menggunakan primary key 'id'
            // $table->foreign('id_jazirah2_hasil')->references('id')->on('jazirah2_hasil')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jazirah2_komentar');
    }
};