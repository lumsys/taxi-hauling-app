<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(){

    	$audits = \OwenIt\Auditing\Models\Audit::with('user')->orderBy('created_at','desc')->get();

    	return view('dashboard.audit_trail',compact('audits'));
    }

    public function create()
    {
        return view('dashboard.audit_trail',compact('audits'));
    } 

}
