<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToMeuAlbumCompartilhadosTable extends Migration
{
    public function up()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            
            // Se você quiser criar a chave estrangeira (opcional, mas recomendado)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
