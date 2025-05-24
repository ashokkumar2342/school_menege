@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Chapter</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                </ol>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{route('admin.chapter.store')}}" method="post" class="add_form" button-click="btn_show" no-reset="true" reset-input-text="chpater">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Class</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="class" class="form-control select2" id="select_class_box" data-table-new-without-pagination="true" onchange="callAjax(this,'{{route('admin.common.class.wise.subjects', 0)}}','subject_select_box');" button-click="btn_show">
                                <option selected value="{{Crypt::encrypt(0)}}">Select Class</option>  
                                @foreach ($classes as $val_rec)
                                    <option value="{{Crypt::encrypt($val_rec->opt_id)}}">{{$val_rec->opt_text}}</option>  
                                @endforeach 
                            </select> 
                        </div>
                    </div>
                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Subject</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="subject" class="form-control select2" id="subject_select_box" required>
                                 
                            </select> 
                        </div>
                    </div>
                </div>
                <button type="button" hidden class="hidden" id="btn_show" onclick="callAjax(this,'{{route('admin.chapter.table')}}'+'?class='+$('#select_class_box').val(),'result_table_id');" data-table-new-without-pagination="ajax_data_table"></button>
                <div class="row">
                    <div class="col-lg-12">                         
                        <div class="form-group">
                        <label>Chapter/Topic Name</label>
                            <textarea class="form-control" name="chpater" id="chpater"></textarea>
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card"> 
        <div class="card-body">

            <div class="row" id="result_table_id">
                
            </div>
            
        </div>
    </div>
</section>
@endsection


