@extends('layouts.master')

@section('head')
@endsection

@section('content')
    <div class="center-block">
        <div style="margin-top: 10px;">
            <div id="loginbox" style="margin-top:50px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops! </strong> There were some problems with your input. <br> <br>
                                <ul>

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="/login">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <a href="login/google" class="btn btn-primary">
                                        Login With Google
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection