<?php


use App\Player;
use App\PlayerTeams;
use App\Stat;
use App\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerTableSeeder extends Seeder
{
    //seed the database
    public function run()
    {
        //Player Seeder
        DB::table('players')->delete();

        $players = $this->createPlayers();
    }

    public function createPlayers()
    {
        $players[] = Player::create([
            'first_name' => 'Andres',
            'last_name' => 'Llanos',
            'position' => 'CM',
            'status' => 'butterfly catcher',
        ]);

        $players[] = Player::create([
            'first_name' => 'Alan',
            'last_name' => 'Reisler',
            'position' => 'CD',
            'status' => 'inactive',
        ]);

        $players[] = Player::create([
            'first_name' => 'Aldo',
            'last_name' => 'Andriani',
            'position' => 'ST',
            'status' => 'active',
        ]);

        $players[] = Player::create([
            'first_name' => 'Alessandro',
            'last_name' => 'Andriani',
            'position' => 'Alpha',
            'status' => 'active',
        ]);

        $players[] = Player::create([
            'first_name' => 'Bruce',
            'last_name' => 'Silcoff',
            'position' => 'CF',
            'status' => 'active',
        ]);

        $players[] = Player::create([
            'first_name' => 'Danny',
            'last_name' => 'Iaboni',
            'position' => 'CD',
            'status' => 'butterfly catcher',
        ]);

        $players[] = Player::create([
            'first_name' => 'Dragomir',
            'last_name' => 'Mladjenovic',
            'position' => 'CD',
            'has_bonus' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Danielle',
            'last_name' => 'Sannuto',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Fern',
            'last_name' => 'Amorim',
            'position' => 'CD',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Julia',
            'last_name' => 'Luu',
            'position' => 'CD',
            'status' => 'inactive',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Jonathan',
            'last_name' => 'Silver',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Len',
            'last_name' => 'Covello',
            'position' => 'CM',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Michael',
            'last_name' => 'Bao',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Mario',
            'last_name' => 'Crudo',
            'position' => 'CM',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Mac',
            'last_name' => 'Do',
            'position' => 'CD',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Michael',
            'last_name' => 'Pouris',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Mitko',
            'last_name' => 'Tochev',
            'position' => 'RW',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Nick',
            'last_name' => 'Boccone',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Olex',
            'last_name' => 'Vakhnin',
            'position' => 'GK',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Peter',
            'last_name' => 'Coliviras',
            'position' => 'CM',
            'status' => 'inactive',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Peter',
            'last_name' => 'Meyers',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Pat',
            'last_name' => 'Stagliano',
            'position' => 'CF',
            'status' => 'butterfly catcher',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Ron',
            'last_name' => 'Benegbi',
            'position' => 'GK',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Raahul',
            'last_name' => 'Biswas',
            'position' => 'CD',
            'status' => 'inactive',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Robert',
            'last_name' => 'Moerland',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Ravi',
            'last_name' => 'Parsaud',
            'position' => 'CF',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Shawn',
            'last_name' => 'Stephenson',
            'position' => 'CF',
            'status' => 'inactive',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Tom',
            'last_name' => 'Covello',
            'position' => 'CM',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Thien',
            'last_name' => 'Le',
            'position' => 'CD',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Tuan',
            'last_name' => 'To',
            'position' => 'CF',
            'status' => 'butterfly catcher',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);

        $players[] = Player::create([
            'first_name' => 'Yao',
            'last_name' => 'Tang',
            'position' => 'CD',
            'status' => 'active',
            'has_bonus' => 'false',
            'has_penalty' => 'false',
        ]);
    }
}