<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemetenteToMeuAlbumCompartilhadoFotosTable extends Migration
{
    public function up()
    {
        Schema::table('meu_album_compartilhado_fotos', function (Blueprint $table) {
            $table->string('remetente_nome')->nullable();
            $table->text('remetente_mensagem')->nullable();
        });
    }

    public function down()
    {
        Schema::table('meu_album_compartilhado_fotos', function (Blueprint $table) {
            $table->dropColumn(['remetente_nome', 'remetente_mensagem']);
        });
    }
}
