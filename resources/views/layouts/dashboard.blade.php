@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div id="sidebar" class="col-md-2">
                    <img id="logo" src="/assets/engage_logo_v1.4_rgb_256.png" width="155px"/>
                    @include('components.sidebar')
                    @include('components.theme')
                </div>
                <div id="content" class="col-md-10">
                    @yield('innerContent')
                </div>
            </div>
        </div>
    </div>
@endsection