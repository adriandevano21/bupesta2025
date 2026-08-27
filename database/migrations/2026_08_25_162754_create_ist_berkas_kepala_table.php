<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ist_berkas_kepala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->unsignedInteger('urutan')->default(1)->comment('Nomor urut tampilan');
            $table->string('nama_dokumen', 255);
            $table->text('keterangan')->nullable();
            $table->string('link_template', 2000)->nullable();
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('ist_periode')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ist_berkas_kepala');
    }
};