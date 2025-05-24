<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ @$id>0?'Edit':'Add' }} Class</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.class.update', Crypt::encrypt(@$id))}}" no-reset="true" class="add_form" button-click="btn_close" content-refresh="example">
                {{ csrf_field() }}
                <div class="box-body"> 
                    <div class="form-group">
                        {!! Form::label('name', 'Class Name : ', ['class'=>"col-sm-3 control-label"]) !!}            
                        
                            {!! Form::text('name', @$classes->name, ['class'=>"form-control",'placeholder'=>"Enter Class Name",'autocomplete'=>'off','maxlength'=>'20']) !!}
                        
                    </div>
                    <div class="form-group">
                        {!! Form::label('code', 'Class Code :', ['class'=>"col-sm-3 control-label"]) !!}
                        
                            {!! Form::text('code', @$classes->alias, ['class'=>"form-control",'placeholder'=>"Enter Class Code",'autocomplete'=>'off','maxlength'=>'5']) !!}
                        
                    </div> 
                    <div class="form-group">
                        {!! Form::label('shorting_id', 'Sorting Order No :', ['class'=>"col-sm-3 control-label"]) !!}
                        
                            {!! Form::text('shorting_id', @$classes->shorting_id, ['class'=>"form-control",'placeholder'=>"Enter Sorting Order No",'autocomplete'=>'off','maxlength'=>'2','onkeypress'=>'return event.charCode >= 48 && event.charCode <= 57']) !!}
                        
                    </div>                    
                </div>

                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">{{ @$id>0? 'Update' : 'Submit' }}</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

