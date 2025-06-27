@extends('admin.layout.base')
@section('body')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>PDF Upload</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                </ol>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{route('admin.pdf.store')}}" method="post" class="add_form" no-reset="true" enctype="multipart/form-data" reset-input-text="video" select-triger="chapter_select_box">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Class</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="class" class="form-control select2" id="select_class_box" data-table-new-without-pagination="true" onchange="callAjax(this,'{{route('admin.common.class.wise.subjects')}}','subject_select_box');" button-click="btn_show">
                                <option selected value="{{Crypt::encrypt(0)}}">Select Class</option>  
                                @foreach ($classes as $val_rec)
                                    <option value="{{Crypt::encrypt($val_rec->opt_id)}}">{{$val_rec->opt_text}}</option>  
                                @endforeach 
                            </select> 
                        </div>
                    </div>
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Subject</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="subject" class="form-control select2" id="subject_select_box" onchange="callAjax(this,'{{route('admin.common.subjects.wise.chapter')}}'+'?class_id='+$('#select_class_box').val()+'&subject_id='+$('#subject_select_box').val(),'chapter_select_box');">
                                 
                            </select> 
                        </div>
                    </div>
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Chapter/Topic</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="chpater" class="form-control select2" id="chapter_select_box" onchange="callAjax(this,'{{route('admin.pdf.table')}}','result_table_id');">
                                 
                            </select> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">                         
                        <div class="form-group">
                        <label>Choose PDF:</label>
                            <input type="file" name="pdf_file" id="pdf" class="form-control" accept="application/pdf">
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-6">                         
                        <div class="form-group">
                        <label>Title</label>
                        <span class="fa fa-asterisk"></span>
                            <textarea name="title" class="form-control" required placeholder="Please Enter Title" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">                         
                        <div class="form-group">
                        <label>Description</label>
                        <span class="fa fa-asterisk"></span>
                            <textarea name="description" class="form-control" required placeholder="Please Enter Description" maxlength="500"></textarea>
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <input type="submit" class="btn btn-primary" value="Upload">
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
@push('scripts')
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
@endpush



