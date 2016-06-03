<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_points');
            $table->boolean('player_of_the_match');
            $table->boolean('player_of_the_match_nomination');
            $table->unsignedInteger('player_of_the_match_nomination_points');
            $table->integer('team_spirit_points');
            $table->boolean('is_captain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stats');
    }
}
