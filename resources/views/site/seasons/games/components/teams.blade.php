@if($teamCount>0)
    @foreach($game->teams()->getResults() as $team)
        <?php
        $panelColor = 'default';
        $plCount = $team->players()->count();

        switch($team->color){
            case 'yellow':
                $panelColor = 'warning';
                break;
            case 'red':
                $panelColor = 'danger';
                break;
            case 'blue':
                $panelColor = 'info';
                break;
        }
        ?>

        <div class="panel panel-{{ $panelColor }}" id="teamPanel{{ $team->id }}">
            <div class="panel-heading">
                {{ ucfirst($team->color) }} Team
                <span class="pull-right glyphicon glyphicon-minus"
                      data-toggle="collapse"
                      data-parent="#teamPanel{{ $team->id }}"
                      href="#teamPanelBody{{ $team->id }}"
                      aria-expanded="true"
                      style="cursor: pointer;"
                      aria-controls="teamPanelBody{{ $team->id }}"></span>
            </div>
            <div class="panel-collapse collapse in" id="teamPanelBody{{ $team->id }}">
                <div class="panel-body" style="vertical-align: top; line-height: 80px;">
                    @if($plCount > 0)
                        @foreach($team->players()->getResults() as $player)
                            <div class="player">
                                <div class="player_img">
                                    <img src="{{ $player->getImage() }}" onclick="javascript: window.location = '{{ URL::route('players.show',['playerId'=>$player->id]) }}';">
                                </div>
                                @if($player->isCaptainForGame($game->id))
                                    <div class="player_captain_icon"></div>
                                @endif
                                @if(Auth::user()->authorized() && $editable)
                                    <div class="player_remove">
                                        <a href="" data-toggle="modal" data-target="#removePlayerModal" data-player="{{ $player->id }}" data-team="{{ $team->id }}">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </div>
                                @endif
                                <div class="player_name">
                                    {{ $player->formalName(true) }}
                                </div>
                                <div class="player_position">{{ $player->position }}</div>
                                <div class="player_tr">TR: {{ number_format((float)($player->teammate_rating * 100), 2, '.', '') . '%' }}</div>
                                <div class="player_pr">PR: {{ number_format((float)($player->player_rating * 100), 2, '.', '') . '%' }}</div>
                            </div>
                        @endforeach
                    @else
                        There are no players on this team
                    @endif
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-8">
                            @if((Auth::user()->authorized()) && $editable)
                                <a href="" class="btn btn-success" data-toggle="modal" data-target="#addPlayerModal" data-team="{{ $team->id }}">Add Player</a>
                                <a href="" class="btn btn-danger" data-toggle="modal" data-target="#deleteTeamModal" data-team="{{ $team->id }}">Delete Team</a>
                            @endif
                        </div>
                        <div class="col-lg-4 text-right">
                            <label>Team Rating: {{ $team->getTeamRating() }}%</label>
                            <label>Average Player Rating: {{ $team->getAveragePlayerRating() }}%</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    There are currently no teams selected for this game<br/><br/>
@endif
@if((Auth::user()->authorized() || Auth::user()->teamCaptain()) && $teamCount<3  && $editable)
    <a href="" class="btn btn-success"  data-toggle="modal" data-target="#addTeamModal">Add Team</a>
@endif
<a href="{{ URL::route('seasons.games.gameSheet',['seasonId'=>$season->id,'gameId'=>$game->id]) }}" class="btn btn-info">Game Sheet</a>
<br/>
<br/>