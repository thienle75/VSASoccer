@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Player</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Player</h1>
    @endif

    <div class="row">
        <div class="col-md-12">@include('components.notifications')</div>
    </div>
    <?php
    switch($action) {
        case 'create':
            $formURL = 'store';
            $urlIds = [];
            $method = 'post';
            break;
        case 'edit':
            $formURL = 'update';
            $urlIds = $player->id;
            $method = 'put';
            break;
    }
    ?>

    {!!  Form::model($player, [
        'url' => URL::route('players.'.$formURL, $urlIds),
        'class'=>'',
        'role'=>'form',
        'method'=> $method
    ]) !!}

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('first_name', 'First Name', array('class'=>'control-label')) !!}
            {!! Form::text('first_name', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. John'
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('last_name', 'Last Name', array('class'=>'control-label')) !!}
            {!! Form::text('last_name', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. Doe'
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('position', 'Position', array('class'=>'control-label')) !!}
            {!! Form::text('position', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. St.'
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('status', 'Status', array('class'=>'control-label')) !!}
            {!! Form::text('status', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. Active'
            ]) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('traits', 'Traits', array('class'=>'control-label')) !!} <br/>
            {!! Form::select(
                'traits',
                $traits,
                $playerTraits,
                [
                    'id' => 'traits',
                    'name' => 'traits[]',
                    'multiple' => 'multiple',
                    'class' => 'multiselect'
                ]
            ) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection

