@extends('layouts.dashboard')

@section('innerContent')
    <style>
        .name {
            color: #FFFFFF;
            text-align: right;
            font-size: 28px;
            margin-bottom: -17px;
        }

        .posStatus {
            color: #FFFFFF;
            text-align: right;
            margin-bottom: 0px;
        }

        #player {
            background-color: #f04e5e;
            padding: 10px;
            z-index: 1;
        }

        .squareFrame img {
            max-width: 200px;
            max-height: 200px;
            margin-top: 20px;
            margin-left: 20px;
            margin-bottom: 20px;
            padding: 1px;
            border: 5px solid #ebebeb;
            background-color: #ebebeb;
            z-index: 10;
        }

        #playerHeader{
            margin-top: 15px;
        }

    </style>
    <div id="playerHeader" class="row">
        <div class="squareFrame pull-left">
            <!--TODO: Add code for players player_avatars-->
            <img src={{ $player->getImage() }}>
        </div>
        <div id="player">
            <h2 class="name">{{ $player->formalName() }}</h2>
            <h3 class="posStatus">{{ $player->position }}<br/>&nbsp;</h3>
            <h4 class="posStatus">{{ $traits }}</h4>
        </div>
        <div id="awardsSummary" class="pull-right">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        $groupedAwards = [];
                        foreach($awards as $award){
                            $groupedAwards[$award->award_type_id][] = [
                                    'name' => $award->award_type()->getResults()->name,
                                    'badge' => $award->getBadge()
                            ];
                        }
                    ?>

                    @foreach($groupedAwards as $awardTypeId=>$awardBadges)
                        {{ count($awardBadges) }} x <img src="{{ $awardBadges[0]['badge'] }}" title="{{ $awardBadges[0]['name'] }}" width="24px" height="24px" title=""/><br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a>
                </li>
                <li role="presentation">
                    <a href="#rankings" aria-controls="rankings" role="tab" data-toggle="tab">Rankings</a>
                </li>
                <li role="presentation">
                    <a href="#performance" aria-controls="performance" role="tab" data-toggle="tab">Performance</a>
                </li>
                <li role="presentation">
                    <a href="#awards" aria-controls="awards" role="tab" data-toggle="tab">Awards</a>
                </li>
                <li role="presentation">
                    <a href="#excuses" aria-controls="excuses" role="tab" data-toggle="tab">Excuses</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <!-- STATS TAB -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="stats">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
                            <table id='stats' class="table table-striped table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th style="text-align: center">Game</th>

                                    <th style="text-align: center">TP</th>

                                    <th style="text-align: center">POTM</th>

                                    <th style="text-align: center">POTMN</th>

                                    <th style="text-align: center">Nomination Points</th>

                                    <th style="text-align: center">Spirit Points</th>

                                    <th style="text-align: center">G</th>

                                    <th style="text-align: center">OG</th>

                                    <th style="text-align: center">A</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $calculatedStats as $gameId => $data)
                                    <?php
                                    $game = $data['game'];
                                    $season = $game->season()->getResults();
                                    $calculatedStat = $data['stats'];
                                    ?>

                                    <tr style="text-align: center">
                                        <td><a href="{{ URL::route('seasons.games.show',['seasonId'=>$season->id, 'gameId'=>$gameId]) }}"> {{ $game->date }}</a> </td>
                                        <td>{{ $calculatedStat['team_points'] }}</td>
                                        <td>{{ $calculatedStat['potm'] }}</td>
                                        <td>{{ $calculatedStat['potmn'] }}</td>
                                        <td>{{ $calculatedStat['nomination_points'] }}</td>
                                        <td>{{ $calculatedStat['team_spirit_points'] }}</td>
                                        <td>{{ $calculatedStat['goals'] }}</td>
                                        <td>{{ $calculatedStat['own_goals'] }}</td>
                                        <td>{{ $calculatedStat['assists'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>


                <!-- RANKINGS TAB -->
                <div role="tabpanel" class="tab-pane" id="rankings">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
                            @include('site.players.components.rankings')
                        </div>
                    </div>
                </div>

                <!-- PERFORMANCE TAB -->
                <div role="tabpanel" class="tab-pane" id="performance">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
                            @include('site.players.components.performance')
                        </div>
                    </div>
                </div>

                <!-- AWARDS TAB -->
                <div role="tabpanel" class="tab-pane" id="awards">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
                            @include('site.players.components.awards')
                        </div>
                    </div>
                </div>

                <!-- EXCUSES TAB -->
                <div role="tabpanel" class="tab-pane" id="excuses">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
                            @include('site.players.components.excuses')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection