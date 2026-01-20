@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>School Activity Report</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form  class="add_form" method="post" action="{{ route('admin.school.activity.show') }}" success-content-id="result_div_id" no-reset="true" data-table-new-without-pagination="example1"> 
            {{ csrf_field() }} 
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>School Detail</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="school_detail" class="form-control select2" select2="true">
                                <option value="{{ Crypt::encrypt(0) }}" disabled selected>Select Report</option>
                                @foreach ($rs_records as $rs_val)
                                    <option value="{{ Crypt::encrypt($rs_val->url) }}">{{ $rs_val->code }} - {{ $rs_val->name }}</option>
                                @endforeach
                            </select>
                        </div>                               
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Report Type</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="report_type" class="form-control select2" select2="true">
                                <option value="{{ Crypt::encrypt(0) }}" disabled selected>Select Report</option>
                                <option value="{{ Crypt::encrypt(1) }}">Login Detail</option>
                                <option value="{{ Crypt::encrypt(2) }}">Log Update Query</option>
                            </select>
                        </div>                               
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>From Date</label>
                        <input type="text" name="from_date" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" minlength="10" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45) || (event.charCode == 47)' value="{{date('d-m-Y')}}"> 
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>To Date</label>
                        <input type="text" name="to_date" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" minlength="10" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45) || (event.charCode == 47)' value="{{date('d-m-Y')}}">
                    </div>
                    <div class="col-lg-4 form-group" style="padding-top: 30px;">
                        <input  type="submit" class="btn btn-primary form-control" id="btn_show" value="Show">
                    </div>
                </div>
            </form> 
        </div>
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row" id="result_div_id"> 

            </div>    
        </div>
    </div> 
</section>
@endsection