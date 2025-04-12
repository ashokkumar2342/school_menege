@if ($status == 0)
    <div class="col-lg-12">
        <fieldset class="fieldset_border">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sr. No.</th>                
                            <th>Action</th>
                            <th>Grievance ID</th>
                            <th>School Code</th>
                            <th>Feedback Type</th>
                            <th>Description</th>
                            <th>Screenshot</th>
                            <th>Date Time</th>
                            <th>User Detail</th>
                            <th>Contact No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sr_no = 1;
                        @endphp
                        @foreach ($rs_records as $rs_val)
                            @php
                                if ($rs_val->feed_back_type == 1) {
                                    $feedback = 'Help';
                                }elseif ($rs_val->feed_back_type == 2) {
                                    $feedback = 'Suggestion';
                                }elseif ($rs_val->feed_back_type == 3) {
                                    $feedback = 'Error';
                                }else{
                                    $feedback = '';
                                }
                            @endphp
                            <tr class="bg-warning">
                                <td>{{ $sr_no++ }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="callPopupLarge(this, '{{ route('admin.support.solution.status',Crypt::encrypt($rs_val->id)) }}')" success-popup="true">Resolved/Solution</button>
                                </td>
                                <td>{{ $rs_val->id }}</td>
                                <td>{{ $rs_val->school_code }}</td>
                                <td>{{ $feedback }}</td>
                                <td>{{ $rs_val->description }}</td>
                                <td>
                                    @if($rs_val->screenshot!="")
                                        <a target="_blank" href="{{ route('admin.common.showimage', Crypt::encrypt('app/support/'.$rs_val->screenshot)) }}">View Attachment</a>
                                        
                                    @endif
                                </td>
                                <td>{{ $rs_val->date_time }}</td>
                                <td>{{ $rs_val->user_detail }}</td>
                                <td>{{ $rs_val->contact_nos }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
@else
    <div class="col-lg-12">
        <fieldset class="fieldset_border">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sr. No.</th>                
                            <th>Grievance ID</th>
                            <th>School Code</th>
                            <th>Feedback Type</th>
                            <th>Description</th>
                            <th>Screenshot</th>
                            <th>Date Time</th>
                            <th>User Detail</th>
                            <th>Contact No.</th>
                            <th>Solution</th>
                            <th>Solution Date</th>
                            <th>Solution File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sr_no = 1;
                        @endphp
                        @foreach ($rs_records as $rs_val)
                            @php
                                if ($rs_val->feed_back_type == 1) {
                                    $feedback = 'Help';
                                }elseif ($rs_val->feed_back_type == 2) {
                                    $feedback = 'Suggestion';
                                }elseif ($rs_val->feed_back_type == 3) {
                                    $feedback = 'Error';
                                }else{
                                    $feedback = '';
                                }
                            @endphp
                            <tr class="bg-success">
                                <td>{{ $sr_no++ }}</td>
                                <td>{{ $rs_val->id }}</td>
                                <td>{{ $rs_val->school_code }}</td>
                                <td>{{ $feedback }}</td>
                                <td>{{ $rs_val->description }}</td>
                                <td>
                                    @if($rs_val->screenshot!="")
                                        <a target="_blank" href="{{ route('admin.common.showimage', Crypt::encrypt('app/support/'.$rs_val->screenshot)) }}">View Attachment</a>
                                        
                                    @endif
                                </td>
                                <td>{{ $rs_val->date_time }}</td>
                                <td>{{ $rs_val->user_detail }}</td>
                                <td>{{ $rs_val->contact_nos }}</td>
                                <td>{{ $rs_val->solution }}</td>
                                <td>{{ $rs_val->solution_date }}</td>
                                <td>
                                    @if($rs_val->solution_file!="")
                                        <a target="_blank" href="{{ route('admin.common.showimage', Crypt::encrypt('app/support/'.$rs_val->solution_file)) }}">View Attachment</a>
                                        
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
@endif