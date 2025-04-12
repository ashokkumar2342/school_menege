<?php

namespace App\Http\Controllers\Admin;
use App\Helper\MyFuncs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    
    public function index()
    {        
        return view('admin.dashboard.dashboard');
    }
}
