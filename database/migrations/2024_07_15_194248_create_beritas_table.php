<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritasTable extends Migration
{
    public function up()
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id('id_berita'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('judul_berita', 255);
            $table->string('gambar', 255);
            $table->dateTime('tanggal_berita');
            $table->enum('status_berita', ['publish', 'draft']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beritas');
    }
}
