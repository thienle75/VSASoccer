<!-- Delete Team Modal -->
<div class="modal fade bs-example-modal-sm" id="deleteTeamModal" tabindex="-1" role="dialog" aria-labelledby="deleteTeamModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addTeamModalLabel">Delete Team</h4>
            </div>
            <div class="modal-body">
               Are you sure you want to delete this team?
            </div>
            <div class="modal-footer">
                <button id="yesBtn" type="button" class="btn btn-success" onclick="deleteTeam();">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>