<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\DriverPlatenumber;
use App\Region;
use Illuminate\Support\Facades\DB;

class DriverPlatenumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
     	$drivers = DB::table('users')
      		->leftjoin('driver_platenumbers', 'driver_platenumbers.user_id', '=', 'users.id')
            ->leftjoin('regions', 'regions.id', '=', 'driver_platenumbers.region_id')
            ->select('users.*','regions.name as rname','users.id as userid','driver_platenumbers.platenumber as platenumber')->where(['users.userType' => 'driver'])->get();
        
    	return view('platenumber.index', compact('drivers'));
    }

    public function edit($id){
    	$driver = User::find($id);
    	$regions = Region::all();
    	return view('platenumber.edit',compact('driver','regions'));
    }

    public function update(Request $request, $id){

    	DriverPlatenumber::updateOrCreate(['user_id' => $id],[
    		'region_id' => $request->region_id,
    		'platenumber'=> $request->platenumber ]
    	);
    	return redirect()->route('platenumber.index')->with('success','Driver Platenumber added successfully');
    }
}
