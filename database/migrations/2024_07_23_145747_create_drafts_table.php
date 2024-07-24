<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_berita');
            $table->string('judul_berita')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->timestamps();

            $table->foreign('id_berita')->references('id_berita')->on('beritas')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_beritas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drafts');
    }
}
