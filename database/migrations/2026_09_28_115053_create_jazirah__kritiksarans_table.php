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
        Schema::create('jazirah_kritiksaran', function (Blueprint $table) {
            $table->id();
            $table->string('nip_pegawai'); 
            $table->enum('jenis', ['kritik', 'saran', 'masukan'])->default('masukan'); 
            $table->text('pesan'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jazirah_kritiksaran');
    }
};