<?php

// database/migrations/..._add_persetujuan_pdf_to_laporan_keuangan_table.php

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
            // Kolom baru untuk menyimpan path PDF
            $table->string('bukti_persetujuan_pdf')->nullable()->after('status_persetujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->dropColumn('bukti_persetujuan_pdf');
        });
    }
};
