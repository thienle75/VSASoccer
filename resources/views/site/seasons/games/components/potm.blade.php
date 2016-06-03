<table id="POTM" class="table table-striped table-hover table-condensed">
    <thead class="">
    <tr>
        <th>Name</th>
        <th class="text-center">1st Place</th>
        <th class="text-center">2nd Place</th>
        <th class="text-center">3rd Place</th>
        <th class="text-center">4th Place</th>
        <th class="text-center">Total Points</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $gamePlayers = $game->players();
    ?>
    @if(count($gamePlayers)>0)
        @foreach($gamePlayers as $player)
            <tr>
                <td>{{ $player->formalName() }}</td>
                <td class="text-center">{{ $player->countPlayerNominationsForGame($game->id, 1) }}</td>
                <td class="text-center">{{ $player->countPlayerNominationsForGame($game->id, 2) }}</td>
                <td class="text-center">{{ $player->countPlayerNominationsForGame($game->id, 3) }}</td>
                <td class="text-center">{{ $player->countPlayerNominationsForGame($game->id, 4) }}</td>
                <td class="text-center">{{ $player->computeNominationPoints($game->id) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6">No players were in this game.</td>
        </tr>
    @endif
    </tbody>
</table>
<br/>
<br/>