<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\AppSetting;
use App\Http\Controllers\Controller;

class AppSettingController extends Controller
{
    public function index(){

   	$appsettings = AppSetting::all();
       // $appsetting = AppSetting::where('key','google_maps_key')->first();
          if ($appsettings){
            return response()->json([
                'success' => true,
                'data' => $appsettings,
            ]);
        }
    }

     
}
