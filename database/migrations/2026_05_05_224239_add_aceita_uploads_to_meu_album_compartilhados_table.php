<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAceitaUploadsToMeuAlbumCompartilhadosTable extends Migration
{
    public function up()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->boolean('aceita_uploads')->default(true);
        });
    }

    public function down()
    {
        Schema::table('meu_album_compartilhados', function (Blueprint $table) {
            $table->dropColumn('aceita_uploads');
        });
    }
}
