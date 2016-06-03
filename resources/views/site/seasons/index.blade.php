@extends('layouts.dashboard')

@section('innerContent')

    @if(Auth::user()->authorized())
        <a href="{{ URL::route('seasons.create') }}" class="btn btn-success pull-right margin-top-25">Add Season</a>
    @endif
    <h1 class="titles">Seasons</h1>
    <hr/>

    @include('components.notifications')

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        @foreach($seasons as $season)
            <li role="presentation" class="{{ ($season->name=="Season 3" ? 'active' : '') }}">
                <a href="#season{{ $season->id }}" aria-controls="season{{ $season->id }}" role="tab" data-toggle="tab">{{ ucfirst($season->name) }}</a>
            </li>
        @endforeach
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        @foreach($seasons as $season)
            <div role="tabpanel" class="tab-pane {{ ($season->name=="Season 3" ? 'active' : '') }}" id="season{{ $season->id }}">
                <?php
                    $startDate = new Carbon\Carbon($season->start_date);
                    $endDate = new Carbon\Carbon($season->end_date);
                ?>
                <h3>Games ({{ $startDate->format('M jS, Y') }} - {{ $endDate->format('M jS, Y') }})</h3>
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th class="text-center">Number of Teams</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $games = $season->games()->orderBy('date', 'DESC')->getResults();
                    ?>
                    @if(count($games)==0)
                        <tr>
                            <td class="text-left" colspan="4">There are no games in this season yet.</td>
                        </tr>
                    @else
                        @foreach($games as $game)
                            <tr>
                                <td>{{ $game->id }}</td>
                                <td>{{ $game->date }}</td>
                                <td class="text-center">{{ $game->teams()->count() }}</td>
                                <td class="text-center">
                                    @if(Auth::user()->authorized())
                                        <a href="{{ URL::route('seasons.games.edit',['seasonId'=>$season->id, 'gameId'=>$game->id]) }}"><span class="glyphicon glyphicon-edit"></span></a>
                                    @endif
                                    <a href="{{ URL::route('seasons.games.show',['seasonId'=>$season->id, 'gameId'=>$game->id]) }}"><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                @if(Auth::user()->authorized())
                    <a href="{{ URL::route('seasons.games.create',['seasonId'=>$season->id]) }}" class="btn btn-success">Add Game</a>
                @endif
            </div>
        @endforeach
    </div>
    <br/>
@endsection
