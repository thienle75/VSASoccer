<?php

namespace App\Console\Commands;

use App\Player;
use Illuminate\Console\Command;
use Log;

class Ratings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soccer:ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recomputes the player and teammate ratings for all the players.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('Start of ratings cronjob');

        $players = Player::all();

        foreach($players as $player){
            $ratings = $player->computeRating(null, false);

            $player->player_rating = $ratings['playerRating'];
            $player->teammate_rating = $ratings['teamPlayerRating'];

            if($player->save()){
                Log::info('Updated player_id='.$player->id.' ratings');
            }else{
                Log::info('Failed to update player_id='.$player->id.' ratings');
            }
        }

        if (!\App::environment('production')) {
            $link = 'http://rawtest.soccer.com/ratings';
        }else{
            $link = 'http://soccer.engagepeople.com/ratings';
        }


        $hook = "https://hooks.slack.com/services/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";

        $data_string = [
            "username" => 'EPFA Official',
            "icon_url" => "http://www.ifanxp.com/wp-content/uploads/2012/07/ref-icon-125x125.png",

            "channel" => '#soccer',
            /*
                you can do @username for messages directly to a user,
                they will see it as if though slackbot msged them
            */

            "text" => "Player Ratings have been updated, please see ".$link
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $hook);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'payload='.json_encode($data_string));

        curl_exec($ch);
        curl_close($ch);

        Log::info('End of cronjob');
    }
}
