<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained()->onDelete('cascade');

            $table->integer('version_number')->default(1);
            $table->string('file_path');      // Lokasi di storage
            $table->string('file_name');      // Nama asli file
            $table->string('file_extension'); // pdf, docx, dll
            $table->unsignedBigInteger('file_size');

            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('document_versions'); }
};
