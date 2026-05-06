<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeuAlbumCompartilhadoFotosTable extends Migration
{
    public function up()
    {
        Schema::create('meu_album_compartilhado_fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meu_album_compartilhado_id')->unsigned();
            $table->string('foto_path');
            $table->timestamps();

            $table->foreign('meu_album_compartilhado_id', 'mac_fotos_album_id_foreign')
                  ->references('id')
                  ->on('meu_album_compartilhados')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meu_album_compartilhado_fotos');
    }
}
