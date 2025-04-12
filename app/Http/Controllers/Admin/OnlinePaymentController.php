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
    
    

    public function payment(Request $request, $s_code)
    {
        try {
            $s_code = intval($s_code);
            return redirect()->away('https://vaishmodelschoolbhiwani.com/newadmin/fee-pay-index');
            if ($s_code == 1000) {
                return redirect()->away('https://vaishmodelschoolbhiwani.com/newadmin/fee-pay-index');
            }else{
                return 'Something went wrong';
            } 
        } catch (Exception $e) {
            $e_method = "getTraDataPopUpdate";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
}
