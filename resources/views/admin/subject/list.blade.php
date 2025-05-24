@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Subjects List</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a type="button" class="btn btn-info btn-sm"  onclick="callPopupLarge(this,'{{ route('admin.subjectType.edit',Crypt::encrypt(0)) }}')">Add Subjects</a>
                </ol>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="fieldset_border">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="example">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sr. No.</th>                
                                        <th>Subject Name</th>
                                        <th>Subject Code</th>
                                        <th>Sorting Order No.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $sr_no = 1;
                                    @endphp
                                    @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $sr_no++ }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->sorting_order_id }}</td>
                                        <td align="center">
                                            <a type="button" class="btn btn-info btn-sm"onclick="callPopupLarge(this,'{{ route('admin.subjectType.edit',Crypt::encrypt($subject->id)) }}')"><i class="fa fa-edit"></i> Edit</a>
                                            <a type="button" href="{{ route('admin.subjectType.delete',Crypt::encrypt($subject->id)) }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>

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
@endpush
