@extends('layouts.dashboard')

@section('innerContent')
    @if(Auth::user()->authorized())
        <a href="{{ URL::route('awards.create') }}" class="btn btn-success pull-right margin-top-25">Add Award</a>
    @endif
    <h1 class="titles">Awards</h1>
    <hr/>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        @foreach($seasons as $season)
            <li role="presentation" class="{{ ($season->name=="Season 1" ? 'active' : '') }}">
                <a href="#season{{ $season->id }}" aria-controls="season{{ $season->id }}" role="tab" data-toggle="tab">{{ ucfirst($season->name) }}</a>
            </li>
        @endforeach
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        @foreach($seasons as $season)
            <div role="tabpanel" class="tab-pane {{ ($season->name=="Season 1" ? 'active' : '') }}" id="season{{ $season->id }}">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Type</th>
                            <th>Description</th>
                            <th>Player</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $awards = $season->awards()->getResults(); ?>
                    @foreach($awards as $award)
                        <?php
                            $awardType = $award->award_type()->getResults();
                            $player = $award->player()->getResults();
                        ?>
                        <tr>
                            <td><img src="{{ $award->getBadge() }}" width="32px" height="32px"/></td>
                            <td>{{ $awardType->name }}</td>
                            <td>{{ $awardType->description }}</td>
                            <td>{{ $player->formalName() }}</td>
                            <td class="text-center">
                                @if(Auth::user()->authorized())
                                    <a href="{{ URL::route('awards.edit',['awardId'=>$award->id]) }}"><span class="glyphicon glyphicon-edit"></span></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
