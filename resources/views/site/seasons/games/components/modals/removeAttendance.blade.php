<!-- Delete Excuse Modal -->
<div class="modal fade bs-example-modal-sm" id="deleteAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteAttendanceModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAttendanceModalLabel">Delete Attendance</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this attendance?
            </div>
            <div class="modal-footer">
                <button id="yesBtn" type="button" class="btn btn-success" onclick="deleteAttendance();">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>