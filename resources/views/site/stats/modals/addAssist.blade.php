<!-- Add Assist Modal -->
<div class="modal fade bs-example-modal-sm" id="addAssistModal" tabindex="-1" role="dialog" aria-labelledby="addAssistModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addAssistModalLabel">Add Goal</h4>
            </div>
            <div class="modal-body">
                <?php
                    $teamsList = $game->teams()->get()->lists('color','id')->all();

                    foreach($teamsList as $id=>$value) {
                        if($id != $team->id){
                            $colors[$id] = $value;
                        }
                    }

                    $playersPlaying = $game->players();
                ?>

                <label for="team_id" class="control-label">Opponent Team</label>
                <select id="team_id" class="form-control">
                    <option value=""></option>
                    @foreach($colors as $id=>$color)
                        <option value="{{ $id }}">{{ ucfirst($color) }}</option>
                    @endforeach
                </select>

                <label for="player_id" class="control-label">Assisted To</label>
                <select id="player_id" class="form-control">
                    <option value="" selected></option>
                    @foreach($playersPlaying as $playerPlaying)
                        @if($playerPlaying->id != $player->id)
                            <option value="{{ $playerPlaying->id }}" >{{ $playerPlaying->formalName() }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addGoal();">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>