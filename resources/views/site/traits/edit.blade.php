@extends('layouts.dashboard')

@section('innerContent')
    @if($action == 'create')
        <h1 class="titles">Create Trait</h1>
    @elseif($action == 'edit')
        <h1 class="titles">Edit Trait</h1>
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
                    $urlIds = ['traitId'=>$trait->id];
                    $method = 'put';
                    break;
            }
            ?>

            {!!  Form::model($trait, [
                'url' => URL::route('traits.'.$formURL, $urlIds),
                'class'=>'',
                'role'=>'form',
                'method'=> $method
            ]) !!}
            <div class="row">
                <div class="form-group col-md-9">
                    {!! Form::label('name', 'Name', array('class'=>'control-label')) !!}
                    {!! Form::text(
                        'name',
                        null,
                        [
                            'class'=>'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-9">
                    {!! Form::label('description', 'Description', array('class'=>'control-label')) !!}
                    {!! Form::textarea(
                        'description',
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
                    <a href="{{ URL::route('traits.index') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection