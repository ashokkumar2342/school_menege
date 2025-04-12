<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-nowrap" width="5%">Sr. No.</th>                
                        <th class="text-nowrap" width="10%">Action</th>
                        <th class="text-nowrap" width="5%">Date Time</th>
                        <th class="text-nowrap" width="5%">School Code</th>
                        <th class="text-nowrap" width="10%">Controller Name</th>
                        <th class="text-nowrap" width="15%">Function Name</th>
                        <th class="text-nowrap" width="25%">Error Detail</th>
                        <th class="text-nowrap" width="15%">User Detail</th>
                        <th class="text-nowrap" width="5%">From Ip</th>
                        <th class="text-nowrap" width="5%">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sr_no = 1;
                        $row_class = 'bg-success';
                        $button_caption = "";
                        $route = "";
                        if($status == 0){
                            $row_class = 'bg-danger';
                            $button_caption = "Resolved";
                            $route = "admin.support.error.resolved";
                        }elseif($status == 1){
                            $row_class = 'bg-warning';
                            $button_caption = "Delete";
                            $route = "admin.support.error.delete";
                        }else{
                            $row_class = 'bg-success';
                        }
                    @endphp
                    @foreach ($rs_records as $rs_val)
                        <tr class="{{ $row_class }}">
                            <td>{{ $sr_no++ }}</td>
                            <td>
                                @if ($rs_val->status<=1)                                
                                    <button type="button" class="btn btn-sm btn-success" select-triger="status" success-popup="true" onclick="if (confirm('Are You Sure to Change The Status')){callAjax(this,'{{ route($route, Crypt::encrypt($rs_val->id)) }}') } else{console_Log('cancel') }">{{$button_caption}}</button>
                                @endif
                            </td>
                            <td>{{ $rs_val->date_time }}</td>
                            <td>{{ $rs_val->school_code }}</td>
                            <td>{{ $rs_val->controller_name }}</td>
                            <td>{{ $rs_val->method_function_name }}</td>
                            <td>{{ $rs_val->error_detail }}</td>
                            <td>{{ $rs_val->user_detail }}</td>
                            <td>{{ $rs_val->from_ip }}</td>
                            <td>{{ $rs_val->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>