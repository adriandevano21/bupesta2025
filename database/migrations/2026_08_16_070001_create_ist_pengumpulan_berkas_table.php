<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk menyimpan link/URL berkas yang dikumpulkan oleh masing-masing kandidat Tahap 2.
     */
    public function up(): void
    {
        Schema::create('ist_pengumpulan_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('berkas_id')->comment('FK ke ist_berkas_provinsi');
            $table->string('nip_kandidat', 50)->comment('NIP kandidat yang mengumpulkan berkas');
            $table->string('link_berkas', 2000)->nullable()->comment('URL/link dokumen yang diupload kandidat');
            $table->text('catatan')->nullable()->comment('Catatan tambahan dari kandidat (opsional)');
            $table->timestamps();

            // Composite unique: satu kandidat hanya boleh submit 1 link per jenis berkas per periode
            $table->unique(['periode_id', 'berkas_id', 'nip_kandidat'], 'unique_pengumpulan');

            $table->foreign('periode_id')->references('id')->on('ist_periode')->onDelete('cascade');
            $table->foreign('berkas_id')->references('id')->on('ist_berkas_provinsi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ist_pengumpulan_berkas');
    }
};
