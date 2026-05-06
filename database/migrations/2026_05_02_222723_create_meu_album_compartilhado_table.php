<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeuAlbumCompartilhadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meu_album_compartilhados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('foto_topo')->nullable();
            $table->text('texto_personalizado')->nullable();
            $table->text('pequena_mensagem')->nullable();
            $table->string('google_drive_folder_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meu_album_compartilhados');
    }
}
