<?php

namespace App\Helper;
use Illuminate\Support\Facades\Auth;
use Route;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;

class MyFuncs {

  public static function Exception_error_handler($controller, $method, $error_message) 
  {
    $user_id = MyFuncs::getUserId();
    $from_ip = MyFuncs::getIp();

    // $error_message = MyFuncs::removeSpacialChr($error_message);

    // $user_detail = "";
    // $rs_fetch = DB::select(DB::raw("SELECT `first_name`, `email`, `mobile` from `admins` where `id` = $user_id limit 1;"));
    // if(count($rs_fetch)>0){
    //   $user_detail = $rs_fetch[0]->first_name.' - '.$rs_fetch[0]->email.' ('.$rs_fetch[0]->mobile.')';
    // }
    
    // $rs_insert = DB::select(DB::raw("INSERT into `gehs` (`controller_name`, `method_function_name`, `error_detail`, `user_id`, `from_ip`, `date_time`, `status`, `remarks`, `user_detail`) values ('$controller', '$method', '$error_message', '$user_id', '$from_ip', now(), 0, '', '$user_detail');"));
  }

  public static function generateId() 
  {
    return rand(1111111111,100000000); 
  }

  public static function generateRandomIV() 
  {
    return substr(uniqid(), 1, 8); 
  }

