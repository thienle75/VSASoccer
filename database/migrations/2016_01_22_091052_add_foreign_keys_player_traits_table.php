<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysPlayerTraitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player_traits', function (Blueprint $table) {
            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->foreign('trait_id')
                ->references('id')->on('traits')
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
        Schema::table('player_traits', function (Blueprint $table) {
            $table->dropForeign('player_traits_player_id_foreign');
            $table->dropForeign('player_traits_trait_id_foreign');
        });
    }
}
