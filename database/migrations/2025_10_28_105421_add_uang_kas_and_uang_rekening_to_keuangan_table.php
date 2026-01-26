<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $table->decimal('uang_kas', 15, 2)->default(0)->after('nominal');
            $table->decimal('uang_rekening', 15, 2)->default(0)->after('uang_kas');
        });
    }

    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $table->dropColumn(['uang_kas', 'uang_rekening']);
        });
    }
};