  public static function getIp()
  {

    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
      foreach ($matches[0] AS $xip) {
        if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
          $ip = $xip;
          break;
        }
      }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
      $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
      $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
  }

  public static function isPermission_route($sub_menu_id)
  { 
    $user_rs = Auth::guard('admin')->user(); 
    if(empty($user_rs)){
      return false;
    }

    // $rs_fetch = DB::select(DB::raw("SELECT `web_url` from `schoolinfo` limit 1;"));
    // $web_url = $rs_fetch[0]->web_url;
    $web_url = "manage.eageskool.com";

    $http_host =  $_SERVER['HTTP_HOST'];
    if(($http_host != $web_url) && ($http_host != 'localhost:8000') && ($http_host != 'localhost:81') && ($http_host != 'localhost')){
      return false;
      return Redirect::route('logout')->with(['error_msg' => 'Unauthorised Access to Application !!']);
    }

    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;
    
    $sub_menu_id = "(".$sub_menu_id.")";
    $result_rs = DB::select(DB::raw("SELECT * from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1 and `sm`.`id` in $sub_menu_id limit 1;"));
    
    if(count($result_rs)>0){
      return true;  
    }

    return false;
  }

  public static function getClasses()
  {
    $result_rs = DB::select(DB::raw("SELECT `ct`.`id` as `opt_id`, `ct`.`name` as `opt_text` from `class_types` `ct` where `ct`.`status` = 1 order by `ct`.`shorting_id`;"));
    return $result_rs;
    
  }

  public static function getUserId()
  {
    return $user = Auth::guard('admin')->user()->id;  
  }

  public static function getUserRoleId()
  {
    return $role_id = Auth::guard('admin')->user()->role_id;  
  }

  public static function check_password_strength($password, $user_id) 
  {
    $passwordError = "";
    if (strlen($password) <= 8) {
      $passwordError .= "Password must have 8 characters at least.<br>";
    }
    if (!preg_match("#[0-9]+#", $password)) {
      $passwordError .= "Password must have 1 Number at least.<br>";
    }
    if (!preg_match("#[A-Z]+#", $password)) {
      $passwordError .= "Password must have 1 uppercase letter at least.<br>";
    }
    if (!preg_match("#[a-z]+#", $password)) {
      $passwordError .= "Password must have 1 lowercase letter at least.<br>";
    }
    if (!preg_match('@[^\w]@', $password)) {
      $passwordError .= "Password must have 1 special character letter at least.<br>";
    }

    
    $rs_fetch = DB::select(DB::raw("SELECT * from `password_change_history` where `user_id` = $user_id order by `id` desc limit 3;"));
    $found = 0;
    foreach ($rs_fetch as $key => $value) {
      if(password_verify($password,$value->new_password)){
        if($found == 0){
          $passwordError .= "You used this password recently, please choose a different password.";
          $found = 1;
        }
      }
    }
    

    return $passwordError;
    
  }

  public static function check_emailid_user($user_id, $emailid)
  {
    $rs_fetch = DB::select(DB::raw("SELECT `id`  from `admins` where `email` = '$emailid' and `id` <> $user_id limit 1;"));
    if(count($rs_fetch)>0){
      return 0;
    }else{
      return 1;
    }
  }

  public static function check_mobile_user($user_id, $mobile)
  {
    $rs_fetch = DB::select(DB::raw("SELECT `id`  from `admins` where `mobile` = '$mobile' and `id` <> $user_id limit 1;"));
    if(count($rs_fetch)>0){
      return 0;
    }else{
      return 1;
    }
  }

  public static function removeSpacialChr($strValue)
  {
    $newString = trim(str_replace('\'', '', $strValue));
    $newString = trim(str_replace('\\', '', $newString));
    // $newString = trim(strip_tags($newString, "<b><u><i><div><p><h>"));
    // $newString = trim(strip_tags($newString));
    // $newString = trim(htmlspecialchars($newString));

    $newString = trim(str_replace('&lt;script&gt;', '', $newString));
    $newString = trim(str_replace('&lt;/script&gt;', '', $newString));
    
    $newString = trim(preg_replace('/script/i', '', $newString));
    $newString = trim(preg_replace('/php/i', '', $newString));
    
    return $newString;
  }

  
  public static function isPermission_reports($role_id, $report_id)
  { 
    $rs_fetch = DB::select(DB::raw("SELECT * from `report_types` where `report_id` = $report_id and `report_for` = $role_id limit 1;"));
    
    if(count($rs_fetch)>0){
      return true;  
    }
    return false;
  }

  // main menu 
  public static function mainMenu($menu_type_id)
  { 

    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;

    $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `id` = $user_id and `password_expire_on` <= curdate();"));
    if(count($rs_fetch)>0){
      $subMenus = DB::select(DB::raw("SELECT `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `sub_menus` `sm` where `sm`.`id` = 8 order by `sm`.`sorting_id` ;"));
    }else{
      $subMenus = DB::select(DB::raw("SELECT `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1 and `sm`.`menu_type_id` = $menu_type_id order by `sm`.`sorting_id` ;"));
    }

    return $subMenus;
  }

  public static function userHasMinu()
  { 

    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;
    $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `id` = $user_id and `password_expire_on` <= curdate();"));
    if(count($rs_fetch)>0){
      $menuTypes = DB::select(DB::raw("SELECT * from `minu_types` where `id` = 1 order by `sorting_id` ;"));
    }else{
      $menuTypes = DB::select(DB::raw("SELECT * from `minu_types` where `id` in (select Distinct `sm`.`menu_type_id` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1) order by `sorting_id` ;"));
    }

    return $menuTypes;
  }

  // all permission check
  public static function isPermission()
  {
    $user = Auth::guard('admin')->user();
    if(!empty($user)){ 
      $role_id = $user->role_id;
      $routeName = Route::currentRouteName();
      $rs_fetch = DB::select(DB::raw("SELECT `id` from `sub_menus` where `url` = '$routeName' and `status` = 1;"));
      if (count($rs_fetch)>0){
        $menu_id = $rs_fetch[0]->id;
        $rs_fetch = DB::select(DB::raw("SELECT `id` from `default_role_menu` where `role_id` = $role_id and `status` = 1 and `sub_menu_id` = $menu_id;"));
        if(count($rs_fetch) == 0){
          return false;    
        }
      }  
    }else{
      // return false;  
    }
    $http_host =  $_SERVER['HTTP_HOST'];
    if(($http_host != 'manage.eageskool.com') && ($http_host != 'localhost') && ($http_host != 'localhost:8000')){
      return false;
      return Redirect::route('logout')->with(['error_msg' => 'Unauthorised Access to Application !!']);
    }
    return true;
  }

  public static function check_valid_date($p_date)  //return [0]-is valid or not, 1-error message, 2-date in yyyy-mm-dd, input in dd-mm-yyyy
  {
    $result_date = array();
    $result_date[0] = 0;
    $result_date[1] = "";
    $result_date[2] = "";

    $p_date = trim(str_replace('\'', '', $p_date));
    $p_date = trim(str_replace('\\', '.', $p_date));
    $p_date = trim(str_replace('-', '.', $p_date));
    $p_date = trim(str_replace('/', '.', $p_date));
    
    if(strlen($p_date)!=10){
      $result_date[1] = "Date Not Valid";
      return $result_date;
    }

    $l_day = intval(substr($p_date, 0, 2));
    $l_month = intval(substr($p_date, 3, 2));
    $l_year = intval(substr($p_date, 6, 4));

    if(checkdate($l_month, $l_day, $l_year)){
      if($l_year < 1900 || $l_year > 2100){
        $result_date[1] = "Date Not Valid";
        return $result_date;  
      }
      
      $result_date[0] = 1;
      if(strlen($l_day) == 1){
        $l_day = "0".$l_day;
      }
      if(strlen($l_month) == 1){
        $l_month = "0".$l_month;
      }
      $result_date[2] = $l_year."-".$l_month."-".$l_day;
      return $result_date;
    }else{
      $result_date[1] = "Date Not Valid";
      return $result_date;  
    }
    
  }

  public static function uploadToS3($file, $filename)
  {
      try {
          $s3 = new S3Client([
              'version' => 'latest',
              'region'  => env('AWS_DEFAULT_REGION'),
              'credentials' => [
                  'key'    => env('AWS_ACCESS_KEY_ID'),
                  'secret' => env('AWS_SECRET_ACCESS_KEY'),
              ],
          ]);

          $bucket = env('AWS_BUCKET');

          $result = $s3->putObject([
              'Bucket' => $bucket,
              'Key'    => $filename,
              'SourceFile' => $file->getRealPath(),
              'ACL'    => 'public-read',
          ]);

          return [
              'success' => true,
              'url' => $result['ObjectURL'],
          ];
      } catch (\Exception $e) {
          \Log::error('S3 Upload Error', ['error' => $e->getMessage()]);
          return [
              'success' => false,
              'error' => $e->getMessage(),
          ];
      }
  }

  public static function uploadpdfToS3($file, $filename)
  {
      try {
          $s3 = new S3Client([
              'version' => 'latest',
              'region'  => env('AWS_DEFAULT_REGION'),
              'credentials' => [
                  'key'    => env('AWS_ACCESS_KEY_ID'),
                  'secret' => env('AWS_SECRET_ACCESS_KEY'),
              ],
          ]);

          $bucket = env('AWS_BUCKET');

          $result = $s3->putObject([
              'Bucket' => $bucket,
              'Key'    => $filename,
              'SourceFile' => $file->getRealPath(),
              'ACL'    => 'public-read',

              // 👇 Force browser to open PDF inline
              'ContentType' => 'application/pdf',
              'ContentDisposition' => 'inline',
          ]);

          return [
              'success' => true,
              'url' => $result['ObjectURL'],
          ];
      } catch (\Exception $e) {
          \Log::error('S3 Upload Error', ['error' => $e->getMessage()]);
          return [
              'success' => false,
              'error' => $e->getMessage(),
          ];
      }
  }


  
  // ----------------------- End -------------------------


}