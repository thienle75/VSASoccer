<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        //call the seeder to fll the users table
        $this->call('SeasonTableSeeder');
        $this->call('FootageTableSeeder');
        $this->call('PlayerTableSeeder');

        Model::reguard();
    }
}
