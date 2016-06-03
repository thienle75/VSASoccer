<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stat_id');
            $table->unsignedInteger('opponent_team_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
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
        Schema::drop('assists');

    }
}
