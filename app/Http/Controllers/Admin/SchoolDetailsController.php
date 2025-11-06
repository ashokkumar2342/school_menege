<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Helper\MyFuncs;


class SchoolDetailsController extends Controller
{
    protected $e_controller = "SchoolDetailsController";
    public function exception_handler()
    {
        try {
                
        } catch (\Exception $e) {
            $e_method = "imageShowPath";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
    public function index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(30);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $rs_records = DB::select(DB::raw("SELECT * from `admin_school_information` order by `id`;"));
            return view('admin.schoolDetail.index',compact('rs_records'));                
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    
    public function edit($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(30);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }
            $id = intval(Crypt::decrypt($id));
            $rs_record = DB::select(DB::raw("SELECT * from `admin_school_information` where `id` = $id limit 1;"));
            $rs_record = reset($rs_record);
            return view('admin.schoolDetail.form',compact('rs_record', 'id'));
                
        } catch (\Exception $e) {
            $e_method = "edit";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
        
    }


    public function update(Request $request, $id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(30);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules=[
                'code' => 'required',
            ];

            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            $id = intval(Crypt::decrypt($id));
            $userid = MyFuncs::getUserId();
            $code = substr(MyFuncs::removeSpacialChr($request->code), 0, 10);
            $name = substr(MyFuncs::removeSpacialChr($request->name), 0, 100);
            $address = substr(MyFuncs::removeSpacialChr($request->address), 0, 100);
            $state = substr(MyFuncs::removeSpacialChr($request->state), 0, 50);
            $city = substr(MyFuncs::removeSpacialChr($request->city), 0, 50);
            $pincode = substr(MyFuncs::removeSpacialChr($request->pincode), 0, 6);
            $email = substr(MyFuncs::removeSpacialChr($request->email), 0, 100);
            $mobile_landline = substr(MyFuncs::removeSpacialChr($request->mobile_landline), 0, 20);
            $contact_person = substr(MyFuncs::removeSpacialChr($request->contact_person), 0, 50);
            $url = substr(MyFuncs::removeSpacialChr($request->url), 0, 100);
            $renewal_rate = intval(substr(MyFuncs::removeSpacialChr($request->renewal_rate), 0, 10));
            $valid_upto = MyFuncs::removeSpacialChr($request->valid_upto);
            $online_fee_url = substr(MyFuncs::removeSpacialChr($request->online_fee_url), 0, 100);
            if ($id == 0) {
                $rs_insert = DB::select(DB::raw("INSERT into `admin_school_information`(`code`, `name`, `address`, `state`, `city`, `pincode`, `email`, `mobile_landline`, `contact_person`, `url`, `renewal_rate`, `valid_upto`, `online_fee_url`) values('$code', '$name', '$address', '$state', '$city', '$pincode', '$email', '$mobile_landline', '$contact_person', '$url', '$renewal_rate', '$valid_upto', '$online_fee_url');"));                
            }else{
                $rs_update = DB::select(DB::raw("UPDATE `admin_school_information` set `code` = '$code', `name` = '$name', `address` = '$address', `state` = '$state', `city` = '$city', `pincode` = '$pincode', `email` = '$email', `mobile_landline` = '$mobile_landline', `contact_person` = '$contact_person', `url` = '$url', `renewal_rate` = '$renewal_rate', `valid_upto` = '$valid_upto', `online_fee_url` = '$online_fee_url' where `id` = $id limit 1;"));
            }

            $response=['status'=>1,'msg'=>'Saved Successfully'];
            return response()->json($response);
                
        } catch (\Exception $e) {
            $e_method = "update";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }         
    }

   

    public function destroy($id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(30);
            if(!$permission_flag){
                return  redirect()->back()->with(['message'=>'Something Went Wrong','class'=>'error']);
            }
            $id = intval(Crypt::decrypt($id));
            $rs_update = DB::select(DB::raw("DELETE from `admin_school_information` where `id` = $id limit 1;"));
            
            return  redirect()->back()->with(['message'=>'Deleted Successfully','class'=>'success']);
                
        } catch (\Exception $e) {
            $e_method = "destroy";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    
     
}
