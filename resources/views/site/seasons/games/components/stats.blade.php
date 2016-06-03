<table id="playerStats" class="table table-striped table-hover table-condensed table-bordered">
    <thead class="">
    <tr>
        <th>Name</th>
        <th>Team</th>
        <th class="text-center">Team Points</th>
        <th class="text-center">Goals</th>
        <th class="text-center">Own Goals</th>
        <th class="text-center">Assists</th>
        <th class="text-center">TS Points</th>
        <th class="text-center">POTM</th>
        <th class="text-center">POTMN</th>
        <th class="text-center">POTMN Points</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($selectedPlayers as $player)
        <?php
        $stats = $player->calculateStats($player->getStatsForGame($game->id));
        $team = $player->getTeamForGame($game->id);
        ?>
        <tr>
            <td>{{ $player->formalName() }}</td>
            <td>{{ ucfirst($team->color) }}</td>
            <td class="text-center">{{ $stats['team_points'] }}</td>
            <td class="text-center">{{ $stats['goals'] }}</td>
            <td class="text-center">{{ $stats['own_goals'] }}</td>
            <td class="text-center">{{ $stats['assists'] }}</td>
            <td class="text-center">{{ $stats['team_spirit_points'] }}</td>
            <td class="text-center">
                @if($stats['potm'])
                    <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td class="text-center">
                @if($stats['potmn'])
                    <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td class="text-center">{{ $stats['nomination_points'] }}</td>
            <td class="text-center">
                @if(Auth::user()->authorized() && $editable)
                    @if($player->hasStatsForGame($game->id))
                        <?php $stat = $player->stats()->where('team_id', '=', $team->id)->first(); ?>
                        <a title="edit" href="{{ URL::route('stats.edit',['statId'=>$stat->id]) }}">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    @else
                        <a title="create" href="{{ URL::route('stats.create',['teamId'=>$team->id, 'playerId'=>$player->id]) }}" >
                            <span class="glyphicon glyphicon-plus"></span>
                        </a>
                    @endif
                @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>