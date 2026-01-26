<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            // Kolom ID (Primary Key, Auto-Increment)
            $table->id();

            // 🔑 Relasi: id_pengguna (Harus diletakkan sebelum Foreign Key)
            $table->unsignedBigInteger('id_pengguna');

            // TGL SURAT (Tanggal Surat)
            $table->date('tgl_surat');

            // NOMOR SURAT
            $table->string('nomor_surat', 100)->unique();

            // TUJUAN
            $table->string('tujuan', 255);

            // PERIHAL
            $table->string('perihal', 255);

            // JENIS SURAT
            $table->enum('jenis_surat', ['umum', 'keuangan', 'operasional'])->default('umum');

            // Timestamps (created_at dan updated_at)
            $table->timestamps();

            // 🔗 DEFINISI FOREIGN KEY
            // Menghubungkan id_pengguna di tabel surat_keluar ke kolom id di tabel pengguna
            $table->foreign('id_pengguna')
                  ->references('id')
                  ->on('pengguna')
                  ->onDelete('cascade'); // Jika pengguna dihapus, surat-suratnya ikut terhapus. Sesuaikan dengan kebutuhan.
        });
    }

    /**
     * Batalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluar');
    }
}
