<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyPlayerTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player_teams', function (Blueprint $table) {
            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');
            $table->foreign('stat_id')
                ->references('id')->on('stats')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_teams', function (Blueprint $table) {
            $table->dropForeign('player_teams_player_id_foreign');
            $table->dropForeign('player_teams_team_id_foreign');
            $table->dropForeign('player_teams_stat_id_foreign');
        });
    }
}
