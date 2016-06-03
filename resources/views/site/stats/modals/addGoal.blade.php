<!-- Add Goal Modal -->
<div class="modal fade bs-example-modal-sm" id="addGoalModal" tabindex="-1" role="dialog" aria-labelledby="addGoalModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addGoalModalLabel">Add Goal</h4>
            </div>
            <div class="modal-body">
                <?php
                $teamsList = $game->teams()->get()->lists('color','id')->all();

                foreach($teamsList as $id=>$value) {
                    if($id != $team->id){
                        $colors[$id] = $value;
                    }
                }
                ?>

                <label for="team_id" class="control-label">Opponent Team</label>
                <select id="team_id" class="form-control">
                    <option value=""></option>
                    @foreach($colors as $id=>$color)
                        <option value="{{ $id }}">{{ ucfirst($color) }}</option>
                    @endforeach
                </select>

                <label for="own_goal" class="control-label">Own Goal</label>
                <select id="own_goal" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addGoal();">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>