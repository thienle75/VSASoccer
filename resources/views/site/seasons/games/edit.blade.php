@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Game</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Game</h1>
    @endif

    <div class="row">
        <div class="col-md-12">@include('components.notifications')</div>
    </div>
    <?php
        switch($action){
            case 'create':
                $formURL = 'store';
                $urlIds = ['seasonId'=>$season->id];
                $method = 'post';
                break;
            case 'edit':
                $formURL = 'update';
                $urlIds = ['seasonId'=>$season->id, 'gameId' => $game->id];
                $method = 'put';
                break;
        }
    ?>

    {!!  Form::model($game, [
        'url' => URL::route('seasons.games.'.$formURL, $urlIds),
        'class'=>'',
        'role'=>'form',
        'method'=> $method
    ]) !!}
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('season_id', 'Season', array('class'=>'control-label')) !!}
            {!! Form::select(
                'season_id',
                $seasons,
                $season->id,
                [
                    'class'=>'form-control'
                ]
            ) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            {!! Form::label('gamedate', 'Date', array('class'=>'control-label')) !!}

            <div class="input-daterange input-group" id="date">
                <input type="text" class="input-sm form-control" name="gamedate" id="gamedate"
                       data-date-format="YYYY-MM-DD"/>
            </div>
        </div>
    </div>
<br />

    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
            <a href="{{ URL::route('seasons.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection