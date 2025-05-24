@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Class Subject</h3>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group col-lg-12">
                        {{ Form::label('class','Class',['class'=>' control-label']) }}   
                        <select name="class" id="class" class="form-control select2" onchange="callAjax(this,'{{ route('admin.subject.search') }}','searchResult')">
                            <option  selected disabled>Select Class</option>
                            @foreach ($classes as $class)                         
                                <option value="{{ Crypt::encrypt($class->opt_id) }}">{{ $class->opt_text }}</option>
                            @endforeach 
                        </select>
                    </div>
                    <form id="saveSubject" action="javascript:;">
                        {{ csrf_field() }}
                        <table class="table table-bordered">
                            <thead>                  
                                <tr>
                                    <th style="width: 10px">Code</th>
                                    <th> <input  class="checked_all" type="checkbox"></th>
                                    <td><b>Subject</b></td>                         
                                    <th><button type="button" data-click="compulsory" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Compulsory</button> </th>
                                    <th ><button type="button" data-click="optional" class="btn btn-warning btn-xs"><i class="fa fa-check"></i> Elective</button>  </th>   
                                </tr>
                            </thead>
                            <tbody id="searchResult">                      
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">                                 
                                        <div class="row">                              
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-success " id="subjectBtn">Save Subject</button>
                                            </div>
                                        </div>  
                                    </td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                    {{ Form::close() }}
                </div>
                <div class="col-lg-7">
                    <fieldset class="fieldset_border">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="example">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sr. No.</th>                
                                        <th>Class Name</th>
                                        <th>Subject Name</th>
                                        <th>Compulsory/Elective</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sr_no = 1;
                                    @endphp
                                    @foreach($manageSubjects as $manageSubject)
                                        <tr>
                                            <td>{{ $sr_no++ }}</td>
                                            <td>{{ $manageSubject->class_name}}</td>
                                            <td>{{ $manageSubject->subject_name}}</td>
                                            <td>{{ $manageSubject->optional}}</td>
                                            <td align="center">
                                                <a type="button" href="{{ route('admin.manageSubject.delete',Crypt::encrypt($manageSubject->id)) }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>                
        </div>
    </div> 
</section>
@endsection
@push('scripts')
<script>
    $(function () {
        $("#example").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["excel", "colvis"]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>
<script type="text/javascript">
        $('.checked_all').on('change', function() {     
                $('.checkbox').prop('checked', $(this).prop("checked"));              
        });
        //deselect "checked all", if one of the listed checkbox product is unchecked amd select "checked all" if all of the listed checkbox product is checked
        $('.checkbox').change(function(){ //".checkbox" change 
            if($('.checkbox:checked').length == $('.checkbox').length){
                   $('.checked_all').prop('checked',true);
            }else{
                   $('.checked_all').prop('checked',false);
            }
        });       
</script>
<script type="text/javascript">
   
  $("#saveSubject").submit(function(e){
        e.preventDefault();
        $.ajax({
          method: "post",
          url: "{{ route('admin.subject.add') }}",
          data: $(this).serialize()+'&class='+'&class='+$("#class").val(),

        })
        .done(function( response ) {            
            if(response.length>0){
                $('#subjectBtn').html('Mark Subject');                 
                 toastr.success('Subject Added Succesfully');
                 window.location.reload();                
            }
            else{
                $('#subjectBtn').html('Mark Subject'); 
                 toastr.success('Subject Added Succesfully');
                 window.location.reload();
            }
            
        });
    });
     
</script>
<script>
 $( function() {
   
   $('button').click(function(){
       $('#searchResult input:radio:checked').filter(':checked').click(function () {
         $(this).prop('checked', false);
       });
       $('.'+$(this).attr('data-click')).prop('checked', true);
     });
   });
 </script>
@endpush
