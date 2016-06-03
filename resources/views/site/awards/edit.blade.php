@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Award</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Award</h1>
    @endif
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">@include('components.notifications')</div>
            </div>
            <?php
            $urlIds = [];

            switch($action){
                case 'create':
                    $formURL = 'store';
                    $urlIds = [];
                    $method = 'post';
                    break;
                case 'edit':
                    $formURL = 'update';
                    $urlIds = ['awardId'=>$award->id];
                    $method = 'put';
                    break;
            }
            ?>

            {!!  Form::model($award, [
                'url' => URL::route('awards.'.$formURL, $urlIds),
                'class'=>'',
                'role'=>'form',
                'method'=> $method
            ]) !!}
            <div class="row">
                <div class="form-group col-md-9">
                    {!! Form::label('award_type_id', 'Award Type', array('class'=>'control-label')) !!}
                    {!! Form::select(
                        'award_type_id',
                        $award_types,
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-9">
                    {!! Form::label('player_id', 'Player', array('class'=>'control-label')) !!}
                    {!! Form::select(
                        'player_id',
                        $players,
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-9">
                    {!! Form::label('season_id', 'Season', array('class'=>'control-label')) !!}
                    {!! Form::select(
                        'season_id',
                        $seasons,
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
                    <a href="{{ URL::route('awards.index') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection