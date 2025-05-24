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
use App\Helper\SelectBox;


class SubjectTypeController extends Controller
{
    protected $e_controller = "SubjectTypeController";
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
            $permission_flag = MyFuncs::isPermission_route(12);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $subjects = DB::select(DB::raw("SELECT * from `subject_types` order by `sorting_order_id`;"));
            return view('admin.subject.list',compact('subjects'));        
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(12);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }
            $id = intval(Crypt::decrypt($id));
            $subjects = DB::select(DB::raw("SELECT * from `subject_types` where `id` = $id limit 1;"));
            $subjects = reset($subjects);    
            return view('admin.subject.subject_edit',compact('subjects', 'id'));
        } catch (\Exception $e) {
            $e_method = "edit";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function update(Request $request,$id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(12);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            
            $rules=[
                'name' => 'required|min:2|max:50',
                'code' => 'required|max:10',
                'sorting_order_id' => 'required|max:2',
            ];

            $customMessages = [
                'name.required'=> 'Subject Name is Required',
                'name.min'=> 'Subject Name Should be Minimum of 2 Character',
                'name.max'=> 'Subject Name Should be Maximum of 50 Character',

                'code.required'=> 'Subject Code is Required',
                'code.max'=> 'Subject Code Should be Maximum of 10 Character',

                'sorting_order_id.required'=> 'Sorting Order No. is Required',
                'sorting_order_id.max'=> 'Sorting Order No. Should be Maximum of 2 Character',
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
            $subject_name = substr(MyFuncs::removeSpacialChr($request->name), 0, 50);
            $subject_code = substr(MyFuncs::removeSpacialChr($request->code), 0, 10);
            $subject_order = intval(MyFuncs::removeSpacialChr($request->sorting_order_id));

            if($subject_order > 50){
                $subject_order = 50;
            }
            
            $rs_update = DB::select(DB::raw("call `up_save_subjects`($id, '$subject_name', '$subject_code', $subject_order, $userid);"));

            $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "update";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function destroy($id)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(12);
            if(!$permission_flag){
                return  redirect()->back()->with(['message'=>'Something Went Wrong','class'=>'error']);
            }
            
            $id = intval(Crypt::decrypt($id));
            $rs_update = DB::select(DB::raw("DELETE from `subject_types` where `id` = $id limit 1;"));
            
            return  redirect()->back()->with(['message'=>'Deleted Successfully','class'=>'success']);        
        } catch (\Exception $e) {
            $e_method = "destroy";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
}
