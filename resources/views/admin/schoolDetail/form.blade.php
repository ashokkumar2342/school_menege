<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ @$id>0? 'Edit' : 'Add' }}</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.school.detail.update',Crypt::encrypt(@$id)) }}" method="post" class="add_form" button-click="btn_close" content-refresh="example">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>Code</label>
                            <input type="text" name="code" value="{{ @$rs_record->code }}" maxlength="10" class="form-control" placeholder="Enter Code"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ @$rs_record->name }}" maxlength="100" class="form-control" placeholder="Enter Name"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="{{ @$rs_record->address }}" maxlength="100" class="form-control" placeholder="Enter address"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>State</label>
                            <input type="text" name="state" value="{{ @$rs_record->state }}" maxlength="50" class="form-control" placeholder="Enter state"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>City</label>
                            <input type="text" name="city" value="{{ @$rs_record->city }}" maxlength="50" class="form-control" placeholder="Enter city"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Pincode</label>
                            <input type="text" name="pincode" value="{{ @$rs_record->pincode }}" maxlength="6" class="form-control" placeholder="Enter pincode"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Email</label>
                            <input type="text" name="email" value="{{ @$rs_record->email }}" maxlength="100" class="form-control" placeholder="Enter email"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Mobile/Landline</label>
                            <input type="text" name="mobile_landline" value="{{ @$rs_record->mobile_landline }}" maxlength="20" class="form-control" placeholder="Enter Mobile/Landline"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" value="{{ @$rs_record->contact_person }}" maxlength="50" class="form-control" placeholder="Enter Contact Person"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Url</label>
                            <input type="text" name="url" value="{{ @$rs_record->url }}" maxlength="100" class="form-control" placeholder="Enter url"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Online Fee Url</label>
                            <input type="text" name="online_fee_url" value="{{ @$rs_record->online_fee_url }}" maxlength="100" class="form-control" placeholder="Enter Online Fee Url"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Renewal Rate</label>
                            <input type="text" name="renewal_rate" value="{{ @$rs_record->renewal_rate }}" maxlength="10" class="form-control" placeholder="Enter Renewal Rate"> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Valid Upto</label>
                            <input type="date" name="valid_upto" value="{{ @$rs_record->valid_upto }}" class="form-control"> 
                        </div>
                    </div> 
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">{{ @$id>0? 'Update' : 'Submit' }}</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

