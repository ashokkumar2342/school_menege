@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>School Detail List</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <button type="button" class="btn btn-info btn-sm"  onclick="callPopupLarge(this,'{{ route('admin.school.detail.edit', Crypt::encrypt(0)) }}')"><i class="fa fa-plus"></i> Add School Detail</button>
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
                                        <th>Sr.No.</th>                
                                        <th>Action</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Pincode</th>
                                        <th>Email</th>
                                        <th>Mobile Landline</th>
                                        <th>Contact Person</th>
                                        <th>Url</th>
                                        <th>Renewal Rate</th>
                                        <th>Valid Upto</th>
                                        <th>Online Fee Url</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sr_no = 1;
                                    @endphp
                                    @foreach($rs_records as $value)
                                    <tr>
                                        <td>{{ $sr_no++ }}</td>
                                        <td>
                                            <a type="button" class="btn btn-info btn-sm"onclick="callPopupLarge(this,'{{ route('admin.school.detail.edit',Crypt::encrypt($value->id)) }}')"><i class="fa fa-edit"></i> Edit</a>
                                            <a type="button" href="{{ route('admin.school.detail.delete',Crypt::encrypt($value->id)) }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>

                                        </td>
                                        <td>{{ $value->code }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->address }}</td>
                                        <td>{{ $value->state }}</td>
                                        <td>{{ $value->city }}</td>
                                        <td>{{ $value->pincode }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->mobile_landline }}</td>
                                        <td>{{ $value->contact_person }}</td>
                                        <td>{{ $value->url }}</td>
                                        <td>{{ $value->renewal_rate }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->valid_upto)) }}</td>
                                        <td>{{ $value->online_fee_url }}</td>

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
