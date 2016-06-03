<!-- Remove Player Modal -->
<div class="modal fade bs-example-modal-sm" id="removePlayerModal" tabindex="-1" role="dialog" aria-labelledby="removePlayerModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="removePlayerModalLabel">Remove Player</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to this player?
            </div>
            <div class="modal-footer">
                <button id="yesBtn" type="button" class="btn btn-success" onclick="removePlayerFromTeam();">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>