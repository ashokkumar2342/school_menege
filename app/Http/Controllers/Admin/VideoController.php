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
use Illuminate\Support\Facades\Response;
use TusPhp\Tus\Server as TusServer;
use Storage;
use Aws\S3\S3Client;

class VideoController extends Controller
{
    protected $e_controller = "VideoController";

    public function video_index()
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(15);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $classes = MyFuncs::getClasses();  
            return view('admin.video.index_v1',compact('classes'));
        } catch (\Exception $e) {
            $e_method = "video_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }

    }

    public function video_table(Request $request)
    {
        try {

            $id = intval(Crypt::decrypt($request->id));
            $rs_videos = DB::select(DB::raw("SELECT * from `videos` where `chapter_id` = $id;"));
            return view('admin.video.table', compact('rs_videos'));
        } catch (Exception $e) {
            $e_method = "video_table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    // public function video_store(Request $request)
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route(15);
    //         if(!$permission_flag){
    //             $response=['status'=>0,'msg'=>'Something Went Wrong'];
    //             return response()->json($response);
    //         }
    //         $rules= [     
    //             'class' => 'required', 
    //             'subject' => 'required', 
    //             'chpater' => 'required',
    //             'video' => 'required|file|mimes:mp4,mov,ogg,qt|max:500000', // Max 500MB
    //             'title' => 'required',
    //             'description' => 'required',
    //         ];
    //         $customMessages = [
    //             'class.required'=> 'Please Select Class',
    //             'subject.required'=> 'Please Select Subject',
    //             'chpater.required'=> 'Please Enter Chapter/Topic Name',
    //             'title.required'=> 'Please Enter Title',
    //             'description.required'=> 'Please Enter Description',
    //         ];
    //         $validator = Validator::make($request->all(),$rules, $customMessages);
    //         if ($validator->fails()) {
    //             $errors = $validator->errors()->all();
    //             $response=array();
    //             $response["status"]=0;
    //             $response["msg"]=$errors[0];
    //             return response()->json($response);// response as json
    //         }
    //         $class_id = intval(Crypt::decrypt($request->class));            
    //         $subject_id = intval(Crypt::decrypt($request->subject));
    //         $chpater_id = intval(Crypt::decrypt($request->chpater));
    //         $video = $request->video;
    //         $title = substr(MyFuncs::removeSpacialChr($request->title), 0, 250);
    //         $description = substr(MyFuncs::removeSpacialChr($request->description), 0, 500);

    //         $extension = $video->getClientOriginalExtension();

    //         // Create unique filename with extension
    //         $filename = date('dmYHis') . '.' . $extension;

    //         $folder_path = 'video/' .$class_id. '/' .$subject_id. '/' .$chpater_id;

    //         $final_path_video = $folder_path . '/' . $filename;

    //         $video->storeAs($folder_path, $filename);
                      
    //         $rs_update = DB::select(DB::raw("INSERT into `videos`(`classType_id`, `subjectType_id`, `chapter_id`, `video_path`, `title`, `description`) values($class_id, $subject_id, '$chpater_id', '$final_path_video', '$title', '$description');"));

    //         $response=['status'=>1,'msg'=>'Upload Successfully'];
    //         return response()->json($response);
    //     } catch (Exception $e) {
    //         $e_method = "video_store";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function store(Request $request)
    // {

    //         $request->validate([
    //            'video' => 'required|file|mimes:mp4,pdf|max:10240'
    //        ]);

    //        $video = $request->file('video');
    //        $filename = time() . '_' . $video->getClientOriginalName();

    //        $response = MyFuncs::uploadToS3($video, $filename);

    //        return response()->json($response);
    // }




    public function store(Request $request)
    {
        ini_set('max_execution_time', '14400');
        ini_set('memory_limit','10000M');
        ini_set("pcre.backtrack_limit", "100000000");
        ini_set('upload_max_filesize', '3048M');
        ini_set('post_max_size', '3048M');
        try {
            $permission_flag = MyFuncs::isPermission_route(15);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules= [     
                'class' => 'required', 
                'subject' => 'required', 
                'chpater' => 'required',
                'video' => 'required|file|mimes:mp4,mov,ogg,qt|max:500000', // Max 500MB
                'title' => 'required',
                'description' => 'required',
            ];
            $customMessages = [
                'class.required'=> 'Please Select Class',
                'subject.required'=> 'Please Select Subject',
                'chpater.required'=> 'Please Enter Chapter/Topic Name',
                'title.required'=> 'Please Enter Title',
                'description.required'=> 'Please Enter Description',
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
            $chpater_id = intval(Crypt::decrypt($request->chpater));
            $video = $request->video;
            $title = substr(MyFuncs::removeSpacialChr($request->title), 0, 250);
            $description = substr(MyFuncs::removeSpacialChr($request->description), 0, 500);

            $extension = $video->getClientOriginalExtension();

            // Create unique filename with extension
            $filename = date('dmYHis') . '.' . $extension;

            $folder_path = 'video/' .$class_id. '/' .$subject_id. '/' .$chpater_id;

            $final_path_video = $folder_path . '/' . $filename;

            $response = MyFuncs::uploadToS3($video, $final_path_video);
            // $video->storeAs($folder_path, $filename);
                      
            $rs_update = DB::select(DB::raw("INSERT into `videos`(`classType_id`, `subjectType_id`, `chapter_id`, `video_path`, `title`, `description`) values($class_id, $subject_id, '$chpater_id', '$final_path_video', '$title', '$description');"));

            return response()->json(['success' => true, 'message' => 'Upload Successfully']);
        } catch (Exception $e) {
            $e_method = "video_store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }



    public function watchEvent(Request $request)
    {
        $userId = MyFuncs::getUserId();

        // Optional: Skip meaningless logs
        if ((int) $request->watched_seconds < 1 && $request->action !== 'stop') {
            return response()->json(['status' => 'ignored']);
        }

        DB::table('video_watch_logs')->insert([
            'user_id'         => $userId,
            'video_id'        => (int) $request->video_id,
            'watched_seconds' => (int) $request->watched_seconds,
            'token'           => $request->token,
            'ip_address'      => $request->ip(),
            'action'          => $request->action,
            'watched_at'      => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json(['status' => 'logged']);
    }

    public function pdf_index()
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(16);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $classes = MyFuncs::getClasses();  
            return view('admin.pdf.index',compact('classes'));
        } catch (\Exception $e) {
            $e_method = "pdf_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }

    }

    public function pdf_table(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(16);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $chapter_id = intval(Crypt::decrypt($request->id));

            $rs_result = DB::select(DB::raw("SELECT * from `pdfs` where `chapter_id` = $chapter_id;"));
            return view('admin.pdf.table',compact('rs_result'));
        } catch (Exception $e) {
            $e_method = "pdf_table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function pdf_store(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(16);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules= [     
                'class' => 'required', 
                'subject' => 'required', 
                'chpater' => 'required',
                'pdf_file' => 'required|mimes:pdf|max:10240', // max size in KB (10MB here)
                'title' => 'required',
                'description' => 'required',
            ];
            $customMessages = [
                'class.required'=> 'Please Select Class',
                'subject.required'=> 'Please Select Subject',
                'chpater.required'=> 'Please Enter Chapter/Topic Name',
                'title.required'=> 'Please Enter Title',
                'description.required'=> 'Please Enter Description',
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
            $chpater_id = intval(Crypt::decrypt($request->chpater));
            $pdf = $request->pdf_file;
            $title = substr(MyFuncs::removeSpacialChr($request->title), 0, 250);
            $description = substr(MyFuncs::removeSpacialChr($request->description), 0, 500);        

            // Create unique filename with extension
            $filename = date('dmYHis').'.pdf';

            $folder_path = 'pdf/' .$class_id. '/' .$subject_id. '/' .$chpater_id;

            $final_path_pdf = $folder_path . '/' . $filename;

            $pdf->storeAs($folder_path, $filename);
                      
            $rs_update = DB::select(DB::raw("INSERT into `pdfs`(`classType_id`, `subjectType_id`, `chapter_id`, `pdf_path`, `title`, `description`) values($class_id, $subject_id, '$chpater_id', '$final_path_pdf', '$title', '$description');"));

            $response=['status'=>1,'msg'=>'Upload Successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $e_method = "pdf_store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }


    
}
