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

class ClassTypeController extends Controller
{
    protected $e_controller = "ClassTypeController";

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
            $permission_flag = MyFuncs::isPermission_route(11);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $classes = DB::select(DB::raw("SELECT * from `class_types` order by `shorting_id`"));
            return view('admin.manage.class.list',compact('classes'));    
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(11);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }

            $id = intval(Crypt::decrypt($id));
            $classes = DB::select(DB::raw("SELECT * from `class_types` where `id` = $id limit 1;"));
            $classes = reset($classes);    

            return view('admin.manage.class.edit',compact('classes', 'id'));
        } catch (\Exception $e) {
            $e_method = "edit";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(11);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'name' => 'required|max:20',
                'code' => 'required|max:5',
                'shorting_id' => 'required|max:2',
            ];

            $customMessages = [
                'name.required'=> 'Class Name is Required',
                'name.max'=> 'Class Name Should be Maximum of 20 Character',

                'code.required'=> 'Class Code is Required',
                'code.max'=> 'Class Code Should be Maximum of 5 Character',

                'shorting_id.required'=> 'Sorting No. is Required',
                'shorting_id.max'=> 'Sorting No. Should be Maximum of 2 Character',                
            ];

            $validator = Validator::make($request->all(),$rules, $customMessages);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }

            $id = intval(Crypt::decrypt($id));
            $userid = MyFuncs::getUserId();
            $class_name = substr(MyFuncs::removeSpacialChr($request->name), 0 , 20);
            $class_code = substr(MyFuncs::removeSpacialChr($request->code), 0, 5);
            $class_order = intval(MyFuncs::removeSpacialChr($request->shorting_id));

            if($class_order > 50){
                $class_order = 50;
            }
            
            $rs_update = DB::select(DB::raw("call `up_save_classes`($id, '$class_name', '$class_code', $class_order, $userid);"));

            $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
            return response()->json($response);    
        } catch (\Exception $e) {
            $e_method = "update";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }

            
         
    }

    public function deleteclass($id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(11);
            if(!$permission_flag){
                return  redirect()->back()->with(['message'=>'Something Went Wrong','class'=>'error']);
            }
            
            $id =Crypt::decrypt($id);
            $rs_update = DB::select(DB::raw("DELETE from `class_types` where `id` = $id limit 1;"));
            return  redirect()->back()->with(['message'=>'Class Deleted Successfully','class'=>'success']);
        } catch (\Exception $e) {
            $e_method = "deleteclass";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
}
