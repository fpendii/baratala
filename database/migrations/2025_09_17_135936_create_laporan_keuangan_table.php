<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_keuangan'); // FK ke tabel keuangan
            $table->date('tanggal');
            $table->foreignId('id_pengguna')->constrained('pengguna')->onDelete('cascade');
            $table->string('keperluan');
            $table->decimal('nominal', 15, 2);
            $table->enum('jenis', ['pengeluaran', 'kasbon', 'uang_masuk']);
            $table->boolean('persetujuan_direktur')->default(false);
            $table->foreignId('penerima')->nullable()->constrained('pengguna')->onDelete('cascade');
            $table->enum('jenis_uang', ['kas', 'bank']);
            $table->string('lampiran')->nullable();
            $table->timestamps();

            // relasi keuangan
            $table->foreign('id_keuangan')->references('id')->on('keuangan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};
