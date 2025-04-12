<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Grievance/Feedback/Help</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.support.desk.store') }}" method="post" class="add_form" button-click="btn_close" select-triger="status" content-refresh="example" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body"> 
                    <div class="form-group">
                        <label>Feedback Type</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="feedback_type" class="form-control select2">
                            <option selected disabled>Select Option</option>
                            <option value="{{Crypt::encrypt(1)}}">Help</option>
                            <option value="{{Crypt::encrypt(2)}}">Suggestion</option>
                            <option value="{{Crypt::encrypt(3)}}">Error</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description (Max. 500 Character)</label>
                        <textarea class="form-control" name="description" maxlength="500" placeholder="Enter Description" style="height: 100px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Attach Screenshot/File (Max. Size 5MB)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/jpeg,image/png,application/pdf">
                    </div>
                    <div class="form-group">
                        <label>Contact No.</label>
                        <input type="text" name="contact_no" class="form-control" placeholder="Contact No." maxlength="10" minlength="10" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>                 
                </div>

                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Submit</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

