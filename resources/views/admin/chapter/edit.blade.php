<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.chapter.update', Crypt::encrypt($rec_id)) }}" method="post" class="add_form" button-click="btn_close,btn_show">
                {{ csrf_field() }}
                
                <div class="form-group">
                <label>Chapter/Topic Name</label>
                    <textarea class="form-control" name="chpater" id="chpater">{{@$rs_result[0]->chapter_topic_name}}</textarea>
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Update</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
