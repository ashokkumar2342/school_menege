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

class SubjectController extends Controller
{
    protected $e_controller = "SubjectController";
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
            $permission_flag = MyFuncs::isPermission_route(13);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = MyFuncs::getUserRoleId();

            if($role_id == 1){
                $manageSubjects = DB::select("SELECT `cs`.`id`, `ct`.`name` as `class_name`, `sub`.`name` as `subject_name`, `io`.`name` as `optional` from `subjects` `cs` inner join `subject_types` `sub` on `sub`.`id` = `cs`.`subjectType_id` inner join `class_types` `ct` on `ct`.`id` = `cs`.`classType_id` inner join `isoptionals` `io` on `io`.`id` = `cs`.`isoptional_id` where `cs`.`status` = 1 order by `ct`.`name`, `sub`.`name`;");
            }else{
                $manageSubjects = DB::select("SELECT `cs`.`id`, `ct`.`name` as `class_name`, `sub`.`name` as `subject_name`, `io`.`name` as `optional` from `subjects` `cs` inner join `subject_types` `sub` on `sub`.`id` = `cs`.`subjectType_id` inner join `class_types` `ct` on `ct`.`id` = `cs`.`classType_id` inner join `isoptionals` `io` on `io`.`id` = `cs`.`isoptional_id` where `cs`.`status` = 1 order by `ct`.`name`, `sub`.`name`;");
                // $manageSubjects = DB::select("SELECT `cs`.`id`, `ct`.`name` as `class_name`, `sub`.`name` as `subject_name`, `io`.`name` as `optional` from `subjects` `cs` inner join `subject_types` `sub` on `sub`.`id` = `cs`.`subjectType_id` inner join `class_types` `ct` on `ct`.`id` = `cs`.`classType_id` inner join `isoptionals` `io` on `io`.`id` = `cs`.`isoptional_id` where `cs`.`status` = 1 and `ct`.`id` in (select distinct `class_id` from `user_class_types` where `admin_id` = $user_id) order by `ct`.`name`, `sub`.`name`;");
            }
            
            $classes = MyFuncs::getClasses(); 
            return view('admin.subject.manageSubject',compact('manageSubjects','classes'));        
        } catch (Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(13);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $class = intval(Crypt::decrypt($request->id));
            $subjectTypes = DB::select("SELECT `sub`.`id`, `sub`.`code`, `sub`.`name` as `subject_name`, ifnull(`io`.`name`,'') as `optional`, ifnull(`cs`.`status`,0) as `is_class_sub`, ifnull(`io`.`id`,0) as `opt_id`, `sub`.`sorting_order_id` from `subject_types` `sub` inner join `subjects` `cs` on `sub`.`id` = `cs`.`subjectType_id` inner join `isoptionals` `io` on `io`.`id` = `cs`.`isoptional_id` where (`cs`.`classType_id` = $class) union select `sub1`.`id`, `sub1`.`code`, `sub1`.`name` as `subject_name`, '' as `optional`, 0 as `is_class_sub`, 0 as `opt_id`, `sub1`.`sorting_order_id` from `subject_types` `sub1` where `sub1`.`id` not in (select `subjectType_id` from `subjects` where `classType_id` = $class and `status` = 1) order by `is_class_sub` desc, `sorting_order_id`;");
            
            return view('admin.subject.subject_list',compact('subjectTypes'));    
        } catch (\Exception $e) {
            $e_method = "search";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(13);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $user_id = MyFuncs::getUserId();
            $class_id = intval(Crypt::decrypt($request->class));
            $rs_update = DB::select("UPDATE `subjects` set `status` = 0, `isoptional_id` = 0 where `classType_id` = $class_id;");
            foreach ($request->value as $key => $value) {
                // $sub_id = intval(Crypt::decrypt($key));
                $sub_id = intval($key);
                $rs_update = DB::select("call `up_save_class_subjects`('$sub_id', $class_id, '$value', $user_id);");
            }
            
            return response()->json(['response'=>'OK','message'=>'Subject Added/Updated Successfully', 'class'=>'sucess']);
                
        } catch (\Exception $e) {
            $e_method = "store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(13);
            if(!$permission_flag){
                return  redirect()->back()->with(['message'=>'Something Went Wrong','class'=>'error']);
            }

            $id = intval(Crypt::decrypt($id));
            $rs_update = DB::select(DB::raw("DELETE from `subjects` where `id` = $id limit 1;"));
            
            return  redirect()->back()->with(['message'=>'Deleted Successfully','class'=>'success']);    
        } catch (\Exception $e) {
            $e_method = "destroy";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
            
    }
}
