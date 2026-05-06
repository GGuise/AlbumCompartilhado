<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialLinksToTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $user) {
            $user->string('instagram')->nullable();
            $user->string('facebook')->nullable();
            $user->string('twitter')->nullable();
            $user->string('youtube')->nullable();
            $user->string('whatsapp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $user) {
            $user->dropColumn(['instagram', 'facebook', 'twitter', 'youtube', 'whatsapp']);
        });
    }
}
