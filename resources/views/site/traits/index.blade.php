@extends('layouts.dashboard')

@section('innerContent')
    @if(Auth::user()->authorized())
        <a href="{{ URL::route('traits.create') }}" class="btn btn-success pull-right margin-top-25">Add Trait</a>
    @endif
    <h1 class="titles">Traits</h1>
    <hr/>

    @if(count($traits)==0)
        There are currently no traits in the system.
    @else
        <table class="table table-striped table-hover table-condensed">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($traits as $trait)
                    <tr>
                        <td>{{ $trait->name }}</td>
                        <td>{{ $trait->description }}</td>
                        <td class="text-center">
                            @if(Auth::user()->authorized())
                                <a href="{{ URL::route('traits.edit',['traitId'=>$trait->id]) }}"><span class="glyphicon glyphicon-edit"></span></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
