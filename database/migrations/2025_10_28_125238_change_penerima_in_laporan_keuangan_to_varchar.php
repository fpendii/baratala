<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            // 1. Hapus Foreign Key Constraint yang mencegah perubahan tipe data
            // Nama constraint biasanya dibuat secara otomatis oleh Laravel: 'nama_tabel_kolom_foreign'
            $table->dropForeign(['penerima']);

            // 2. Ubah tipe data kolom 'penerima' menjadi varchar(255) dan nullable
            $table->string('penerima', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            // 1. Kembalikan kolom ke tipe data aslinya (bigint unsigned)
            // PERHATIAN: Perubahan ini akan GAGAL jika data string sudah tersimpan.
            $table->unsignedBigInteger('penerima')->nullable()->change();

            // 2. Tambahkan kembali Foreign Key Constraint ke tabel 'pengguna'
            // Asumsi: Penerima merujuk ke id di tabel 'pengguna'
            $table->foreign('penerima')->references('id')->on('pengguna')->onDelete('set null');
        });
    }
};
