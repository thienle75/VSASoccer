<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance', function (Blueprint $table) {
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
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign('attendance_game_id_foreign');
            $table->dropForeign('attendance_player_id_foreign');
        });
    }
}
