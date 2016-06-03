<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysExcusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('excuses', function (Blueprint $table) {
            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade');

            $table->foreign('player_id')
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
        Schema::table('excuses', function (Blueprint $table) {
            $table->dropForeign('excuses_game_id_foreign');
            $table->dropForeign('excuses_player_id_foreign');
        });
    }
}
