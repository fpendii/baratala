<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            // 1. Tambah kolom untuk menyimpan konten HTML (TEXT karena panjang)
            $table->longText('konten_surat')->nullable()->after('perihal');

            // 2. Tambah kolom untuk path PDF (menggantikan atau menambahkan)
            // Jika Anda sudah punya 'file_pdf', Anda bisa rename
            // $table->renameColumn('file_pdf', 'dok_surat');
            // Jika belum punya, tambahkan:
            $table->string('dok_surat')->nullable()->after('lampiran');

            // (Opsional) Ubah kolom 'perihal' menjadi string biasa jika sebelumnya TEXT
            $table->string('perihal', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropColumn('konten_surat');
            $table->dropColumn('dok_surat');
            // Jika Anda sebelumnya rename:
            // $table->renameColumn('dok_surat', 'file_pdf');
        });
    }
};
