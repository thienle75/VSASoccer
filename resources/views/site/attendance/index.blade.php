@extends('layouts.dashboard')

@section('innerContent')

    <h1 class="titles"> Attendance {{' '.$gameDate}} </h1>
    <hr />
    {!! Form::open([
        'url' => URL::to('/attendance'),
        'method' => 'POST'
    ]) !!}

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('attendance', 'Attending', [
            'class'=>'control-label'
            ]) !!} <br />

            {!! Form::select('attending', ["yes" => 'Yes', "no" => 'No', 'excuse' => "Excuse"], [
            'class'=>'control-label'
            ]) !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('comments', 'Comments:', [
            'class'=>'control-label'
            ]) !!}

            {!! Form::textarea('comments', null, [
                'class'=>'form-control'
            ]) !!}
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