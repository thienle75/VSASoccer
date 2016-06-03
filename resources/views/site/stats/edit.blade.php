@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Stats</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Stats</h1>
    @endif
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">@include('components.notifications')</div>
            </div>
            <?php
            $urlIds = [];
            $colors = [];

            switch($action){
                case 'create':
                    $formURL = 'store';
                    $urlIds = ['teamId'=>$team->id, 'playerId'=>$player->id];
                    $method = 'post';
                    break;
                case 'edit':
                    $formURL = 'update';
                    $urlIds = ['statId'=>$stat->id];
                    $method = 'put';
                    break;
            }
            ?>

            {!!  Form::model($stat, [
                'url' => URL::route('stats.'.$formURL, $urlIds),
                'class'=>'',
                'role'=>'form',
                'method'=> $method
            ]) !!}
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Game:</label> {{ $game->date }}<br/>
                    <label>Player:</label> {{ $player->formalName() }}<br/>
                    <label>Position:</label> {{ $player->position }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('team_points', 'Team Points', array('class'=>'control-label')) !!}
                    {!! Form::text('team_points', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('player_of_the_match', 'Player of the Match', array('class'=>'control-label')) !!}<br/>
                    {!! Form::select(
                        'player_of_the_match',
                        [''=>'',1=>'Yes',0=>'No'],
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('player_of_the_match_nomination', 'Player of the Match Nomination', array('class'=>'control-label')) !!}<br/>
                    {!! Form::select(
                        'player_of_the_match_nomination',
                        [''=>'',1=>'Yes',0=>'No'],
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('player_of_the_match_nomination_points', 'Player of the Match Nomination Points', array('class'=>'control-label')) !!}
                    {!! Form::text('player_of_the_match_nomination_points', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('team_spirit_points', 'Team Spirit Points', array('class'=>'control-label')) !!}
                    {!! Form::text('team_spirit_points', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('is_captain', 'Team Captain', array('class'=>'control-label')) !!}<br/>
                    {!! Form::select(
                        'is_captain',
                        [''=>'',1=>'Yes',0=>'No'],
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
                    <a href="{{ URL::route('seasons.games.show',['seasonId'=>$season->id,'gameId'=>$game->id]) }}" class="btn btn-danger">Back</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        @if($stat)
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Goals <a href="" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addGoalModal" data-stat="{{ $stat->id }}"><span class="glyphicon glyphicon-plus"></span></a></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <table id="playerGoals" class="table table-striped table-hover table-condensed">
                            <thead class="">
                            <tr>
                                <th>Opponent Team</th>
                                <th class="text-center">Own Goal</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($stat->goals()->count()>0)
                                @foreach($stat->goals()->getResults() as $goal)
                                    <?php $opponentTeam = $goal->oppTeam()->getResults(); ?>
                                    <tr>
                                        <td>{{ $opponentTeam ? ucfirst($opponentTeam->color) : 'N/A' }}</td>
                                        <td class="text-center">{{ $goal->own_goal ? 'Yes' : 'No' }}</td>
                                        <td class="text-center">
                                            @if(Auth::user()->authorized())
                                                <a title="delete" href="javascript: deleteGoal({{ $stat->id }}, {{ $goal->id }});">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">This person didn't score any goals in this game.</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3">

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Assists <a href="" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addAssistModal" data-stat="{{ $stat->id }}"><span class="glyphicon glyphicon-plus"></span></a></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <table id="playerGoals" class="table table-striped table-hover table-condensed">
                            <thead class="">
                            <tr>
                                <th>Opponent Team</th>
                                <th>Assist To</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($stat->assists()->count()>0)
                                @foreach($stat->assists()->getResults() as $assist)
                                    <?php
                                        $opponentTeam = $assist->oppTeam()->getResults();
                                        $assistedPlayer = $assist->player()->getResults();
                                    ?>
                                    <tr>
                                        <td>{{ $opponentTeam ? ucfirst($opponentTeam->color) : 'N/A' }}</td>
                                        <td>{{ $assistedPlayer ? $assistedPlayer->formalName() : 'N/A' }}</td>
                                        <td class="text-center">
                                            @if(Auth::user()->authorized())
                                                <a title="delete" href="javascript: deleteAssist({{ $stat->id }}, {{ $assist->id }});">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">This person didn't have any assists in this game.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            //This function preselects the Team dropdown from the Add Player Modal
            $('#addGoalModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var statId = button.data('stat');
                var modal = $(this);

                modal.find(".modal-footer .btn-success").attr('onclick', "addGoal("+statId+")");
            });

            //This function preselects the Team dropdown from the Add Player Modal
            $('#addAssistModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var statId = button.data('stat');
                var modal = $(this);

                modal.find(".modal-footer .btn-success").attr('onclick', "addAssist("+statId+")");
            });
        });


        function addGoal(statId)
        {
            var modal = $('#addGoalModal');
            var teamId = modal.find('.modal-body #team_id').val();
            var ownGoal = modal.find('.modal-body #own_goal').val();

            $.ajax({
                url: '{{ URL::route('stats.index') }}/'+statId+'/add-goal',
                type: 'post',
                data: {teamId: teamId, ownGoal: ownGoal},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        function deleteGoal(statId, goalId)
        {
            $.ajax({
                url: '{{ URL::route('stats.index') }}/'+statId+'/delete-goal',
                type: 'post',
                data: {goalId: goalId},
                success: function(){
                    window.location.reload();
                }
            });
        }

        function addAssist(statId)
        {
            var modal = $('#addAssistModal');
            var teamId = modal.find('.modal-body #team_id').val();
            var playerId = modal.find('.modal-body #player_id').val();

            $.ajax({
                url: '{{ URL::route('stats.index') }}/'+statId+'/add-assist',
                type: 'post',
                data: {teamId: teamId, playerId: playerId},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        function deleteAssist(statId, assistId)
        {
            $.ajax({
                url: '{{ URL::route('stats.index') }}/'+statId+'/delete-assist',
                type: 'post',
                data: {assistId: assistId},
                success: function(){
                    window.location.reload();
                }
            });
        }
    </script>

@endsection

@section('modals')
    <!-- Modals -->
    @include('site.stats.modals.addGoal')
    @include('site.stats.modals.addAssist')
@endsection