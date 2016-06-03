<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awards', function (Blueprint $table) {
            $table->foreign('award_type_id')
                ->references('id')->on('award_types')
                ->onDelete('cascade');

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('season_id')
                ->references('id')->on('seasons')
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
        Schema::table('awards', function (Blueprint $table) {
            $table->dropForeign('awards_award_type_id_foreign');
            $table->dropForeign('awards_player_id_foreign');
            $table->dropForeign('awards_season_id_foreign');
        });
    }
}
