<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi_surat', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->unsignedBigInteger('surat_masuk_id');
            $table->unsignedBigInteger('direktur_id');
            $table->unsignedBigInteger('user_tujuan_id');

            // DATA DISPOSISI
            $table->text('catatan')->nullable();
            $table->date('tanggal_disposisi');
            $table->enum('status_baca', ['belum', 'sudah'])->default('belum');

            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('surat_masuk_id')
                ->references('id')
                ->on('surat_masuk')
                ->onDelete('cascade');

            $table->foreign('direktur_id')
                ->references('id')
                ->on('pengguna')
                ->onDelete('cascade');

            $table->foreign('user_tujuan_id')
                ->references('id')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi_surat');
    }
};
