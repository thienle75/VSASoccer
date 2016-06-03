<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Excuse;
use App\Game;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\View;
use Input;
use Redirect;

class AttendanceController extends Controller
{
    /**
     * @return $this
     */
    public function index()
    {
        $token = Input::get('token');

        if(isset($token) && $token != ''){
            $decodedToken = base64_decode($token);
            $decodedObject = json_decode($decodedToken);

            $game = Game::find($decodedObject->gameId);
            $user = Auth::getUser();
            $player = $user->player()->getResults();

            $attendance = Attendance::where('game_id','=',$game->id)
                ->where('player_id','=',$player->id)
                ->first();

            if($attendance){
                return Redirect::to('/seasons')->with([
                    'info' => 'Already Responded.'
                ]);
            }else{
                return View::make('site.attendance.index')->with([
                    'gameDate' => $game->date,
                    'gameId' => $game->id
                ]);
            }
        }else{
            return Redirect::to('/seasons')->withErrors([
                'error' => 'Invalid token'
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAttendance()
    {
        $attending = Input::get('attending');
        $reason = Input::get('comments');

        $game = Game::find(Input::get('game_id'));
        $user = Auth::getUser();
        $player = $user->player()->getResults();

        $attendance = Attendance::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'attending' => $attending,
            'description' => $reason
        ]);

        if(!$attendance){
            return Redirect::to('/seasons')->with(['error'=>'Error saving attendance.']);
        }else{
            return Redirect::to('/seasons')->with(['success'=>'Attendance submitted.']);
        }
    }

    /**
     * add an attendance through the modal
     * @param $seasonId
     * @param $gameId
     * @return string
     */
    public function postAddAttendance($seasonId, $gameId)
    {
        $attendance = Attendance::create([
            'game_id' => $gameId,
            'player_id' => Input::get('playerId'),
            'attending' => Input::get('attending'),
            'description' => Input::get('description')
        ]);

        if($attendance){
            return 'success';
        }
    }

    /**
     * delete an attendance
     * @param $seasonId
     * @param $gameId
     * @return string
     */
    public function postDeleteAttendance($seasonId, $gameId)
    {
        $attendance = Attendance::find(Input::get('attendanceId'));

        if($attendance->delete()){
            return 'success';
        }
    }
}
