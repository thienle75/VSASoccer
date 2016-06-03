@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Video Link</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Video Link</h1>
    @endif
    <hr />
    <div class="row">
        <div class="col-md-12">@include('components.notifications')</div>
    </div>
    <?php
    switch($action){
        case 'create':
            $formURL = 'store';
            $urlIds = [];
            $method = 'post';
            break;
        case 'edit':
            $formURL = 'update';
            $urlIds = ['videoId' => $video->id];
            $method = 'put';
            break;
    }
    ?>

    {!!  Form::model($video, [
        'url' => URL::route('footage.'.$formURL, $urlIds),
        'class'=>'',
        'role'=>'form',
        'method'=> $method
    ]) !!}

    <div class="row">
        <div class="form-group col-md-6">
            {!! Form::label('url', 'Youtube Video ID', array('class'=>'control-label')) !!}
            {!! Form::text('url', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. ywjAJOd1bSf'
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            {!! Form::label('name', 'Description', array('class'=>'control-label')) !!}
            {!! Form::text('name', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. 720p Camera 1'
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            {!! Form::label('game_id', 'Game', array('class'=>'control-label')) !!}
            {!! Form::select('game_id', $games, null,
                [
                    'class'=>'form-control'
                ]
            ) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
            <a href="{{ URL::route('footage.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

