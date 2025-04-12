<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Solution</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.support.solution.store', $rec_id) }}" method="post" class="add_form" button-click="btn_close" select-triger="status" content-refresh="example" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body"> 
                    <div class="form-group">
                        <label>Sulution (Max. 200 Character)</label>
                        <textarea class="form-control" name="solution" maxlength="200" placeholder="Enter Description" style="height: 150px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Attach Screenshot/Pdf File (Max. 5MB)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/jpeg,image/png,application/pdf">
                    </div>                
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Resolved</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

