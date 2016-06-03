<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->foreign('stat_id')
                ->references('id')->on('stats')
                ->onDelete('cascade');
        });
        Schema::table('goals', function (Blueprint $table) {
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
        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign('goals_stat_id_foreign');
        });
        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign('goals_team_id_foreign');
        });
    }
}
