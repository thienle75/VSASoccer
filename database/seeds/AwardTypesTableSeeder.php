<?php


use App\AwardType;
use App\Game;
use App\Season;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AwardTypesTableSeeder extends Seeder
{
    //seed the database
    public function run()
    {
        DB::table('award_types')->delete();

        $types = [
            'Golden Boot' => 'Most Goals in the season',
            'Golden Compass' => 'Most Own Goals in the season',
            'Golden Ball' => 'Most Goals in a single game',
            'Attendance' => 'The highest attendance for the season',
            'Red Cross' => 'The most weeks missed due to injury',
            'Teammate of the Season' => 'The highest Teammate rating for the season',
            'Golden Player' => 'The highest Player rating for the season',
            'Player of the Season' => 'Most Player of the match nomination points for the season',
            'Playmaker of the Season' => 'Most Assists for the season',
            'Most Improved' => 'Biggest positive change in teammate and player rating',
            'Team Spirit' => 'Highest Team Spirit Points for the season',
            "Winner's Circle" => 'More than 50% wins in a season'
        ];

        foreach($types as $name=>$description){
            $awardType = AwardType::create([
                'name' => $name,
                'description' => $description
            ]);
        }
    }
}