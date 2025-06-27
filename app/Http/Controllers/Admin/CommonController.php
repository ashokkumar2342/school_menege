<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;
class CommonController extends Controller
{   
    protected $e_controller = "CommonController";
    
    public function classWiseSubject(Request $request)
    {
        try {
            $class_id = intval(Crypt::decrypt($request->id));
            $rs_records = DB::select(DB::raw("SELECT `sbt`.`id` as `opt_id`, concat(`sbt`.`name`, ' - ', case `sub`.`isoptional_id` when 1 then 'Compulsory' else 'Optional' end) as `opt_text` from `subjects` `sub` inner join `subject_types` `sbt` on `sbt`.`id` = `sub`.`subjectType_id` where `sub`.`classType_id` = $class_id and `sub`.`status` = 1 order by `sbt`.`sorting_order_id`;"));
            $show_disabled = 1;
            $box_caption = "Subjects";
            return view('admin.common.select_box_v1', compact('rs_records', 'show_disabled', 'box_caption', 'show_all'));
            
        } catch (Exception $e) {
            $e_method = "classWiseSubject";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function subjectWiseChapter(Request $request)
    {
        try {
            $class_id = intval(Crypt::decrypt($request->class_id));
            $subject_id = intval(Crypt::decrypt($request->subject_id));
            $rs_records = DB::select(DB::raw("SELECT `id` as `opt_id`, `chapter_topic_name` as `opt_text` from `chapter_topic` where `classType_id` = $class_id and `subjectType_id` = $subject_id;"));
            $show_disabled = 1;
            $box_caption = "Chapter Topic Name";
            return view('admin.common.select_box_v1', compact('rs_records', 'show_disabled', 'box_caption', 'show_all'));
            
        } catch (Exception $e) {
            $e_method = "subjectWiseChapter";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }
    
}
