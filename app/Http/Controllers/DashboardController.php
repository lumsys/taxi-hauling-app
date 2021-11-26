<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use App\User;
use Auth;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class DashboardController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $dt = Carbon::now();
        
        $trip =   Trip::where('tripRequest','approved')->count();
        $business =   User::where('userType','business')->count();
        $driver =   User::where('userType','driver')->count();
         $totaltripAmt =  Trip::whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
         $totaltripAmtDay = Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $totaltripAmtMonth =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtMonthPrev =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m")-1)->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtYear =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
          $totaltripAmtWeek = Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(\DB::raw("WEEKOFYEAR(created_at)"), $dt->weekOfYear)->where('tripRequest','approved')->sum('tripAmt');

    	return view('dashboard.index',compact('trip','business','driver','totaltripAmt','totaltripAmtDay','totaltripAmtMonth','totaltripAmtMonthPrev','totaltripAmtYear','totaltripAmtWeek'));
    }

    public function getAllCompletedTrips()
    {
    	 $trips = Trip::whereNotNull('tripAmt')->paginate(5);
       $businesses = User::whereIn('userType', ['business', 'branch'])->get();
      //   dd($trips);
      //  return Datatables::of($trips)->make();
      
       return view('dashboard.trips', compact('trips','businesses'));
    }

     public function getAllCompletedTripsServer()
    {
       $trips = Trip::whereNotNull('tripAmt')->get();
      //   dd($trips);
      // return view('dashboard.trips', compact('trips'));
       return Datatables::of($trips)->make();
      // return response()->json($trips);
    }

    public function getAllUsers()
    {
      ini_set('max_execution_time',600);
      ini_set('memory_limit','-1');

    	$staffs = User::where('userType','staff')->get();
    	return view('dashboard.staff',compact('staffs'));
    }

     public function profile()
    {
    	$user = User::find(Auth::id());
    	return view('dashboard.profile', compact('user'));
    }
    public function setLargeData($id)
    {
      User::where('id',$id)
      ->update([
        'largeData'=>1
      ]);
      return redirect()->back();
    }

    public function removeLargeData($id)
    {
      User::where('id',$id)
      ->update([
        'largeData'=>0
      ]);
      return redirect()->back();
    }

    public function getAllBusinessAccount()
    {
    	$businesses = User::where('userType','business')->get();
    //	dd($businesses);
    	return view('dashboard.business', compact('businesses'));	
    }

    public function getBusinessTrips($company, $businesscode)
    {
    	$trips = Trip::where('business_code',$businesscode)->paginate(25);
    	return view('dashboard.business_trips', compact('trips'));
    }

     public function getBusinessStaffs($company, $businesscode)
    {
    	$staffs = User::where(['business_code'=>$businesscode, 'userType' =>'staff'])->paginate(25);
    	return view('dashboard.business_staff', compact('staffs'));
    }

    public function FunctionName(Request $request)
    {
      $from = $request->first ?? '0000000000';
      $from=date('Y-m-d H:i:s',strtotime($from));
      $to = $request->second ?? Carbon::now();
      
      $to=date('Y-m-d H:i:s',strtotime($to));

      $list= User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();

      foreach ($list as $item ) {
        $tripAmt =  Trip::where('driverId', $item->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->sum('tripAmt');
        $tripCount =  Trip::where('driverId', $item->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->whereBetween('created_at', [$from, $to])->count();
        $tripAmtDay = Trip::where('driverId', $item->id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->sum('tripAmt');
        $tripDayCount = Trip::where('driverId', $item->id)->whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['tripRequest' => 'approved'])->count();
        $tripAmtLastWeek = Trip::where('driverId', $item->id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->sum('tripAmt');
        $tripAmtLastWeekCount = Trip::where('driverId', $item->id)->whereBetween('created_at', [$start_week, $end_week])->whereNotNull('tripAmt')->where(['tripRequest' => 'approved'])->count();

        $drivers->put($key,[
            'tripAmt'=>$tripAmt,
            'tripCount'=>$tripCount,
            'name' => $name->name,
            'phone'=>$name->phone,
            'tripAmtDay'=>$tripAmtDay,
            'tripDayCount'=>$tripDayCount,
            'tripAmtLastWeek'=> $tripAmtLastWeek,
            'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
        ]);
      }

    }

     public function searchTrip(Request $request)
    {
        $from = $request->first ?? '0000000000';
        $from=date('Y-m-d H:i:s',strtotime($from));
        $to = $request->second. '11:59' ?? Carbon::now();
        $to=date('Y-m-d H:i:s',strtotime($to));
        
         $drivers = collect([]);
         $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
         $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
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
             'tripAmtDay'=>$tripAmtDay,
             'tripDayCount'=>$tripDayCount,
             'tripAmtLastWeek'=> $tripAmtLastWeek,
             'tripAmtLastWeekCount'=> $tripAmtLastWeekCount
         ]);
       }
       //dd($drivers);

       return redirect()->back()->with(['drivers'=>$drivers,'msg'=>"showing results between $from and $to"]);
}

}