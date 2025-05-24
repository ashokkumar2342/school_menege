<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ @$id>0?'Edit':'Add' }} Subject</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.subjectType.update',Crypt::encrypt(@$id)) }}" method="post" class="add_form" button-click="btn_close" content-refresh="example">
                {{ csrf_field() }}
                <div class="box-body"> 
                    <div class="form-group">
                        <label>Subject Name</label>           

                        {!! Form::text('name',@$subjects->name, ['class'=>"form-control",'placeholder'=>"Subject Name",'autocomplete'=>'off','maxlength'=>'50',]) !!}

                    </div>
                    <div class="form-group">
                        <label>Subject Code</label>           

                        {!! Form::text('code', @$subjects->code, ['class'=>"form-control",'placeholder'=>"Subject Code",'autocomplete'=>'off','maxlength'=>'10',]) !!}

                    </div>
                    <div class="form-group">
                        <label>Sorting Order No.</label>            

                        {!! Form::text('sorting_order_id', @$subjects->sorting_order_id,  ['class'=>"form-control",'placeholder'=>"Sorting Order No",'autocomplete'=>'off','maxlength'=>'2','onkeypress'=>'return event.charCode >= 48 && event.charCode <= 57']) !!}

                    </div>                    
                </div>

                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">{{ @$id>0?'Update':'Submit' }}</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

