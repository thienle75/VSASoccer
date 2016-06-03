<!-- ADD RANKINGS LOGIC -->
<div class="row">
    @foreach($rankings as $stat=>$rank)
        <?php
            $newStat = "";
            if($stat == 'team_points') {
                $newStat = 'Team Pts';
            } else if($stat == 'nomination_points') {
                $newStat = 'Nomination Pts';
            } else if($stat == 'goals') {
                $newStat = 'Goals';
            } else if($stat == 'assists') {
                $newStat = 'Assists';
            } else if($stat == 'potm') {
                $newStat = 'POTM';
            } else if($stat == 'potmn') {
                $newStat = 'POTMN';
            }
        ?>
        <div class="col-lg-4">
            <center><h3>{{ $newStat }}</h3></center>
            <hr />

            <?php
            if($newStat == 'Spirit Pts') {
                $newStat = 'SP';
            } else if($newStat == 'Team Pts') {
                $newStat = 'TP';
            } else if($newStat == 'Nomination Pts') {
                $newStat = 'NP';
            }
            ?>

            <table class="table table-hover table-condensed">
                <tr>
                    <th class="text-center"> Rank</th>
                    <th> Name</th>
                    <th class="text-center"> {{ $newStat }} </th>
                </tr>

                @foreach($rank as $rank2)
                    <tr class="{{ $rank2['id'] == $player->id ? 'danger' : '' }}">
                        <td class="text-center"> {{ $rank2['rank'] + 1 }} </td>
                        <td> {{ $rank2['name'] }} </td>
                        <td class="text-center"> {{ $rank2[$stat] }} </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach
</div>