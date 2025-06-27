<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class ChapterController extends Controller
{
    protected $e_controller = "ChapterController";

    public function index()
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(14);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $classes = MyFuncs::getClasses();  
            return view('admin.chapter.index',compact('classes'));
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }

    }

    public function table(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(14);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $class_id = intval(Crypt::decrypt($request->class));
            

            $rs_result = DB::select(DB::raw("SELECT `ct`.`id`, `ct`.`chapter_topic_name`, `st`.`name` from `chapter_topic` `ct` inner join `subject_types` `st` on `st`.`id` = `ct`.`subjectType_id` where `ct`.`classType_id` = $class_id;"));
            return view('admin.chapter.table',compact('rs_result'));
        } catch (Exception $e) {
            $e_method = "table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(14);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules= [     
                'class' => 'required', 
                'subject' => 'required', 
                'chpater' => 'required',
            ];
            $customMessages = [
                'class.required'=> 'Please Select Class',
                'subject.required'=> 'Please Select Subject',
                'chpater.required'=> 'Please Enter Chapter/Topic Name',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            $class_id = intval(Crypt::decrypt($request->class));            
            $subject_id = intval(Crypt::decrypt($request->subject));
            $chapter = MyFuncs::removeSpacialChr($request->chpater);            
            
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();
            $rs_update = DB::select(DB::raw("INSERT into `chapter_topic`(`classType_id`, `subjectType_id`, `chapter_topic_name`, `user_id`) values($class_id, $subject_id, '$chapter', $user_id);"));

            $response=['status'=>1,'msg'=>'Save Successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $e_method = "store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function edit($rec_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(14);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }
            $rec_id = intval(Crypt::decrypt($rec_id));
            $rs_result = DB::select(DB::raw("SELECT `ct`.`id`, `ct`.`chapter_topic_name`from `chapter_topic` `ct`  where `ct`.`id` = $rec_id limit 1;"));
            return view('admin.chapter.edit',compact('rs_result', 'rec_id'));
        } catch (Exception $e) {
            $e_method = "edit";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function update(Request $request, $rec_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(14);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules= [     
                'chpater' => 'required',
            ];
            $customMessages = [
                'chpater.required'=> 'Please Enter Chapter/Topic Name',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            $rec_id = intval(Crypt::decrypt($rec_id));            
            $chapter = MyFuncs::removeSpacialChr($request->chpater);
            $rs_update = DB::select(DB::raw("UPDATE `chapter_topic` set `chapter_topic_name` = '$chapter' where `id` = $rec_id limit 1;"));

            $response=['status'=>1,'msg'=>'Update Successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $e_method = "update";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
}
