@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Support/Help Desk Solution</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this , '{{ route('admin.support.solution.table') }}', 'result_table')">
                        <option value="{{Crypt::encrypt(0)}}">Pending</option>
                        <option value="{{Crypt::encrypt(1)}}">Resolved</option>
                    </select> 
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row" id="result_table">
                
            </div>
        </div>
    </div>
            
</section>
@endsection
@push('scripts')
<script>
    $("#status").trigger('change');
</script>
@endpush
