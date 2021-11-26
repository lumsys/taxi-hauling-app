<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Trip;
use App\DriverPlatenumber;
use Auth;
use Carbon\Carbon;
use App\Region;

class BMController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	 
    	$r_driver_count = DriverPlatenumber::where('region_id',Auth::user()->region_id)->count();
    	$total_r_driver_count = DriverPlatenumber::count();
    	$users = DriverPlatenumber::where('region_id',Auth::user()->region_id)->get();

    	$from =   '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to =  Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
 
	     $previous_week = strtotime("-1 week +1 day");
	     $start_week = strtotime("last sunday midnight",$previous_week);
	     $end_week = strtotime("next saturday",$start_week);
	     $start_week = date("Y-m-d",$start_week);
	     $end_week = date("Y-m-d",$end_week);

    	$drivers = collect([]);
    	$items = collect();
 		
 		$tripAmt =0;
 		$tripCount =0;
 		$tripAmtDay =0;
 		$tripDayCount =0;

        foreach($users as $key => $name){
        $tripAmt +=  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
         $tripCount +=  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->count();
         $tripAmtDay += Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount += Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
       }
      
    return view('BM.dashboard',compact('r_driver_count','total_r_driver_count','tripAmt','tripCount','tripAmtDay','tripDayCount'));
    }

    public function searchTripp(Request $request){
    	// $users = DriverPlatenumber::where('region_id',Auth::user()->region_id)->get();

        $from = $request->first ?? '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to = $request->second. '11:59' ?? Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
        
         $drivers = collect([]);
         $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])
         ->join('driver_platenumbers', 'driver_platenumbers.user_id', '=', 'users.id')
         ->where('driver_platenumbers.region_id', Auth::user()->region_id)
         ->get();
        //  $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      //   $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
      //  $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      //return view('business.trips2', compact('trips'));
 //dd($driver);
     $previous_week = strtotime("-1 week +1 day");
     $start_week = strtotime("last sunday midnight",$previous_week);
     $end_week = strtotime("next saturday",$start_week);
     $start_week = date("Y-m-d",$start_week);
     $end_week = date("Y-m-d",$end_week);
 
        foreach($users as $key => $name){
        $region  = Region::where('id', Auth::user()->region_id)->first();
        $tripAmt =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->sum('tripAmt');
        // dd($this->getSql($tripAmt));
         $tripCount =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->count();
         $tripAmtDay = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
          $tripAmtLastWeek = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripAmtLastWeekCount = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->count();
 
         $drivers->put($key,[
             'id' => $name->user_id,
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'name' => $name->name,
             'phone'=>$name->phone,
             'region' => $region->name,
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
        }
        // dd($drivers);
        $msg = "showing results between $from and $to";
    	return view('BM.driver_list',compact('drivers', 'msg'));
    //    return redirect()->back()->with(['driverss'=>$drivers,'msg'=>"showing results between $from and $to"]);
    }

    
    public function searchTrippold(Request $request)
    {
        $from = $request->first ?? '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to = $request->second. '11:59' ?? Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
        
        
        
        $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
        
        
       //  $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      /*
       $drivers = DB::table('driver_platenumbers')
            ->join('trips', 'driver_platenumbers.user_id', '=', 'trips.driverId')
             ->join('regions', 'driver_platenumbers.region_id', '=', 'regions.id')
            ->select('driver_platenumbers.*', 'trips.*','regions.*','trips.id as trip_id')->where(['trips.driverId' => $request->Auth::user()->platenumber])->whereNotNull('trips.tripAmt')->whereNull('trips.deleted_at')->get();
            
    */
           // $users = DriverPlatenumber::where('region_id',Auth::user()->region_id)->get();
         $drivers = collect([]);
         //$users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
         //$drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        
      //return view('business.trips2', compact('trips'));
 
     $previous_week = strtotime("-1 week +1 day");
     $start_week = strtotime("last sunday midnight",$previous_week);
     $end_week = strtotime("next saturday",$start_week);
     $start_week = date("Y-m-d",$start_week);
     $end_week = date("Y-m-d",$end_week);
 
        foreach($users as $key => $name){
  
        $tripAmt =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->sum('tripAmt');
         $tripCount =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->count();
         $tripAmtDay = Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount = Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
          $tripAmtLastWeek = Trip::where('driverId', $name->id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripAmtLastWeekCount = Trip::where('driverId', $name->id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->count();
 
         $drivers->put($key,[
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'name' => $name->name,
             'phone'=>$name->phone,
             'region' => $name->region->name,
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
       }
    //   dd($drivers);
       
    //    return redirect()->back()->with(['drivers'=>$drivers,'msg'=>"showing results between $from and $to"]);
}
    
public function getSql($builder) 
{
    $sql = $builder->toSql();
    foreach($builder->getBindings() as $binding)
    {
      $value = is_numeric($binding) ? $binding : "'".$binding."'";
      $sql = preg_replace('/\?/', $value, $sql, 1);
    }
    return $sql;
}

    public function regionDrivers(){
    	$users = DriverPlatenumber::where('region_id',Auth::user()->region_id)->get();
    	
    //	dd($users);

    	$from =   '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to =  Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
        
         $drivers = collect([]);
      
     $previous_week = strtotime("-1 week +1 day");
     $start_week = strtotime("last sunday midnight",$previous_week);
     $end_week = strtotime("next saturday",$start_week);
     $start_week = date("Y-m-d",$start_week);
     $end_week = date("Y-m-d",$end_week);
 
        foreach($users as $key => $name){
  
        $tripAmt =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->sum('tripAmt');
         $tripCount =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->count();
         $tripAmtDay = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
          $tripAmtLastWeek = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripAmtLastWeekCount = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->count();
 /*
         $drivers->put($key,[
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'name' => $name->user ? $name->user->name : null,
            'phone'=> $name->user ? $name->user->phone : null,
            'region' => $name->user ? $name->region->name: null,
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
       }
    //    $drivers = [];
    
    */
    if ($name->user) {
        $drivers->put($key,[
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'name' => $name->user->name,
             'phone'=>$name->user->phone,
             'region' => $name->region->name,
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
       }
}
    	return view('BM.driver_list',compact('drivers'));
    }

    public function allRegionDrivers(){
        
        $regions  = Region::all();
        $d_regions = collect([]);

        foreach($regions as $key => $region){
         $users = DriverPlatenumber::where('region_id',$region->id)->get();
            
        $tripAmt =0;
        $tripCount =0;
        $tripAmtDay =0;
        $tripDayCount =0;

        foreach($users as $key => $name){
        $tripAmt +=  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
         $tripCount +=  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->count();
         $tripAmtDay += Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount += Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
            }

              $d_regions->put($key,[
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'region' => $region->name,
         ]);
        }

       // dd($d_regions);
       
    	/* $users = DriverPlatenumber::all();
    	$from =   '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to =  Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
        
        $drivers = collect([]);
     
 
     $previous_week = strtotime("-1 week +1 day");
     $start_week = strtotime("last sunday midnight",$previous_week);
     $end_week = strtotime("next saturday",$start_week);
     $start_week = date("Y-m-d",$start_week);
     $end_week = date("Y-m-d",$end_week);
 
        foreach($users as $key => $name){
  
        $tripAmt =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->sum('tripAmt');
         $tripCount =  Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->count();
         $tripAmtDay = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripDayCount = Trip::where('driverId', $name->user_id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
          $tripAmtLastWeek = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $tripAmtLastWeekCount = Trip::where('driverId', $name->user_id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->count();
 
         $drivers->put($key,[
             'tripAmt'=>$tripAmt,
             'tripCount'=>$tripCount,
             'name' => $name->user->name,
             'phone'=>$name->user->phone,
             'region' => $name->region->name,
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
       }*/
    	return view('BM.all_driver_list',compact('d_regions'));
    }
}
