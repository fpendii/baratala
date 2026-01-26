<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_id');   // relasi ke tabel tugas
            $table->unsignedBigInteger('pengguna_id'); // relasi ke pengguna
            $table->text('isi');                      // isi komentar
            $table->enum('status', ['setuju','tolak','menunggu'])->default('menunggu');
            $table->timestamps();

            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};
