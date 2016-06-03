<!-- Add Team Modal -->
<div class="modal fade bs-example-modal-sm" id="addTeamModal" tabindex="-1" role="dialog" aria-labelledby="addTeamModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addTeamModalLabel">Add Team</h4>
            </div>
            <div class="modal-body">
                <?php
                    $teamsList = $game->teams()->get()->lists('color','id')->all();

                    $colors = [
                        'yellow'=>true,
                        'blue'=>true,
                        'red'=>true
                    ];

                    foreach($teamsList as $id=>$value) {
                        if($colors[$value]){
                            $colors[$value] = false;
                        }
                    }
                ?>

                <label for="team_color" class="control-label">Team Color</label>
                <select id="team_color" class="form-control">
                    <option value=""></option>
                    @foreach($colors as $color=>$show)
                        @if($show)
                            <option value="{{ $color }}">{{ ucfirst($color) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addTeamToGame();">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
