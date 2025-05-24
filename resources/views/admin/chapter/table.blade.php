<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Subject</th>
                        <th>Chapter/Topic</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $srno = 1;
                    @endphp
                    @foreach ($rs_result as $rs_value)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_value->name }}</td>
                            <td>{{ $rs_value->chapter_topic_name }}</td>
                            
                            <td>
                               
                                <button type="button" title="Edit" class="btn btn-info btn-sm" text-editor="summernote" onclick="callPopupLarge(this,'{{ route('admin.chapter.edit',Crypt::encrypt($rs_value->id))}}')"><i class="fa fa-edit"></i> Edit</button>
                                
                                {{-- <button type="button" class="btn btn-danger btn-xs" success-popup="true" button-click="btn_show" title="Delete" onclick="if (confirm('Are you Sure delete file')){callAjax(this,'{{ route('admin.daily.homework.file.delete',Crypt::encrypt($rs_value->id)) }}') } else{console_Log('cancel') }"  ><i class="fa fa-trash"></i> Delete file</button> --}}
                                
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>