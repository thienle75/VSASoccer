<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyAssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assists', function (Blueprint $table) {
            $table->foreign('stat_id')
                ->references('id')->on('stats')
                ->onDelete('cascade');

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('opponent_team_id')
                ->references('id')->on('teams')
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
        Schema::table('assists', function (Blueprint $table) {
            $table->dropForeign('assists_stat_id_foreign');
            $table->dropForeign('assists_player_id_foreign');
            $table->dropForeign('assists_opponent_team_id_foreign');
        });
    }
}
