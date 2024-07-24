<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriBeritasTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_beritas', function (Blueprint $table) {
            $table->id('id_kategori'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('nama_kategori', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_beritas');
    }
}
