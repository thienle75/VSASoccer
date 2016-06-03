<!-- Add Player Modal -->
<div class="modal fade" id="addPlayerModal" tabindex="-1" role="dialog" aria-labelledby="addPlayerModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addPlayerModalLabel">Add Player</h4>
            </div>
            <div class="modal-body">
                <?php
                    $teamsList = $game->teams()->get()->lists('color','id')->all();
                ?>

                <label for="team_id" class="control-label">Team</label>
                <select id="team_id" class="form-control">
                    @foreach($teamsList as $id=>$value)
                        <option value="{{ $id }}">{{ ucfirst($value) }} Team</option>
                    @endforeach
                </select>

                <label for="player_id" class="control-label">Player</label>
                <select id="player_id" class="form-control">
                    <option value="" selected></option>
                    @foreach($remainingPlayers as $player)
                        <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addPlayerToTeam();">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>