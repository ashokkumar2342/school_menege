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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiController extends Controller
{
    protected $e_controller = "ApiController";

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

    public function getallsubjects()
    {
       try {
            $rs_subjects = MyFuncs::getSubjectType();
            return response()->json($rs_subjects);
            
        } catch (Exception $e) {
            $e_method = "getallsubjects";
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

    public function getchapter($class_id, $subject_id)
    {
       try {
            $rs_chapter = DB::select(DB::raw("SELECT `id` as `opt_id`, `chapter_topic_name` as `opt_text` from `chapter_topic` where `classType_id` = $class_id and `subjectType_id` = $subject_id;"));
            return response()->json($rs_chapter);
            
        } catch (Exception $e) {
            $e_method = "getchapter";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function getvideo($chapter_id)
    {
       try {
            $videos = DB::select(DB::raw("SELECT * FROM `videos` where `chapter_id` = $chapter_id;"));
            return response()->json($videos);
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

            // $allowedHost = parse_url('https://manage.eageskool.com', PHP_URL_HOST);
            $referer = $request->headers->get('referer');
            $allowedHosts = ['manage.eageskool.com', '127.0.0.1', 'localhost', 'demo.eageskool.com', 'eduvia.eageskool.com', 'vpsjuniorrtk.eageskool.com'];
            if (!in_array(parse_url($referer, PHP_URL_HOST), $allowedHosts)) {
                return view('error.home');
            }

            $videos = DB::select(DB::raw("SELECT * FROM `videos` where `id` = $rec_id;"));
            $url = $videos[0]->video_path;
            $storagePath='https://eageskoolvideo.s3.ap-south-1.amazonaws.com/'.$url;
            return redirect()->away($storagePath);
        } catch (Exception $e) {
            Log::error('UserManualController-videoStream: ' . $e->getMessage());
            return view('error.home');
        }
    }

    public function getpdf($chapter_id)
    {
       try {
            $pdfs = DB::select(DB::raw("SELECT * FROM `pdfs` where `chapter_id` = $chapter_id;"));
            return response()->json($pdfs);
            
        } catch (Exception $e) {
            $e_method = "getpdf";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    // public function securePdfView(Request $request, $encryptedPath)
    // {
    //     try {
    //         $referer = $request->headers->get('referer');
    //         $allowedHosts = ['manage.eageskool.com', '127.0.0.1', 'localhost'];

    //         if (!$referer || !in_array(parse_url($referer, PHP_URL_HOST), $allowedHosts)) {
    //             abort(403, 'Unauthorized access.');
    //         }

    //         $relativePath = Crypt::decrypt($encryptedPath);
    //         $fullPath = storage_path($relativePath);

    //         if (!\File::exists($fullPath)) {
    //             abort(404, 'PDF not found');
    //         }

    //         return response()->file($fullPath, [
    //             'Content-Type' => 'application/pdf',
    //             'Content-Disposition' => 'inline; filename="secure.pdf"',
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('securePdfView failed: ' . $e->getMessage());
    //         return abort(403, 'Invalid or expired link');
    //     }
    // }

    public function show($enc, Request $request)
    {
        // 1) Decrypt S3 key/path
        try {
            $key = Crypt::decrypt($enc); // e.g. "pdfs/4/1/1/14062025154909.pdf"
        } catch (\Exception $e) {
            abort(404, 'Invalid file.');
        }

        $disk = Storage::disk('s3');

        // 2) Exists check
        if (!$disk->exists($key)) {
            abort(404, 'File not found.');
        }

        // 3) S3 client + bucket (for HEAD & Range support)
        $adapter = $disk->getDriver()->getAdapter();
        $client  = $adapter->getClient();
        $bucket  = $adapter->getBucket();

        // 4) Fetch metadata (size, content-type)
        $head = $client->headObject([
            'Bucket' => $bucket,
            'Key'    => $key,
        ]);

        $size         = isset($head['ContentLength']) ? (int) $head['ContentLength'] : null;
        $contentType  = isset($head['ContentType']) ? (string) $head['ContentType'] : 'application/pdf';
        $filename     = basename($key);

        // Common headers (inline display)
        $baseHeaders = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Accept-Ranges'       => 'bytes',
            // 'Cache-Control'     => 'private, max-age=0, no-store', // (optional) stricter caching
        ];

        // 5) Byte-Range support (PDF.js ke liye helpful, large files pe seek smooth ho jata)
        $rangeHeader = $request->headers->get('Range'); // e.g. bytes=0-1023
        if ($rangeHeader && $size) {
            // Parse range
            if (!preg_match('/bytes=(\d*)-(\d*)/', $rangeHeader, $matches)) {
                // Malformed Range -> ignore and stream full
                return $this->streamFull($disk, $key, $size, $baseHeaders);
            }

            $start = ($matches[1] !== '') ? (int) $matches[1] : 0;
            $end   = ($matches[2] !== '') ? (int) $matches[2] : ($size - 1);

            // Clamp
            if ($start > $end || $start >= $size) {
                // Invalid range -> 416
                return response('Requested Range Not Satisfiable', 416, [
                    'Content-Range' => "bytes */{$size}",
                ]);
            }
            if ($end >= $size) {
                $end = $size - 1;
            }

            $length = $end - $start + 1;

            // S3 partial get using Range
            $result = $client->getObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'Range'  => "bytes={$start}-{$end}",
            ]);

            $bodyStream = $result['Body']; // Psr7 StreamInterface

            $headers = $baseHeaders + [
                'Content-Length' => (string) $length,
                'Content-Range'  => "bytes {$start}-{$end}/{$size}",
            ];

            return new StreamedResponse(function () use ($bodyStream) {
                while (!$bodyStream->eof()) {
                    echo $bodyStream->read(8192);
                    flush();
                }
            }, 206, $headers);
        }

        // 6) Full stream (no Range header)
        return $this->streamFull($disk, $key, $size, $baseHeaders);
    }

    private function streamFull($disk, $key, $size, array $baseHeaders)
    {
        $stream = $disk->readStream($key);
        if (!$stream) {
            abort(404, 'Unable to read file stream.');
        }

        $headers = $baseHeaders;
        if (!is_null($size)) {
            $headers['Content-Length'] = (string) $size;
        }

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $headers);
    }

}
