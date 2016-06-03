<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysPotmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('potm', function (Blueprint $table) {
            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade');

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('player1_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('player2_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('player3_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('player4_id')
                ->references('id')->on('players')
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
        Schema::table('potm', function (Blueprint $table) {
            $table->dropForeign('potm_game_id_foreign');
            $table->dropForeign('potm_player_id_foreign');
            $table->dropForeign('potm_player1_id_foreign');
            $table->dropForeign('potm_player2_id_foreign');
            $table->dropForeign('potm_player3_id_foreign');
            $table->dropForeign('potm_player4_id_foreign');
        });
    }
}
