<?php

use App\Footage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FootageTableSeeder extends Seeder
{
    //seed the database
    public function run()
    {
        DB::table('footage')->delete();
        Footage::create(array(

            'url' => 'yWbBj-DWxEY',
            'name' => 'camera 1',
            'game_id' => '1',
        ));
        Footage::create(array(

            'url' => 'cCW4qJcvEsU',
            'name' => 'camera 1',
            'game_id' => '2',
        ));
        Footage::create(array(

            'url' => 'W7rXm1XR85I',
            'name' => 'camera 1',
            'game_id' => '3',
        ));
        Footage::create(array(

            'url' => 'fPnPFQlq1q4',
            'name' => 'camera 1',
            'game_id' => '4',
        ));
        Footage::create(array(

            'url' => 'WgoXHKtTLtM',
            'name' => 'camera 2',
            'game_id' => '4',
        ));
        Footage::create(array(

            'url' => 'hZIldx57f5E',
            'name' => 'camera 1',
            'game_id' => '5',
        ));
        Footage::create(array(

            'url' => 'nONTpAGdBuY',
            'name' => 'camera 1',
            'game_id' => '6',
        ));
        Footage::create(array(

            'url' => 'dfGu_prLQ3U',
            'name' => 'camera 1',
            'game_id' => '7',
        ));
        Footage::create(array(

            'url' => 'lHbCQmaQdbQ',
            'name' => 'camera 1',
            'game_id' => '8',
        ));
        Footage::create(array(

            'url' => 'BQNjH_wFtAc',
            'name' => 'camera 1',
            'game_id' => '9',
        ));
        Footage::create(array(

            'url' => 'pYJb0Sjab6c',
            'name' => 'camera 1',
            'game_id' => '10',
        ));
        Footage::create(array(

            'url' => 'qIA1v9ih1EE',
            'name' => 'camera 1',
            'game_id' => '11',
        ));
        Footage::create(array(

            'url' => 'jie2q8_V2kE',
            'name' => 'camera 2',
            'game_id' => '11',
        ));
        Footage::create(array(

            'url' => '_PG09AYJmS0',
            'name' => 'camera 1',
            'game_id' => '12',
        ));
        Footage::create(array(

            'url' => 'TETEDc8390A',
            'name' => 'camera 1',
            'game_id' => '13',
        ));
        Footage::create(array(

            'url' => 'txfK4daacP4',
            'name' => 'camera 1',
            'game_id' => '14',
        ));
        Footage::create(array(

            'url' => 'bjwPe1KKofA',
            'name' => 'camera 1',
            'game_id' => '15',
        ));
        Footage::create(array(
            'url' => 'KRQ8ZqKC9dQ',
            'name' => 'camera 1',
            'game_id' => '16',
        ));
    }
}