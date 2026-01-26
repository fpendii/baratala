<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanJobdeskTable extends Migration
{
    public function up()
    {
        Schema::create('laporan_jobdesk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_jobdesk');
            $table->text('deskripsi');
            $table->string('lampiran');
            $table->enum('status', ['belum-dikerjakan', 'on-progress', 'tidak-dikerjakan', 'selesai']);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_jobdesk')->references('id')->on('jobdesk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_jobdesk');
    }
}
