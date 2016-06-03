@extends('layouts.dashboard')

@section('innerContent')
    @if(Auth::user()->authorized())
        <a href="{{ URL::route('players.create') }}" class="btn btn-success pull-right margin-top-25">Add Player</a>
    @endif
    <h1 class="titles">Players</h1>
    <hr/>
    <table id="players" class="table table-striped table-hover table-condensed">
        <thead class="">
        <tr>
            <th>Name</th>
            <th class="text-center">Position</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach($players as $player)
                <tr>
                    <td>
                        <a title="view" href="{{ URL::route('players.show',['playerId'=>$player->id]) }}">
                            {{ ucwords($player->formalName()) }}
                        </a>
                    </td>
                    <td class="text-center">{{ ucwords($player->position) }}</td>
                    <td class="text-center">{{ ucwords($player->status) }}
                    <td class="text-center">
                        @if(Auth::user()->authorized())
                            <a title="edit" href="{{ URL::route('players.edit',['playerId'=>$player->id]) }}">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>

                            <a title="delete" href="" data-toggle="modal" data-target="#deletePlayerModal" data-player="{{ $player->id }}">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <span style="display: table; margin: 0 auto;">
            {!! $players->render() !!}
        </span>
    </div>

        <!-- Delete Team Modal -->
    <div class="modal fade bs-example-modal-sm" id="deletePlayerModal" tabindex="-1" role="dialog" aria-labelledby="deletePlayerModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deletePlayerModalLabel">Delete Player</h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this player?
                </div>
                <div class="modal-footer">
                    <button id="yesBtn" type="button" class="btn btn-success" onclick="deletePlayer();">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#deletePlayerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var playerId = button.data('player');
            var modal = $(this);

            console.log(playerId);

            modal.find(".modal-footer #yesBtn").attr('onclick', "deletePlayer("+playerId+");");
        });
    });

    function deletePlayer(playerId)
    {
        var modal = $('#deletePlayerModal');

        $.ajax({
            url: '{{ URL::route('players.index') }}/'+playerId,
            type: 'delete',
            success: function(){
                window.location.reload();
            }
        });

        modal.modal('toggle');
    }
</script>
@endsection