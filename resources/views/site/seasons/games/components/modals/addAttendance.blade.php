<!-- Add Excuse Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="addAttendanceModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addAttendanceModalLabel">Add Excused Player</h4>
            </div>
            <div class="modal-body">
                <label for="player_id" class="control-label">Player</label>
                <select id="player_id" class="form-control">
                    <option value="" selected></option>
                    @foreach($attendancePlayers as $player)
                        <option value="{{ $player->id }}">{{ $player->formalName() }}</option>
                    @endforeach
                </select>
                <label for="player_id" class="control-label">Attending</label>
                <select id="attending" class="form-control">
                    <option value="" selected></option>
                    <option value="yes" selected>Yes</option>
                    <option value="no" selected>No</option>
                    <option value="excuse" selected>Excuse</option>
                </select>
                <label for="description" class="control-label">Description</label>
                <textarea id="description" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addAttendanceToGame();">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>