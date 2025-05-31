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
            return view('admin.video.index',compact('classes'));
        } catch (\Exception $e) {
            $e_method = "video_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }

    }

    public function video_table(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(15);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $chapter_id = intval(Crypt::decrypt($request->id));

            $rs_result = DB::select(DB::raw("SELECT * from `videos` where `chapter_id` = $chapter_id;"));
            return view('admin.video.table',compact('rs_result'));
        } catch (Exception $e) {
            $e_method = "video_table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function video_store(Request $request)
    {
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

            $video->storeAs($folder_path, $filename);
                      
            $rs_update = DB::select(DB::raw("INSERT into `videos`(`classType_id`, `subjectType_id`, `chapter_id`, `video_path`, `title`, `description`) values($class_id, $subject_id, '$chpater_id', '$final_path_video', '$title', '$description');"));

            $response=['status'=>1,'msg'=>'Upload Successfully'];
            return response()->json($response);
        } catch (Exception $e) {
            $e_method = "video_store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function pdf_index()
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(15);
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
            $permission_flag = MyFuncs::isPermission_route(15);
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
            $permission_flag = MyFuncs::isPermission_route(15);
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
