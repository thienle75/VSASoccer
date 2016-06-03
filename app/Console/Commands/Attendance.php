<?php

namespace App\Console\Commands;

use App\Game;
use App\Player;
use App\Season;
use DB;
use Illuminate\Console\Command;
use Log;

class Attendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soccer:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out the attendance emails.';

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
        Log::info('Start of attendance cronjob');

        $players = Player::all();

        foreach($players as $player){
            $user = $player->user()->getResults();

            //TODO: add game and generate token
            $date = date('Y-m-d');

            $game = Game::where('date','=',$date)->first();

            if(!$game){
                Log::info('Game found');
                $season = Season::where('start_date','<=',$date)
                    ->where('end_date','>=',$date)
                    ->first();

                $game = Game::create([
                    'season_id' => $season->id,
                    'date' => $date
                ]);
            }

            $jsonData = [
                'gameId' => $game->id
            ];

            $token = base64_encode(json_encode($jsonData));

            if(!\App::environment('production')) {
                $data = [
                    "link" => 'http://rawtest.soccer.com/attendance/?token='.$token
                ];
            } else {
                $data = [
                    "link" => 'http://soccer.engagepeople.com/attendance/?token='.$token
                ];
            }

            if($user){
                $email = \Mail::send('emails.attendance',$data, function($message) use ($user){
                    $message->to($user->email);
                    $message->subject('Soccer Attendance');
                });

                if($email){
                    Log::info('Emailed '.$user->email.' successfully');
                } else {

                    Log::info('Failed to email '.$user->email);
                }
            }
        }

        Log::info('End of cronjob');
    }
}
