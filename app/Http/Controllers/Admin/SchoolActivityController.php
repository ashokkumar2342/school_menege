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


class SchoolActivityController extends Controller
{
    protected $e_controller = "SchoolActivityController";
    
    public function index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(30);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $rs_records = DB::select(DB::raw("SELECT * from `admin_school_information` order by `id`;"));
            return view('admin.schoolActivity.index',compact('rs_records'));                
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $rules= [     
                'school_detail' => 'required', 
                'report_type' => 'required', 
                'from_date' => 'required',
                'to_date' => 'required',
            ];
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            $url = Crypt::decrypt($request->school_detail);
            $report_type_id = Crypt::decrypt($request->report_type);
            $from_date = $request->from_date;
            $result_date = MyFuncs::check_valid_date($from_date);
            if($result_date[0] == 0){
                $response=array();
                $response['status']=0;
                $response['msg']='From Date Is Not Valid';
                return $response;
            }
            $from_date = $result_date[2];

            $to_date = $request->to_date;
            $result_date = MyFuncs::check_valid_date($to_date);
            if($result_date[0] == 0){
                $response=array();
                $response['status']=0;
                $response['msg']='To Date Is Not Valid';   
                return $response;
            }
            $to_date = $result_date[2];
            $response = array();
            if ($report_type_id == 1) {
                $complite_url = $url.'/api/getLoginDetail?from_date='.$from_date.'&to_date='.$to_date;
                $rs_result = $this->getApi_ResultValues($complite_url);
                $tcols = 5;
                $qcols = array(
                    array('User Detail',30),
                    array('Role',15),
                    array('Login IP',15),
                    array('Login Time',20),
                    array('Logout Time',20),
                );
                $response['data'] =view('admin.schoolActivity.result',compact('rs_result', 'tcols', 'qcols'))->render();
            }
            if ($report_type_id == 2) {
                $rs_fetch = DB::select(DB::raw("SELECT * from `admin_school_information` where `url` = '$url' limit 1;"));
                if (count($rs_fetch) == 0) {
                    $response=array();
                    $response['status']=0;
                    $response['msg']='School Detail Not Valid';   
                    return $response;
                }
                $school_code = $rs_fetch[0]->code;
                $rs_result = DB::select(DB::raw("SELECT `query_text`, date_format(`log_date_time`, '%d-%m-%Y') as `log_date` from `log_update_query` where `school_code` = '$school_code' and  `log_date` >= '$from_date' and `log_date` <= '$to_date' order by `log_date` DESC;"));
                $tcols = 2;
                $qcols = array(
                    array('Query',30),
                    array('Date Time',20),
                );
                $response['data'] =view('admin.schoolActivity.result_2',compact('rs_result', 'tcols', 'qcols'))->render();
            }

            
            $response['status'] = 1; 
            return $response;
        } catch (Exception $e) {
            $e_method = "show";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }        
    }

    public static function getApi_ResultValues($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return [];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            return [];
        }

        return json_decode($response, true) ?? [];
    }

}
