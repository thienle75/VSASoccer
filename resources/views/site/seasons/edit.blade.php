@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Season</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Season</h1>
    @endif

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
                $urlIds = ['seasonId'=>$season->id];
                $method = 'put';
                break;
        }
    ?>

    {!!  Form::model($season, [
        'url' => URL::route('seasons.'.$formURL, $urlIds),
        'class'=>'',
        'role'=>'form',
        'method'=> $method
    ]) !!}
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('name', 'Name', array('class'=>'control-label')) !!}
            {!! Form::text('name', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. Season 1'
            ]) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('start_date', 'Start Date', array('class'=>'control-label')) !!}
            {!! Form::text('start_date', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. 2015-07-31, or July 31st, 2015'
            ]) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-9">
            {!! Form::label('end_date', 'End Date', array('class'=>'control-label')) !!}
            {!! Form::text('end_date', null, [
                'class'=>'form-control',
                'placeholder'=>'e.g. 2015-07-31, or July 31st, 2015'
            ]) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
            <a href="{{ URL::route('seasons.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection