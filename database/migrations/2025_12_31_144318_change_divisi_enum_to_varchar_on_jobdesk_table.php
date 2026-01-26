<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jobdesk', function (Blueprint $table) {
            $table->string('divisi', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobdesk', function (Blueprint $table) {
            $table->enum('divisi', [
                'direktur',
                'kepala teknik',
                'enginer',
                'produksi',
                'keuangan'
            ])->change();
        });
    }
};
