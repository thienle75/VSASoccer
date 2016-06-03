<table id="Attendance" class="table table-bordered table-hover table-condensed">
    <thead class="">
    <tr>
        <th>Name</th>
        <th class="text-center">Position</th>
        <th class="text-center">TR</th>
        <th class="text-center">PR</th>
        <th>Traits</th>
        <th class="text-center">Attending</th>
        <th>Description</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @if($game->attendance()->count()>0)
        <?php
            $attendanceRecords = $game->attendance()
                ->select('attendance.*')
                ->join('players','attendance.player_id','=','players.id')
                ->orderBy('attendance.attending', 'asc')
                ->orderBy('players.first_name','asc')
                ->orderBy('players.last_name','asc')
                ->getResults();
        ?>
        @foreach($attendanceRecords as $attendance)
            <?php $player = $attendance->player()->first(); ?>
            <tr class="{{ in_array($attendance->attending,["no","excuse"]) ? "active" : "" }}">
                <td style="white-space: nowrap;">{{ $player->formalName() }}</td>
                <td class="text-center">{{ $player->position }}</td>
                <td class="text-center">{{ number_format($player->teammate_rating*100,2) }}%</td>
                <td class="text-center">{{ number_format($player->player_rating*100,2) }}%</td>
                <td>{{ implode(', ',$player->traits()->get()->lists('name')->all()) }}</td>
                <td class="text-center">{{ $attendance->attending }}</td>
                <td>{{ $attendance->description }}</td>
                <td class="text-center">
                    @if(Auth::user()->authorized() && $editable)
                        <a href="" data-toggle="modal" data-target="#deleteAttendanceModal" data-attendance="{{ $attendance->id }}">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3">No players have answered the attendance form.</td>
        </tr>
    @endif
    </tbody>
</table>
@if(Auth::user()->authorized() && $editable)
    <a href="" class="btn btn-success" data-toggle="modal" data-target="#addAttendanceModal" data-game="{{ $game->id }}">Add Attendance</a>
@endif
<br/>
<br/>