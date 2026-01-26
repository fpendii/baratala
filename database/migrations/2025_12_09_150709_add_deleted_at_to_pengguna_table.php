<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            // Tambahkan kolom deleted_at
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            // Hapus kolom deleted_at jika rollback
            $table->dropSoftDeletes();
        });
    }
};
