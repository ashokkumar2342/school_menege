<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Helper\MyFuncs;
use App\Helper\WebHelper;
use DB;
use URL;
use Session;
class SupportController extends Controller
{
    protected $e_controller = "SupportController";
    
    // public function exception_handler()
    // {
    //     try {
            
    //     } catch (\Exception $e) {
    //         $e_method = "imageShowPath";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    public function deskIndex()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                return view('admin.common.error');
            }          
            return view('admin.support.desk.index');
        } catch (Exception $e) {
            $e_method = "deskIndex";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function deskTable(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $status = intval(Crypt::decrypt($request->id));
            
            $query = "SELECT `id`, `feed_back_type`, `description`, `screenshot`, `contact_nos`, date_format(`date_time`, '%d-%m-%Y %H:%i:%s') as `date_time`, `solution`, date_format(`date_time`, '%d-%m-%Y %H:%i:%s') as `solution_date`, `solution_file`, `status` from `support_desk` where `status` = '$status' order by `date_time` DESC;";
            
            $rs_records = DB::select(DB::raw("$query"));
            
           
            return view('admin.support.desk.table', compact('rs_records', 'status'));
        } catch (\Exception $e) {
            $e_method = "deskTable";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function deskForm()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }
            return view('admin.support.desk.form');
        } catch (\Exception $e) {
            $e_method = "deskForm";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function deskStore(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'feedback_type' => 'required',
                'screenshot' => 'nullable|mimes:jpeg,jpg,png,pdf|max:5000',
            ];
            $customMessages = [
                'feedback_type.required'=> 'Please Select Feedback Type',
                'screenshot.mimes'=> 'Screenshot Accepted Only jpeg,jpg,png,pdf',
                'screenshot.max'=> 'Screenshot Size Cannot Be More Then 5MB',              
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            } 
            $feedback_type_id = intval(Crypt::decrypt($request->feedback_type));
            $description = substr(MyFuncs::removeSpacialChr($request->description),0,500);            
            $contact_no = substr(MyFuncs::removeSpacialChr($request->contact_no),0,10);            
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();
            $school_code = "";
            $online_offline = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `school_code`, `online_offline` from `schoolinfo`;"));
            if(count($rs_fetch)>0){
                $school_code = $rs_fetch[0]->school_code;
                $online_offline = $rs_fetch[0]->online_offline;
            }
            $screenshot = '';
            if ($request->hasFile('screenshot')){
                $image = $request->screenshot;

                if($_FILES['screenshot']['size'] > 5000*1024) {
                  $response=['status'=>0,'msg'=>'Screenshot Size cannot be more then 5MB KB'];
                  return response()->json($response);
                   
                }elseif (($_FILES["screenshot"]["type"]=='image/jpeg') || ($_FILES["screenshot"]["type"]=='image/png')) {
                    $screenshot = $school_code.'/'.date('dmYhis').'.jpg';

                }elseif ($_FILES["screenshot"]["type"]=='application/pdf') {
                    $screenshot = $school_code.'/'.date('dmYhis').'.pdf';

                }else{
                    $response=['status'=>0,'msg'=>'Screenshot Accepted Only jpeg,jpg,png,pdf'];
                    return response()->json($response);
                }
                $image->storeAs('support/',$screenshot);
            }

            $user_detail = "";
            $rs_fetch = DB::select(DB::raw("SELECT `first_name`, `email`, `mobile` from `admins` where `id` = $user_id limit 1;"));
            if(count($rs_fetch)>0){
              $user_detail = $rs_fetch[0]->first_name.' - '.$rs_fetch[0]->email.' ('.$rs_fetch[0]->mobile.')';
            }
            
            $query = "INSERT INTO `support_desk` (`school_code`, `user_id`, `feed_back_type`, `description`, `screenshot`, `contact_nos`, `from_ip`, `date_time`, `status`, `user_detail`) values('$school_code', '$user_id', '$feedback_type_id', '$description', '$screenshot', '$contact_no', '$from_ip', now(), 0, '$user_detail');";
            if($online_offline == 1){
                $rs_insert = DB::connection('admin_gehs')->select(DB::raw("$query"));
            }else{
                $rs_insert = DB::select(DB::raw("$query"));
            }
          
            $response=['status'=>1,'msg'=>'Submitted Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "deskStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function solutionIndex()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                return view('admin.common.error');
            }          
            
            return view('admin.support.desk.solution_index');
        } catch (\Exception $e) {
            $e_method = "solutionIndex";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function solutionTable(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $status = intval(Crypt::decrypt($request->id));
            
            $query = "SELECT `id`, `school_code`, `feed_back_type`, `description`, `screenshot`, `user_id`, `contact_nos`, date_format(`date_time`, '%d-%m-%Y %H:%i:%s') as `date_time`, `solution`, date_format(`date_time`, '%d-%m-%Y %H:%i:%s') as `solution_date`, `solution_file`, `status`, `user_detail` from `support_desk` where `status` = '$status' order by `date_time` DESC;";
            
            $rs_records = DB::select(DB::raw("$query"));            
            
            return view('admin.support.desk.solution_table', compact('rs_records', 'status'));
        } catch (Exception $e) {
            $e_method = "solutionTable";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function solutionStatus($rec_id)
    {
        try {      
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }     
            return view('admin.support.desk.solution_form', compact('rec_id'));
        } catch (\Exception $e) {
            $e_method = "solutionStatus";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function solutionStore(Request $request, $rec_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'screenshot' => 'nullable|mimes:jpeg,jpg,png,pdf|max:5000',
            ];
            $customMessages = [
                'screenshot.mimes'=> 'Screenshot Accepted Only jpeg,jpg,png,pdf',
                'screenshot.max'=> 'Screenshot Size Cannot Be More Then 5MB',              
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
            $solution = substr(MyFuncs::removeSpacialChr($request->solution),0,200);
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();
            $school_code = "";
            $online_offline = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `school_code`, `online_offline` from `schoolinfo`;"));
            if(count($rs_fetch)>0){
                $school_code = $rs_fetch[0]->school_code;
                $online_offline = $rs_fetch[0]->online_offline;
            }

            $screenshot = '';
            if ($request->hasFile('screenshot')){
                $image = $request->screenshot;

                if($_FILES['screenshot']['size'] > 5000*1024) {
                  $response=['status'=>0,'msg'=>'Screenshot Size cannot be more then 5MB KB'];
                  return response()->json($response); 
                }elseif (($_FILES["screenshot"]["type"]=='image/jpeg') ||($_FILES["screenshot"]["type"]=='image/png')) {
                    $screenshot = $school_code.'/'.date('dmYhis').'.jpg';
                }elseif ($_FILES["screenshot"]["type"]=='application/pdf') {
                    $screenshot = $school_code.'/'.date('dmYhis').'.pdf';
                }else{
                    $response=['status'=>0,'msg'=>'Screenshot Accepted Only jpeg,jpg,png,pdf'];
                    return response()->json($response);
                }
                $image->storeAs('support/',$screenshot);
            }
            
            $query = "UPDATE `support_desk` set `status` = 1, `solution` = '$solution', `solution_file` = '$screenshot', `solution_date` = now() where `id` = $rec_id limit 1;";
            if($online_offline == 1){
                $rs_records = DB::connection('admin_gehs')->select(DB::raw("$query"));
            }else{
                $rs_records = DB::select(DB::raw("$query"));
            }
            $response=['status'=>1,'msg'=>'Resolved Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "solutionStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function errorIndex()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            return view('admin.support.error.index');
        } catch (\Exception $e) {
            $e_method = "errorIndex";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function errorShow(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            
            $status = intval(Crypt::decrypt($request->id));
            $rs_records = DB::select(DB::raw("SELECT `g`.`id`, `g`.`school_code`, `g`.`controller_name`, `g`.`method_function_name`, `g`.`error_detail`, `g`.`user_id`, `g`.`from_ip`, date_format(`g`.`date_time`, '%d-%m-%Y %H:%i:%s') as `date_time`, `g`.`status`, `g`.`remarks`, `g`.`user_detail` from `gehs` `g` where `g`.`status` = $status order by `g`.`date_time`;"));
            
            return view('admin.support.error.table', compact('rs_records', 'status'));
            
        } catch (Exception $e) {
            $e_method = "errorShow";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function resolved($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $l_id = intval(Crypt::decrypt($id));           
            
            $query = "UPDATE `gehs` set `status` = 1 where `id` = $l_id limit 1;";       
            
            $rs_records = DB::select(DB::raw("$query"));
            $response=['status'=>1,'msg'=>'Status Changed Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "resolved";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $l_id = intval(Crypt::decrypt($id));            
            $query = "UPDATE `gehs` set `status` = 2 where `id` = $l_id limit 1;";       
            $rs_records = DB::select(DB::raw("$query"));
            $response=['status'=>1,'msg'=>'Status Changed Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "resolved";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
}