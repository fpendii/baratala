<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('doc_number')->unique()->nullable(); // Contoh: 2024/SK/001
            $table->string('title');
            $table->text('description')->nullable();

            // Relasi ke kategori & user
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained(); // User pembuat arsip

            $table->string('status')->default('active'); // active, archived
            $table->boolean('is_confidential')->default(false);

            $table->softDeletes(); // Fitur backup jika data dihapus
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('documents'); }
};
