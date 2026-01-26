<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_laporan_keuangan')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('keputusan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_laporan_keuangan')->references('id')->on('laporan_keuangan')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan');
    }
}
