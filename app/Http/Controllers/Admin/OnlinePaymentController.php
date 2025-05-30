<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;
class OnlinePaymentController extends Controller
{   
    protected $e_controller = "OnlinePaymentController";
    
    

    public function payment(Request $request, $param_values)
    {
        try {

            $len_variable = intval(substr($param_values, 1, 2));
            $param_values = substr($param_values, 3);
            $school_code = substr($param_values, 0, $len_variable);
            $param_values = substr($param_values, $len_variable);

            $send_param_values = "0".$param_values;

            $len_variable = intval(substr($param_values, 0, 2));
            $param_values = substr($param_values, 2);
            $registration_no = substr($param_values, 0, $len_variable);
            $param_values = substr($param_values, $len_variable);

            $upto_month = substr($param_values, 0, 2);
            $param_values = substr($param_values, 2);

            $upto_year = intval(substr($param_values, 0, 4));

            $dob_year = substr($param_values, 4, 4);
            $dob_month = substr($param_values, 8, 2);
            $dob_day = substr($param_values, 10, 2);
            $dob_complete = substr($dob_day.'-'.$dob_month.'-'.$dob_year, 0, 10);

            $from_ip = MyFuncs::getIp();

            $status = $this->insert_hit_record($from_ip, $school_code, $registration_no, $upto_month, $upto_year, $dob_complete);
            if($status[0] == 0){
                return $status[1];                
            }
            
            $final_url = $status[2].$status[3];
            $final_url = $status[2].$status[3].'/'.$send_param_values;
            return redirect()->away($final_url);
             
        } catch (\Exception $e) {
            $e_method = "payment";
            // return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function insert_hit_record($from_ip, $code, $regis_no, $upto_month, $upto_year, $dob)
    {
        $status[0] = 0;
        $status[1] = '';
        $upto_month = intval($upto_month);
        if($upto_month > 12){
            $upto_month = 0;
        }
        $upto_year = intval($upto_year);
        if(($upto_year)> (date('Y') + 1)){
            $upto_year = 0;
        }
        $rs_fetch = DB::select(DB::raw("SELECT `url`, `online_fee_url` from `admin_school_information` where `code` = '$code' limit 1;"));
        if(count($rs_fetch) == 0){
            $status[1] = 'Something Went Wrong-01';
            goto return_end;
        }
        if(($upto_month == 0) || ($upto_year == 0)){
            $status[1] = 'Something Went Wrong-02';
            goto return_end;
        }

        $temp_date = MyFuncs::check_valid_date($dob);
        if($temp_date[0] == 0){
            $status[1] = 'Something Went Wrong-03';
            goto return_end;
        }
        
        $status[2] = $rs_fetch[0]->url;
        $status[3] = $rs_fetch[0]->online_fee_url;

        $status[0] = 1;
        return_end:
            $rs_insert = DB::select(DB::raw("INSERT into `log_hit_online_fee_url`(`from_ip`, `code`, `regis_no`, `upto_month`, `upto_year`, `dob`, `log_time`, `status`) values ('$from_ip', '$code', '$regis_no', $upto_month, $upto_year, '$dob', now(), $status[0]);"));

            return $status;
    }
}
