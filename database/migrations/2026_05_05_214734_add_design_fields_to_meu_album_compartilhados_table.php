<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDesignFieldsToMeuAlbumCompartilhadosTable extends Migration
{
    public function up()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->string('cor_fundo')->nullable()->default('#ffffff'); // cor de fundo hex
            $table->string('imagem_fundo')->nullable(); // imagem de fundo (path)
            $table->string('tipo_fundo')->nullable()->default('cor'); // 'cor' ou 'imagem'
            $table->string('foto_topo_mobile')->nullable(); // foto de capa mobile
            $table->string('foto_topo_web')->nullable(); // foto de capa web
        });
    }

    public function down()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->dropColumn(['cor_fundo', 'imagem_fundo', 'tipo_fundo', 'foto_topo_mobile', 'foto_topo_web']);
        });
    }
}
