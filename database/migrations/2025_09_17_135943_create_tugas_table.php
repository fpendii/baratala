<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasTable extends Migration
{
    public function up()
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->string('judul_rencana');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['belum dikerjakan', 'sedang dikerjakan', 'selesai'])->default('belum dikerjakan');
            $table->enum('jenis', ['rencana', 'perintah']);
            $table->string('prioritas')->nullable();
            $table->string('lampiran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tugas');
    }
}
