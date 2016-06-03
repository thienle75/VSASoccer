<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyFootageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('footage', function (Blueprint $table) {
            $table->foreign('game_id')
                ->references('id')->on('games')
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
        Schema::table('footage', function (Blueprint $table) {
            $table->dropForeign('footage_game_id_foreign');
        });
    }
}
