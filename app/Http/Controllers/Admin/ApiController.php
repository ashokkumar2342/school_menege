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

class ApiController extends Controller
{
    protected $e_controller = "ApiController";
    
    public function getuserdetails()
    {
       try {
            $rs_user_details = DB::select(DB::raw("SELECT * FROM `user_details`;"));
            return response()->json($rs_user_details);
            
        } catch (Exception $e) {
            $e_method = "getclass";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function getclass()
    {
       try {
            $classes = MyFuncs::getClasses();
            return response()->json($classes);
            
        } catch (Exception $e) {
            $e_method = "getclass";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function getsubject($class_id)
    {
       try {
            $rs_subjects = DB::select(DB::raw("SELECT `sbt`.`id` as `opt_id`, concat(`sbt`.`name`, ' - ', case `sub`.`isoptional_id` when 1 then 'Compulsory' else 'Optional' end) as `opt_text` from `subjects` `sub` inner join `subject_types` `sbt` on `sbt`.`id` = `sub`.`subjectType_id` where `sub`.`classType_id` = $class_id and `sub`.`status` = 1 order by `sbt`.`sorting_order_id`;"));
            return response()->json($rs_subjects);
            
        } catch (Exception $e) {
            $e_method = "getsubject";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function getchapter($subject_id)
    {
       try {
            $rs_chapter = DB::select(DB::raw("SELECT `id` as `opt_id`, `chapter_topic_name` as `opt_text` from `chapter_topic` where `subjectType_id` = $subject_id;"));
            return response()->json($rs_chapter);
            
        } catch (Exception $e) {
            $e_method = "getchapter";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function getvideo($chapter_id)
    {
       try {
            $videos = DB::select(DB::raw("SELECT * FROM `videos` where `id` = $chapter_id;"));
            return response()->json($videos);
            
        } catch (Exception $e) {
            $e_method = "getvideo";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function stremvideo($paramiter)
    {
       try {
            // $rec_id = $paramiter;
            $rec_id = $paramiter;
            $videos = DB::select(DB::raw("SELECT * FROM `videos` where `id` = $rec_id;"));
            $path = $videos[0]->video_path;
            $storagePath = Storage_path() . '/app/'.$path;
            if (file_exists($storagePath)) {
                $mimeType = mime_content_type($storagePath);

                // Return video file inline
                return response()->file($storagePath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($storagePath) . '"'
                ]);
            } else {
                // File Not Found view
                return view('error.fnf', compact('storagePath'));
            }
            
        } catch (Exception $e) {
            $e_method = "getvideo";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    // Securely stream the video file
public function strem_video(Request $request)
{
    try {

        $rec_id = Crypt::decrypt($request->id);
        $token = $request->token;

        $allowedHost = parse_url("https://manage.eageskool.com", PHP_URL_HOST);
        $referer = $request->headers->get('referer');
         dd(parse_url($referer, PHP_URL_HOST), $allowedHost);
        // dd(parse_url($referer, PHP_URL_HOST));
        if (parse_url($referer, PHP_URL_HOST) != $allowedHost) {
            dd(parse_url($referer, PHP_URL_HOST), $allowedHost);
            // abort(403, 'Unauthorized access.');
        }

        $videos = DB::select(DB::raw("SELECT * FROM `videos` where `id` = $rec_id;"));
        $url = $videos[0]->video_path;
        $storagePath = storage_path('app/' . $url);

        if (!\File::exists($storagePath)) {
            return view('error.home');
        }

        return response()->file($storagePath);
    } catch (Exception $e) {
        Log::error('UserManualController-videoStream: ' . $e->getMessage());
        return view('error.home');
    }
}

    public function getpdf($chapter_id)
    {
       try {
            $pdfs = DB::select(DB::raw("SELECT * FROM `pdfs` where `id` = $chapter_id;"));
            return response()->json($pdfs);
            
        } catch (Exception $e) {
            $e_method = "getpdf";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function strempdf($paramiter)
    {
       try {
            $rec_id = $paramiter;
            $pdfs = DB::select(DB::raw("SELECT * FROM `pdfs` where `id` = $rec_id;"));
            $path = 'app/'.$pdfs[0]->pdf_path;

            $storagePath = storage_path($path);          
            if(file_exists($storagePath)){
                $mimeType = mime_content_type($storagePath);
                return response()->file($storagePath);
            }else{
                return 'File Not Found';
            }
            
        } catch (Exception $e) {
            $e_method = "strempdf";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

}
