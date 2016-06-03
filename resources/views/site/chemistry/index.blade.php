@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">Chemistry</h1>
    <hr/>
    <small>
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <td style="width: 100px;"></td>
                @foreach($chemistryData as $player1s)
                    <td style="width: 50px; height: 100px;">
                        <b>
                            <div style="transform: translate(5px, 30px) rotate(270deg); padding: 0px; height: 50px; width: 45px;">{{ $player1s['player1']->formalName() }}</div>
                        </b>
                    </td>
                @endforeach
            </tr>

            @foreach($chemistryData as $player1s)
                <tr>
                    <td><b>{{ $player1s['player1']->formalName() }}</b></td>
                    @foreach($player1s['players'] as $player2s)
                        @if($player1s['player1']->id == $player2s['player2']->id)
                            <td class="active">N/A</td>
                        @else
                            <?php
                                $class = 'active';
                                if($player2s['chemistry']>=75){
                                    $class = 'success';
                                }elseif($player2s['chemistry']>=50 && $player2s['chemistry']<75){
                                    $class = 'info';
                                }elseif($player2s['chemistry']>=25 && $player2s['chemistry']<50){
                                    $class = 'warning';
                                }elseif($player2s['chemistry']<25){
                                    $class = 'danger';
                                }
                            ?>

                            <td class="{{ $class }} text-center">{{ $player2s['chemistry'] }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </small>
@endsection