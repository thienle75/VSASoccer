@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">Ratings</h1>
    <hr/>
    @if(Input::get('dir')=='desc' || !Input::has('dir'))
        <?php $dir = 'asc'; ?>
    @else
        <?php $dir = 'desc'; ?>
    @endif

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    Seasons
                </a>
            </div>
            <ul class="nav navbar-nav">
                <li {{ $selectedSeason==null ? 'class=active' : '' }}>
                    <a href="{{ URL::route('ratings.index') }}">Overall</a>
                </li>
                @foreach($seasons as $season)
                    <li {{ $selectedSeason==$season->id ? 'class=active' : '' }}>
                        <a href="{{ URL::route('ratings.seasons.show',['seasonId'=>$season->id]) }}">{{ $season->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <table class="table table-striped table-hover table-condensed table-bordered table-fluid">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th id="nameColumn" class="text-center sortable" data-column="name" data-dir="{{ $dir }}" onclick="javascript: sort('nameColumn');">
                Name
                @if(Input::get('column') == 'name')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="wdlColumn" class="text-center">WDL %</th>
            <th id="teamPlayerColumn" class="text-center sortable" data-column="team_player_rating" data-dir="{{ $dir }}" onclick="javascript: sort('teamPlayerColumn');">
                Teammate Rating
                @if(Input::get('column') == 'team_player_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="playerColumn" class="text-center sortable" data-column="player_rating" data-dir="{{ $dir }}" onclick="javascript: sort('playerColumn');">
                Player Rating
                @if(Input::get('column') == 'player_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="teamPointsColumn" class="text-center sortable" data-column="team_points_rating" data-dir="{{ $dir }}" onclick="javascript: sort('teamPointsColumn');">
                Team Points Rating
                @if(Input::get('column') == 'team_points_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="potmColumn" class="text-center sortable" data-column="potm_rating" data-dir="{{ $dir }}" onclick="javascript: sort('potmColumn');">
                POTM Rating
                @if(Input::get('column') == 'potm_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="potmnColumn" class="text-center sortable" data-column="potmn_rating" data-dir="{{ $dir }}" onclick="javascript: sort('potmnColumn');">
                POTMN Rating
                @if(Input::get('column') == 'potmn_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="assistsColumn" class="text-center sortable" data-column="assists_rating" data-dir="{{ $dir }}" onclick="javascript: sort('assistsColumn');">
                Assists Rating<br/>(assists/game)
                @if(Input::get('column') == 'assists_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="goalsColumn" class="text-center sortable" data-column="goals_rating" data-dir="{{ $dir }}" onclick="javascript: sort('goalsColumn');">
                Goals Rating<br/>(goals/game)
                @if(Input::get('column') == 'goals_rating')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
        </tr>
        </thead>

        @foreach($stats as $rank=>$player)
            <tr>
                <td class="text-center">{{ $rank + 1 }}</td>
                <td><a href="{{ URL::route('players.show',['playerId'=> $player['id']]) }}"> {{ $player['name'] }}</a></td>
                <td class="text-center col-lg-2">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" style="width: {{ $player['stats']['rating']['wdlWin'] }}" title="{{ $player['stats']['rating']['wdlWin'] }} Wins">
                            <span class="sr-only">{{ $player['stats']['rating']['wdlWin'] }} Wins</span>
                        </div>
                        <div class="progress-bar progress-bar-warning" style="width: {{ $player['stats']['rating']['wdlDraw'] }}" title="{{ $player['stats']['rating']['wdlDraw'] }} Draws">
                            <span class="sr-only">{{ $player['stats']['rating']['wdlDraw'] }} Draw</span>
                        </div>
                        <div class="progress-bar progress-bar-danger" style="width: {{ $player['stats']['rating']['wdlLost'] }}" title="{{ $player['stats']['rating']['wdlLost'] }} Loses">
                            <span class="sr-only">{{ $player['stats']['rating']['wdlLost'] }} Loses</span>
                        </div>
                    </div>
                </td>
                <td class="text-right">{{ $player['stats']['rating']['teamPlayerRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['playerRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['teamPointsRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['potmRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['potmnRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['assistsRating'] }}</td>
                <td class="text-right">{{ $player['stats']['rating']['goalsRating'] }}</td>
            </tr>
        @endforeach
    </table>

<script type="text/javascript">
    function sort(id) {
        window.location = window.location.pathname + '?' + $.param($('#' + id).data());
    }
</script>
@endsection