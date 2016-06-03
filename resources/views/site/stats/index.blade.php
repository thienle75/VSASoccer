@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">Stats</h1>
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
                    <a href="{{ URL::route('stats.index') }}">Overall</a>
                </li>
                @foreach($seasons as $season)
                    <li {{ $selectedSeason==$season->id ? 'class=active' : '' }}>
                        <a href="{{ URL::route('stats.seasons.show',['seasonId'=>$season->id]) }}">{{ $season->name }}</a>
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
            <th id="positionColumn" class="text-center">Position</th>
            <th id="gamesPlayedColumn" class="text-center sortable" data-column="games_played" data-dir="{{ $dir }}" onclick="javascript: sort('gamesPlayedColumn');">
                Games Played
                @if(Input::get('column') == 'games_played')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="excusesColumn" class="text-center sortable" data-column="excuses" data-dir="{{ $dir }}" onclick="javascript: sort('excusesColumn');">
                Excuses
                @if(Input::get('column') == 'excuses')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="teamPointsColumn" class="text-center sortable" data-column="team_points" data-dir="{{ $dir }}" onclick="javascript: sort('teamPointsColumn');">
                Team Points
                @if(Input::get('column') == 'team_points')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="potmColumn" class="text-center sortable" data-column="potm" data-dir="{{ $dir }}" onclick="javascript: sort('potmColumn');">
                POTM Titles
                @if(Input::get('column') == 'potm')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="potmnColumn" class="text-center sortable" data-column="potmn" data-dir="{{ $dir }}" onclick="javascript: sort('potmnColumn');">
                POTMN
                @if(Input::get('column') == 'potmn')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="potmnPointsColumn" class="text-center sortable" data-column="nomination_points" data-dir="{{ $dir }}" onclick="javascript: sort('potmnPointsColumn');">
                POTMN Points
                @if(Input::get('column') == 'nomination_points')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="assistsColumn" class="text-center sortable" data-column="assists" data-dir="{{ $dir }}" onclick="javascript: sort('assistsColumn');">
                Assists
                @if(Input::get('column') == 'assists')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="goalsColumn" class="text-center sortable" data-column="goals" data-dir="{{ $dir }}" onclick="javascript: sort('goalsColumn');">
                Goals
                @if(Input::get('column') == 'goals')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="ownGoalsColumn" class="text-center sortable" data-column="own_goals" data-dir="{{ $dir }}" onclick="javascript: sort('ownGoalsColumn');">
                Own Goals
                @if(Input::get('column') == 'own_goals')
                    <i class="fa fa-sort-{{ $dir }}"></i>
                @else
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            <th id="teamSpiritColumn" class="text-center sortable" data-column="team_spirit_points" data-dir="{{ $dir }}" onclick="javascript: sort('teamSpiritColumn');">
                Team Spirit Points
                @if(Input::get('column') == 'team_spirit_points')
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
                <td class="text-center">{{ $player['position'] }}</td>
                <td class="text-right">{{ $player['stats']['games_played'] }}</td>
                <td class="text-right">{{ $player['stats']['excuses'] }}</td>
                <td class="text-right">{{ $player['stats']['team_points'] }}</td>
                <td class="text-right">{{ $player['stats']['potm'] }}</td>
                <td class="text-right">{{ $player['stats']['potmn'] }}</td>
                <td class="text-right">{{ $player['stats']['nomination_points'] }}</td>
                <td class="text-right">{{ $player['stats']['assists'] }}</td>
                <td class="text-right">{{ $player['stats']['goals'] }}</td>
                <td class="text-right">{{ $player['stats']['own_goals'] }}</td>
                <td class="text-right">{{ $player['stats']['team_spirit_points'] }}</td>
            </tr>
        @endforeach
    </table>

<script type="text/javascript">
    function sort(id) {
        window.location = window.location.pathname + '?' + $.param($('#' + id).data());
    }
</script>
@endsection