<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobdeskTable extends Migration
{
    public function up()
    {
        Schema::create('jobdesk', function (Blueprint $table) {
            $table->id();
            $table->string('judul_jobdesk');
            $table->text('deskripsi');
            $table->enum('divisi', ['direktur', 'kepala teknik', 'enginer', 'produksi', 'keuangan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobdesk');
    }
}
