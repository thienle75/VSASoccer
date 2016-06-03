@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">Team Builder</h1>
    <hr/>
    @include('site.teamBuilder.common.topMenu')

    <div class="row">
        <div class="col-lg-6">
            <h3>Player's Attending</h3>
        </div>
        <div class="col-lg-6">
            <h3>Teams</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <select id="players" class="form-control playerDropdown" multiple="multiple" style="height:248px;">
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-7">
                    <div class="form-group">
                        <label for="numberOfTeams">Number</label>
                        <select id="numberOfTeams" class="form-control">
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="captains">Captains</label>
                        <select id="captains" class="form-control playerDropdown" multiple="multiple" style="height:150px;">
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-5">
                    <input type="button" class="btn btn-primary " value="Auto Balance" onclick="balanceTeams();"/>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <h3>The Teams</h3>
        </div>
    </div>
    <div class="row">
        <div id="teams" class="col-lg-12">
            TBD
        </div>
    </div>

    <script type="text/javascript">
        function balanceTeams()
        {
            $.ajax({
                url: '{{ URL::to('/team-builder/balanceTeams') }}',
                type: 'POST',
                data: {
                    players: $('#players').val(),
                    captains: $('#captains').val(),
                    numberOfTeams: $('#numberOfTeams').val()
                },
                dataType: 'json',
                complete: function(jqXHR, status){
                    var response = jqXHR.responseJSON;

                    console.log(response);
                    if(response.status==0){
                        var resultDiv = $('<div id="result"></div>');
                        var teams = response.data;


                        teams.forEach(function(team, indx){
                            var teamList = $('<ol id="team'+indx+'" class="col-lg-4"></ol>');

                            team.forEach(function(player, indx){
                                var value = (((player.player_rating*3) + (player.teammate_rating*5) +  (player.player_class*10))/18)*100;

                                teamList.append('<li>'+player.first_name+' '+player.last_name+' ('+value.toFixed(2)+'%)</li>');
                            });
                            resultDiv.append(teamList);
                        });

                        $('#teams').html(resultDiv);
                    }else{
                        $('#teams').html(response.msg);
                    }
                }
            });
        }
    </script>
@endsection