<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParagraftsTable extends Migration
{
    public function up()
    {
        Schema::create('paragrafts', function (Blueprint $table) {
            $table->unsignedBigInteger('id_berita');
            $table->string('id_paragraft', 255); // NON Primary key
            $table->string('isi_paragraft', 500);
            $table->enum('status_paragraft', ['new', 'edit', 'publish']);
            $table->foreign('id_berita')->references('id_berita')->on('beritas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paragrafts');
    }
}
