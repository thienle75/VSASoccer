<?php

namespace App\Console\Commands;

use App\Game;
use Illuminate\Console\Command;
use Log;

class PlayerOfTheMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soccer:potm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out the player of the match voting.';

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
        Log::info('Start of Player of the match cronjob');

        //find today's game
        $date = date('Y-m-d');
        $game = Game::where('date','=',$date)->first();

        if($game) {
            Log::info('Game found');

            //find all the players that played
            $players = $game->players();

            //loop over the players
            foreach ($players as $player) {
                $user = $player->user()->getResults();

                $jsonData = [
                    'gameId' => $game->id
                ];

                $token = base64_encode(json_encode($jsonData));

                if (!\App::environment('production')) {
                    $data = [
                        "link" => 'http://rawtest.soccer.com/potm/?token=' . $token
                    ];
                } else {
                    $data = [
                        "link" => 'http://soccer.engagepeople.com/potm/?token=' . $token
                    ];
                }

                if ($user) {
                    $email = \Mail::send('emails.potm', $data, function ($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Player Of The Match');
                    });

                    if ($email) {
                        Log::info('Emailed '.$user->email.' successfully');
                    } else {
                        Log::info('Failed to email '.$user->email);
                    }
                }
            }
        }

        Log::info('End of cronjob');
    }
}
