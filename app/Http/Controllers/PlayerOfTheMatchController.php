<?php

namespace App\Http\Controllers;

use App\Game;
use App\Player;
use App\POTMVote;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use View;

class PlayerOfTheMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Input::get('token');

        if(isset($token) && $token != ''){
            $decodedToken = base64_decode($token);
            $decodedObject = json_decode($decodedToken);

            $game = Game::find($decodedObject->gameId);
            $user = Auth::getUser();
            $thePlayer = $user->player()->getResults();

            $gamePlayerIds = [];

            foreach($game->players() as $player){
                if($player->id != $thePlayer->id) {
                    $gamePlayerIds[] = $player->id;
                }
            }

            $players = Player::whereIn('id', $gamePlayerIds)
                ->get();

            $potmVotes = $thePlayer->potmVotes()
                ->where('game_id','=',$game->id)
                ->first();

            if($potmVotes){
                return Redirect::to('/seasons')->with([
                    'info' => 'Already Voted.'
                ]);
            }else{
                return View::make('site.potm.index')->with([
                    'gameDate' => $game->date,
                    'gameId' => $game->id,
                    'players' => $players
                ]);
            }
        }else{
            return Redirect::to('/seasons')->withErrors([
                'error' => 'Invalid token'
            ]);
        }
    }

    /**
     * Save the vote.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAttendance()
    {
        $player1_id = Input::get('player1');
        $player2_id = Input::get('player2');
        $player3_id = Input::get('player3');
        $player4_id = Input::get('player4');

        $game = Game::find(Input::get('game_id'));
        $user = Auth::getUser();
        $player = $user->player()->getResults();

        $jsonData = [
            'gameId' => $game->id
        ];

        $token = base64_encode(json_encode($jsonData));

        if(in_array($player->id,[$player1_id,$player2_id,$player3_id,$player4_id])){
            return Redirect::to('/potm?&token='.$token)
                ->with([
                    'error' => 'Can not vote for yourself.'
                ]);
        }elseif(in_array($player1_id,[$player2_id,$player3_id,$player4_id])){
            return Redirect::to('/potm?&token='.$token)
                ->with([
                    'error' => 'Nominations must be distinct.1'
                ]);
        }elseif(in_array($player2_id,[$player1_id,$player3_id,$player4_id])){
            return Redirect::to('/potm?&token='.$token)
                ->with([
                    'error' => 'Nominations must be distinct.2'
                ]);
        }elseif(in_array($player3_id,[$player2_id,$player1_id,$player4_id])){
            return Redirect::to('/potm?&token='.$token)
                ->with([
                    'error' => 'Nominations must be distinct.3'
                ]);
        }elseif(in_array($player4_id,[$player2_id,$player3_id,$player1_id])){
            return Redirect::to('/potm?&token='.$token)
                ->with([
                    'error' => 'Nominations must be distinct.4'
                ]);
        }else{
            $potmVote = POTMVote::create([
                'game_id' => $game->id,
                'player_id' => $player->id,
                'player1_id' => $player1_id,
                'player2_id' => $player2_id,
                'player3_id' => $player3_id,
                'player4_id' => $player4_id
            ]);

            if(!$potmVote){
                return Redirect::to('/seasons')->with(['error'=>'Error saving POTM Vote.']);
            }else{
                return Redirect::to('/seasons')->with(['success'=>'POTM Vote submitted.']);
            }
        }
    }
}
