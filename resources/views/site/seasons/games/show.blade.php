@extends('layouts.dashboard')

@section('innerContent')
    <h1 class="titles">{{ $game->date }}</h1>
    <hr/>

    @include('components.notifications')

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#teams" aria-controls="teams" role="tab" data-toggle="tab">Teams</a>
        </li>
        <li role="presentation">
            <a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a>
        </li>
        <li role="presentation">
            <a href="#attendance" aria-controls="attendance" role="tab" data-toggle="tab">Attendance</a>
        </li>
        <li role="presentation">
            <a href="#potm" aria-controls="potm" role="tab" data-toggle="tab">POTM Votes</a>
        </li>
        <li role="presentation">
            <a href="#videos" aria-controls="videos" role="tab" data-toggle="tab">Footage</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="teams">
            <br/>
            @include('site.seasons.games.components.teams')
        </div>
        <div role="tabpanel" class="tab-pane" id="stats">
            <br/>
            @include('site.seasons.games.components.stats')
        </div>
        <div role="tabpanel" class="tab-pane" id="attendance">
            <br/>
            @include('site.seasons.games.components.attendance')
        </div>
        <div role="tabpanel" class="tab-pane" id="potm">
            <br/>
            @include('site.seasons.games.components.potm')
        </div>
        <div role="tabpanel" class="tab-pane" id="videos">
            <br/>
            @include('site.seasons.games.components.videos')
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            //This function preselects the Team dropdown from the Add Player Modal
            $('#addPlayerModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var teamId = button.data('team');
                var modal = $(this);

                modal.find(".modal-body #team_id option[value='"+teamId+"']").prop('selected', true);
            });

            $('#deleteTeamModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var teamId = button.data('team');
                var modal = $(this);

                modal.find(".modal-footer #yesBtn").attr('onclick', "deleteTeam("+teamId+");");
            });

            $('#removePlayerModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var teamId = button.data('team');
                var palyerId = button.data('player');
                var modal = $(this);

                modal.find(".modal-footer #yesBtn").attr('onclick', "removePlayerFromTeam("+teamId+","+palyerId+");");
            });

            $('#addAttendanceModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var gameId = button.data('game');
                var modal = $(this);

                modal.find(".modal-footer #yesBtn").attr('onclick', "addAttendanceToGame("+gameId+");");
            });

            $('#deleteAttendanceModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var attendanceId = button.data('attendance');
                var modal = $(this);

                modal.find(".modal-footer #yesBtn").attr('onclick', "deleteAttendance("+attendanceId+");");
            });

            $('.btnModal').click(function () {
                document.getElementById("youtubeVideo").src = "https://www.youtube.com/embed/" + $(this).data('atr');
            });
        });


        /**
         * This function does the ajax call to add a player to a team
         */
        function addPlayerToTeam()
        {
            var modal = $('#addPlayerModal');
            var teamId = modal.find('.modal-body #team_id').val();
            var playerId = modal.find('.modal-body #player_id').val();


            $.ajax({
                url: '{{ URL::route('seasons.games.teams.index',['seasonId' => $season->id,'gameId' => $game->id]) }}/'+teamId+'/add-player',
                type: 'post',
                data: {'playerId': playerId},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        /**
         * This function does the ajax call to add a player to a team
         */
        function removePlayerFromTeam(teamId, playerId)
        {
            var modal = $('#removePlayerModal');

            $.ajax({
                url: '{{ URL::route('seasons.games.teams.index',['seasonId' => $season->id,'gameId' => $game->id]) }}/'+teamId+'/remove-player',
                type: 'post',
                data: {'playerId': playerId},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        /**
         * This function does the ajax call to add a team to the game
         */
        function addTeamToGame()
        {
            var modal = $('#addTeamModal');
            var teamColor = modal.find('.modal-body #team_color').val();

            $.ajax({
                url: '{{ URL::route('seasons.games.teams.store',['seasonId' => $season->id,'gameId' => $game->id]) }}',
                type: 'post',
                data: {'teamColor': teamColor},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        /**
         * This function does the ajax call to delete a team from the game
         */
        function deleteTeam(teamId)
        {
            var modal = $('#deleteTeamModal');

            $.ajax({
                url: '{{ URL::route('seasons.games.teams.destroy',['seasonId' => $season->id,'gameId' => $game->id]) }}/'+teamId,
                type: 'delete',
                success: function(jqXHR){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        /**
         * This function does the ajax call to add an excuse to the game
         */
        function addAttendanceToGame()
        {
            var modal = $('#addAttendanceModal');
            var playerId = modal.find('.modal-body #player_id').val();
            var attending = modal.find('.modal-body #attending').val();
            var description = modal.find('.modal-body #description').val();

            $.ajax({
                url: '{{ URL::route('seasons.games.addAttendance',['seasonId' => $season->id,'gameId' => $game->id]) }}',
                type: 'post',
                data: {playerId: playerId, attending: attending, description: description},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }

        /**
         * This function does the ajax call to delete an Attendance to the game
         */
        function deleteAttendance(attendanceId)
        {
            var modal = $('#deleteAttendanceModal');

            $.ajax({
                url: '{{ URL::route('seasons.games.deleteAttendance',['seasonId' => $season->id,'gameId' => $game->id]) }}',
                type: 'post',
                data: {'attendanceId': attendanceId},
                success: function(){
                    window.location.reload();
                }
            });

            modal.modal('toggle');
        }
    </script>
@endsection

@section('modals')
    <!-- Modals -->
    @include('site.seasons.games.components.modals.addPlayer')
    @include('site.seasons.games.components.modals.removePlayer')
    @include('site.seasons.games.components.modals.addTeam')
    @include('site.seasons.games.components.modals.removeTeam')
    @include('site.seasons.games.components.modals.addAttendance')
    @include('site.seasons.games.components.modals.removeAttendance')
    @include('site.footage.modals.showVideo')
@endsection