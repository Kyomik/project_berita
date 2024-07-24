<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_berita');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_user');
            $table->enum('keterangan', ['upload', 'edit', 'publish']);
            $table->integer('views');
            $table->primary(['id_berita', 'id_kategori']); // Menjadikan id_berita dan id_kategori sebagai PRIMARY KEY
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_berita')->references('id_berita')->on('beritas');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_beritas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
}
