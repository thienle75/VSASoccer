<?php


use App\Game;
use App\Season;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeasonTableSeeder extends Seeder
{
    //seed the database
    public function run()
    {
        DB::table('seasons')->delete();

        $season1 = Season::create([
            'name' => 'Season 1',
            'start_date' => '2015-02-11 00:00:00',
            'end_date' => '2015-05-06 23:59:59',
        ]);

        $season2 = Season::create([
            'name' => 'Season 2',
            'start_date' => '2015-05-13 00:00:00',
            'end_date' => '2015-07-22 23:59:59',
        ]);


        $season1Games = [
            '2015-02-11',
            '2015-02-18',
            '2015-02-25',
            '2015-03-04',
            '2015-03-11',
            '2015-03-18',
            '2015-03-25',
            '2015-04-01',
            '2015-04-08',
            '2015-04-15',
            '2015-04-22',
            '2015-04-29',
            '2015-05-06'
        ];

        foreach($season1Games as $season1Game){
            Game::create([
                'season_id' => $season1->id,
                'date' => $season1Game.' 00:00:00'
            ]);
        }

        $season2Games = [
            '2015-05-13',
            '2015-05-20',
            '2015-05-27',
            '2015-06-03',
            '2015-06-10',
            '2015-06-17',
            '2015-06-24',
            '2015-07-08',
            '2015-07-15',
            '2015-07-22',
            '2015-07-29',
            '2015-08-05',
            '2015-08-12',
            '2015-08-19',
            '2015-08-26',
            '2015-09-02',
            '2015-09-09',
            '2015-09-16',
            '2015-09-23',
            '2015-09-30',
            '2015-10-07',
            '2015-10-14',
            '2015-10-21',
            '2015-10-28',
            '2015-11-04',
            '2015-11-11',
            '2015-11-18',
            '2015-11-25',
            '2015-12-02',
            '2015-12-09',
            '2015-12-16',
            '2015-12-23',
            '2015-12-30'
        ];

        foreach($season2Games as $season2Game){
            Game::create([
                'season_id' => $season2->id,
                'date' => $season2Game.' 00:00:00'
            ]);
        }
    }
}