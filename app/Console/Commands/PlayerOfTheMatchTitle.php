<?php

namespace App\Console\Commands;

use App\Game;
use Illuminate\Console\Command;
use Log;

class PlayerOfTheMatchTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soccer:potm-title';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Computes and updates the player stats for POTM nominations and title.';

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
        Log::info('Start of Player of the match title cronjob');

        //find today's game
        $date = date('Y-m-d', strtotime('2 DAYS AGO'));
        $game = Game::where('date','=',$date)->first();

        if($game) {
            Log::info('Game found');

            //find all the players that played
            $players = $game->players();
            $nominations = [];

            //loop over the players
            foreach ($players as $player) {
                $points = $player->computeNominationPoints($game->id);
                $nominations[$points][] = $player;

                $stat = $player->getStatsForGame($game->id);
                $stat->player_of_the_match_nomination_points = $points;
                $stat->save();
            }

            array_multisort(array_keys($nominations),SORT_NUMERIC, SORT_DESC, $nominations);

            for($i=0; $i<4; $i++){
                if(isset($nominations[$i])) {
                    foreach ($nominations[$i] as $player) {
                        $stat = $player->getStatsForGame($game->id);
                        $stat->player_of_the_match_nomination = true;

                        if ($i == 0) {
                            $stat->player_of_the_match = true;
                        }

                        if($stat->save()){
                            Log::info('Updated player_id='.$player->id.' stats');
                        }else {
                            Log::info('Failed to update player_id='.$player->id.' stats');
                        }
                    }
                }
            }
        }

        Log::info('End of cronjob');
    }
}
