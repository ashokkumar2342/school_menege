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

    public function getpdf()
    {
       try {
            $pdfs = DB::select(DB::raw("SELECT * FROM `pdfs`"));
            return response()->json($pdfs);
            
        } catch (Exception $e) {
            $e_method = "getpdf";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function strempdf($paramiter)
    {
       try {
            $rec_id = 1;
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
