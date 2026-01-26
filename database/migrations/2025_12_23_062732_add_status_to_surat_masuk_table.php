<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'didisposisikan'])
                  ->default('menunggu')
                  ->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
