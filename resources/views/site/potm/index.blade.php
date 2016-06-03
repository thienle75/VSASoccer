@extends('layouts.dashboard')

@section('innerContent')

    <h1 class="titles"> POTM for {{' '.$gameDate}} </h1>
    <hr />
    @include('components.notifications')
    {!! Form::open([
        'url' => URL::to('/potm'),
        'method' => 'POST'
    ]) !!}
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('player1', '1st Place', [
            'class'=>'control-label'
            ]) !!} <br />

            <select id="player1" name="player1" class="form-control">
                <option value="" selected></option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('player2', '2nd Place', [
            'class'=>'control-label'
            ]) !!} <br />

            <select id="player2" name="player2" class="form-control">
                <option value="" selected></option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('player3', '3rd Place', [
            'class'=>'control-label'
            ]) !!} <br />

            <select id="player3" name="player3" class="form-control">
                <option value="" selected></option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('player4', '4th Place', [
            'class'=>'control-label'
            ]) !!} <br />

            <select id="player4" name="player4" class="form-control">
                <option value="" selected></option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::submit('Submit', ['class'=>'btn btn-success']) !!}
        </div>
    </div>

    {!! Form::hidden('game_id', $gameId) !!}

    {!! Form::close() !!}
@endsection