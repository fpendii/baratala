<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->enum('status_persetujuan', ['menunggu','tanpa persetujuan', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->after('persetujuan_direktur');
            $table->text('catatan')->nullable()->after('status_persetujuan');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->dropColumn(['status_persetujuan', 'catatan']);
        });
    }
};
