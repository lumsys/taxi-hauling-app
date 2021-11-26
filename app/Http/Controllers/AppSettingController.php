<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppSetting;
use DB;

class AppSettingController extends Controller
{
    public function index(){
    	$appsettings = AppSetting::all();
    	return view('appsettings.index', compact('appsettings'));
    }

    public function update(Request $request){
    	$inputs = $request->except(['_token']);
    	
    	foreach($inputs as $key => $value){
    		//DB::table('app_settings')->where()->update($input);
    		AppSetting::where('key',$key)->update(['value' => $value]);
    	}

    	return back()->with('success','Updated successfully');
    }
}
