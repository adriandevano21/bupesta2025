<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('narahubung_jazirah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gmail', 150)->nullable();
            $table->string('email_bps', 150)->nullable();
            $table->string('nama', 150)->nullable();
            $table->string('satker', 10)->nullable()->index();
            $table->string('role', 50);
            $table->string('no_hp', 30)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('narahubung_jazirah');
    }
};
