<?php

namespace App\Http\Controllers;

use App;
use App\Attendance;
use App\Excuse;
use App\Game;
use App\Player;
use App\Season;
use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\MessageBag;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Writer_Excel2007;
use Redirect;
use Input;
use Response;
use View;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //get all seasons
        $seasons = Season::all();

        //render view
        return View::make('site.seasons.games.index', ['seasons' => $seasons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $seasonId
     * @return \Illuminate\Contracts\View\View
     */
    public function create($seasonId)
    {
        if(Auth::user()->authorized()) {
            $season = Season::find($seasonId);
            $seasons = Season::get()->lists('name', 'id')->all();

            //render view
            return View::make('site.seasons.games.edit', [
                'season' => $season,
                'seasons' => $seasons,
                'game' => [],
                'action' => 'create'
            ]);
        }

        $errors = new MessageBag([
            'errors' => 'Access Denied'
        ]);

        return redirect()->to('seasons')->with([
            'errors' => $errors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param $seasonId
     * @return \Illuminate\Contracts\View\View
     */
    public function store($seasonId)
    {
        //TODO: add validation

        $seasons = Season::all();
        $game = new Game();
        $game->season_id = Input::get('season_id');
        $date = new Carbon(Input::get('gamedate'));
        $game->date = $date->toDateTimeString();

        if($game->save()){
            return Redirect::route('seasons.index', [
                'seasons' => $seasons
            ]);
        }else{
            //TODO: add error case
        }
    }

    /**
     * Display the specified resource.
     * @param $seasonId
     * @param $gameId
     * @return \Illuminate\Contracts\View\View
     */
    public function show($seasonId, $gameId)
    {
        $season = Season::find($seasonId);
        $game = Game::find($gameId);
        $selectedPlayers = $game->players();
        $selectedPlayersIds = [];

        foreach($selectedPlayers as $selectedPlayer){
            $selectedPlayersIds[] = $selectedPlayer->id;
        }

        $attendingIds = Attendance::where('attending','=','yes')
            ->where('game_id','=',$gameId)
            ->whereNotIn('player_id', $selectedPlayersIds)
            ->get()
            ->lists('player_id')
            ->all();

        $respondedIds = Attendance::where('game_id','=',$gameId)
            ->whereNotIn('player_id', $selectedPlayersIds)
            ->get()
            ->lists('player_id')
            ->all();

        $attendancePlayers = Player::whereNotIn('id', array_merge($selectedPlayersIds,$respondedIds))
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get();

        $remainingPlayers = Player::whereIn('id', $attendingIds)
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get();

        $teamsCount = $game->teams()->count();

        $currentDate = new Carbon('1 WEEK AGO');

        //render view
        return View::make('site.seasons.games.show', [
            'season' => $season,
            'game' => $game,
            'attendancePlayers' => $attendancePlayers,
            'remainingPlayers' => $remainingPlayers,
            'selectedPlayers' => $selectedPlayers,
            'teamCount' => $teamsCount,
            'editable' => !$currentDate->diff(new Carbon($game->date))->invert,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param $seasonId
     * @param $gameId
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($seasonId, $gameId)
    {
        if(Auth::user()->authorized()) {
            $season = Season::find($seasonId);
            $seasons = Season::get()->lists('name', 'id')->all();
            $game = Game::find($gameId);

            //render view
            return View::make('site.seasons.games.edit', [
                'season' => $season,
                'seasons' => $seasons,
                'game' => $game,
                'action' => 'edit'
            ]);
        }

        $errors = new MessageBag([
            'errors' => 'Access Denied'
        ]);

        return redirect()->to('seasons')->with([
            'errors' => $errors
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param $seasonId
     * @param $gameId
     * @return \Illuminate\Contracts\View\View
     */
    public function update($seasonId, $gameId)
    {
        //TODO: add validation

        $game = Game::find($gameId);
        $seasons = Season::all();

        $game->season_id = Input::get('season_id');
        $date = new Carbon(Input::get('gamedate'));
        $game->date = $date->toDateTimeString();

        if($game->save()){
            return View::make('site.seasons.index', [
                'seasons' => $seasons
            ]);
        }else{
            //TODO: add error case
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $seasonId
     * @param $gameId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function getGameSheet($seasonId, $gameId)
    {
        $game = Game::find($gameId);

        //set the file path and name
        $file_dir = storage_path('files');
        $file_name = "Game {$gameId}.xlsx";

        //instantiate the excel library and create a sheet
        $reportFile = new \PHPExcel();
        $reportFile->setActiveSheetIndex(0);
        $activeSheet = $reportFile->getActiveSheet();

        //TODO: fill up the spreadsheet

        //Writes each team to an excel file
        $rowCounter = 1;
        $teamCounter = 1;

        $centeredColumnStyle = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $borderStyle = [
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ];

        foreach($game->teams()->getResults() as $team) {
            $teamRGBColor = '000000';
            switch($team->color){
                case 'yellow':
                    $teamRGBColor = 'FFFF00';
                    break;
                case 'blue':
                    $teamRGBColor = '5b9bd5';
                    break;
                case 'red':
                    $teamRGBColor = 'ff5050';
                    break;
            }

            //Team Title
            $trTeamString = 'TR: '.$team->getTeamRating().'%';
            $prTeamString = 'PR: '.$team->getAveragePlayerRating().'%';
            $teamStatsString = '('.$trTeamString.', '.$prTeamString.')';


            $teamHeaderStyle = [
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => $teamRGBColor
                    ]
                ],
                'borders' => [
                    'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                ]
            ];
            $activeSheet->setCellValue('A'.$rowCounter, 'Team: #'.$teamCounter.' '.$teamStatsString);
            $activeSheet->mergeCells('A'.$rowCounter.':D'.$rowCounter);
            $activeSheet->getStyle('A'.$rowCounter.':D'.$rowCounter)->applyFromArray($teamHeaderStyle);

            $rowCounter++;

            // Team Column Names
            $teamColumnStyle = [
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => $teamRGBColor
                    ]
                ],
                'borders' => [
                    'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                ]
            ];

            $activeSheet->getStyle('A'.$rowCounter)->applyFromArray($teamColumnStyle);
            $activeSheet->getStyle('B'.$rowCounter)->applyFromArray($teamColumnStyle);
            $activeSheet->getStyle('C'.$rowCounter)->applyFromArray($teamColumnStyle);
            $activeSheet->getStyle('D'.$rowCounter)->applyFromArray($teamColumnStyle);
            $activeSheet->getStyle('C')->applyFromArray($centeredColumnStyle);
            $activeSheet->getStyle('D')->applyFromArray($centeredColumnStyle);

            $activeSheet->SetCellValue('A' . $rowCounter, 'Name')
                ->SetCellValue('B' . $rowCounter, 'Position')
                ->SetCellValue('C' . $rowCounter, 'Teammate Rating')
                ->SetCellValue('D' . $rowCounter, 'Player Rating');

            $rowCounter++;

            // Add the Players
            foreach($team->players()->getResults() as $player) {
                // adds players name, pos, and ratings to the spreadsheet
                $activeSheet->SetCellValue('A' . $rowCounter, $player->formalName())
                    ->SetCellValue('B' . $rowCounter, $player->position)
                    ->SetCellValue('C' . $rowCounter, number_format($player->teammate_rating*100,2).'%')
                    ->SetCellValue('D' . $rowCounter, number_format($player->player_rating*100,2).'%');

                $activeSheet->getStyle('A' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('B' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('C' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('D' . $rowCounter)->applyFromArray($borderStyle);

                // increments the row to add data
                $rowCounter++;
            }

            $teamCounter++;
            $rowCounter++;
            $rowCounter++;
        }

        // Write Game Log to excel file
        $rowCounter = 1;

        // Game Log Title

        $gameLogHeaderStyle = [
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ];
        $activeSheet->SetCellValue('F'. $rowCounter, 'Game Log');
        $activeSheet->mergeCells('F'.$rowCounter.':I'.$rowCounter);
        $activeSheet->getStyle('F'.$rowCounter.':I'.$rowCounter)->applyFromArray($gameLogHeaderStyle);

        $rowCounter++;

        // Game Log Column Titles
        $activeSheet->SetCellValue('F'. $rowCounter, 'Goal By')
            ->getStyle('F'. $rowCounter)
            ->applyFromArray($gameLogHeaderStyle);
        $activeSheet->SetCellValue('G'. $rowCounter, 'Assisted From')
            ->getStyle('G'. $rowCounter)
            ->applyFromArray($gameLogHeaderStyle);
        $activeSheet->SetCellValue('H'. $rowCounter, 'Opponent Team')
            ->getStyle('H'. $rowCounter)
            ->applyFromArray($gameLogHeaderStyle);
        $activeSheet->SetCellValue('I'. $rowCounter, 'Own Goal')
            ->getStyle('I'. $rowCounter)
            ->applyFromArray($gameLogHeaderStyle);

        $activeSheet->getStyle('H')->applyFromArray($centeredColumnStyle);
        $activeSheet->getStyle('I')->applyFromArray($centeredColumnStyle);

        $rowCounter++;

        $assistPlayerIds = [];

        $stats = $game->stats();
        $assistCount = 0;
        $goalCount = 0;

        // display all the assists
        foreach($stats as $stat){
            foreach($stat->assists()->getResults() as $assist){
                $goalBy = $assist->player()->getResults();
                $assistedBy = $stat->player()->first()->formalName();
                $oppTeam = $assist->oppTeam()->getResults();

                if($goalBy) {
                    $assistPlayerIds[] = $goalBy->id;
                    $activeSheet->SetCellValue('F' . $rowCounter, $goalBy->formalName());
                }
                $activeSheet->SetCellValue('G'. $rowCounter, $assistedBy);
                if($oppTeam) {
                    $activeSheet->SetCellValue('H' . $rowCounter, ucfirst($oppTeam->color));
                }

                $activeSheet->getStyle('F' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('G' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('H' . $rowCounter)->applyFromArray($borderStyle);
                $activeSheet->getStyle('I' . $rowCounter)->applyFromArray($borderStyle);

                $rowCounter++;
                $assistCount++;
            }
        }

        // display all the goals
        foreach($stats as $stat){
            foreach($stat->goals()->getResults() as $goal){
                $goalBy = $stat->player()->first();
                $oppTeam = $goal->oppTeam()->getResults();

                if(in_array($goalBy->id, $assistPlayerIds)){
                    unset($assistPlayerIds[array_search($goalBy->id, $assistPlayerIds)]);
                }else{
                    $activeSheet->SetCellValue('F'. $rowCounter, $goalBy->formalName());
                    if($oppTeam) {
                        $activeSheet->SetCellValue('H' . $rowCounter, ucfirst($oppTeam->color));
                    }
                    $activeSheet->SetCellValue('I'. $rowCounter, $goal->own_goal ? 'X' : '');

                    $activeSheet->getStyle('F' . $rowCounter)->applyFromArray($borderStyle);
                    $activeSheet->getStyle('G' . $rowCounter)->applyFromArray($borderStyle);
                    $activeSheet->getStyle('H' . $rowCounter)->applyFromArray($borderStyle);
                    $activeSheet->getStyle('I' . $rowCounter)->applyFromArray($borderStyle);

                    $rowCounter++;
                    $goalCount++;
                }
            }
        }

        if($assistCount==0 && $goalCount==0){
            $activeSheet->getStyle('F3:I20')->applyFromArray($borderStyle);
        }

        // Fix column Widths
        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);

        //writing the file to the server
        $objWriter = new PHPExcel_Writer_Excel2007($reportFile);
        $objWriter->save($file_dir .'/'. $file_name);

        //downloading the file
        return Response::download($file_dir .'/'. $file_name);
    }
}
