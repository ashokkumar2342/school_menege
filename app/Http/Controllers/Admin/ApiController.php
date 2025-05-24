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
    
    public function getvideo()
    {
       try {
            $videos = DB::table('videos')->get(); // cleaner than raw query
            //$videos = DB::select(DB::raw("SELECT * FROM `videos`"));

            $data = [];
            $data['status'] = 1;
            $data['msg'] = 'success';
            $data['data'] = $videos;

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
            $rec_id = 1;
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

}
