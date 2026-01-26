<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tugas_pengguna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_tugas');
            $table->timestamps();

            // foreign key
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_tugas')->references('id')->on('tugas')->onDelete('cascade');

            $table->unique(['id_pengguna','id_tugas']); // optional biar tidak ganda
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_pengguna');
    }
};

