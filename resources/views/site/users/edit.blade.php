@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create User</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit User</h1>
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
                    $urlIds = ['userId'=>$user->id];
                    $method = 'put';
                    break;
            }
            ?>

            {!!  Form::model($user, [
                'url' => URL::route('users.'.$formURL, $urlIds),
                'class'=>'',
                'role'=>'form',
                'method'=> $method
            ]) !!}
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('name', 'Name', array('class'=>'control-label')) !!}
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('email', 'Email', array('class'=>'control-label')) !!}
                    {!! Form::text('email', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('password', 'Password', array('class'=>'control-label')) !!}
                    {!! Form::password('password', ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
                    <a href="{{ URL::to('/') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection