<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ist_pengumpulan_kepala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('berkas_id');
            $table->string('nip_kepala', 50);
            $table->string('link_berkas', 2000)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Sesuai constraint yang Anda berikan
            $table->unique(['periode_id', 'berkas_id', 'nip_kepala'], 'unique_pengumpulan_kepala');
            $table->foreign('berkas_id')->references('id')->on('ist_berkas_kepala')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('ist_periode')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ist_pengumpulan_kepala');
    }
};