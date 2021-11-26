<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Documents;
use App\Trip;
use App\DriverRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Config;
use App\TrackerTrip;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exporter;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TripsViewExport;
use App\Exports\TripsExport;
use App\Exports\Trips2Export;
use Rap2hpoutre\FastExcel\FastExcel;
//use App\DB;

class BusinessController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth')->except(['postStaff','staffRegister','postStaffRegister']);

    //    $this->middleware('log')->only('index');

     //   $this->middleware('subscribed')->except('store');
    }

    public function index()
    {
        $dt = Carbon::now();

         $tripCount =  Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->count();
          $userCount =  User::where(['business_code' => Auth::user()->unique_code, 'userType' => 'staff'])->count();
        $totaltripAmt =  Trip::whereNotNull('tripAmt')->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
         $totaltripAmtDay =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
          $totaltripAmtMonth =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
           $totaltripAmtMonthPrev =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m")-1)->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
           $totaltripAmtYear =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
            $totaltripAmtWeek = Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(\DB::raw("WEEKOFYEAR(created_at)"), $dt->weekOfYear)->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');

            $branches = User::where(['userType' => 'branch','business_code' => Auth::user()->unique_code])->get();
            $totaltripbranch =0;
            $totaltripbranchmonth =0;
            $totaltripbranchday =0;
            $totaltrackertripbranchamountmonth = 0;
            $totaltrackertripbranchamountday = 0;
            foreach($branches as $key => $branch){
               $totaltripbranch = $totaltripbranch +$this->totalTripBranch($branch->unique_code);
               $totaltripbranchmonth=$totaltripbranchmonth + $this->totalTripBranchAmountMonth($branch->unique_code);
               $totaltripbranchday = $totaltripbranchday + $this->totalTripBranchAmountDay($branch->unique_code);
               $totaltrackertripbranchamountmonth = $totaltrackertripbranchamountmonth + $this->totalTrackerTripBranchAmountMonth($branch->unique_code);
              $totaltrackertripbranchamountday = $totaltrackertripbranchamountday + $this->totalTrackerTripBranchAmountDay($branch->unique_code);
            }
            
      //  dd($totaltripAmtMonthPrev, $totaltripAmtDay, $totaltripAmtMonth,$totaltripAmtYear, $totaltripAmt );
        return view('business.index', compact('tripCount','userCount','totaltripAmtDay','totaltripAmtMonth','totaltripAmtYear','totaltripAmt', 'totaltripAmtMonthPrev','totaltripAmtWeek','totaltripbranch','totaltripbranchmonth','totaltripbranchday','totaltrackertripbranchamountmonth','totaltrackertripbranchamountday'));
    }

    public function profile()
    {
        $user = User::find(Auth::id());
        return view('business.profile', compact('user'));
    }

    public function getStaff()
    {
        $staffs = User::where('business_code', Auth::user()->unique_code)->where('userType','staff')->paginate(10);
        $businesses = User::where(['business_code'=> Auth::user()->unique_code,'userType'=>'branch'])->get();
        return view('business.staff', compact('staffs','businesses'));
    }
//staff registration by admin
     public function postStaff(Request $request)
    {
        $formInput=$request->except('password');
//        validation
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required',
            'email' =>'unique:users|required|email',
            'phone'=>'required'
        ]);
//        image upload 
      //  $image=$request->profileUrl;
        $formInput['password'] = bcrypt($request->password);
        $formInput['userType'] = 'staff'; 
        $formInput['business_code'] = $request->business_code;
        //$formInput['business_code'] = Auth::user()->unique_code;
        $formInput['company'] = $this->extractDomain($request->email);
        $formInput['phone'] = preg_replace('/^0/','+234',$request->phone);

        $email = $request->email;
        $name = $request->name;
        $password = $request->password;

//dd($formInput['company']);
       /* if($image){
            $imageName=$image->getClientOriginalName();
            $image->move('images',$imageName);
            $formInput['profileUrl']=$imageName;
        } */
        $audit = Auth::user();
       $config = Config::where('unique_code',$request->business_code)->first();
       if(!$config){
        return redirect()->back()->with('message','This account doesnot have configuration, contact support');
       }
      
        User::create($formInput);
        $audit->log("created staff");
        try {
         Mail::send('emails.staffregistration',  [
            'email' => $email, 
            'name' => $name,
            'password' => $password 
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Welcome to SmoothRide');
            });
         }catch(\Exception $e){
                     
            }
         $msg = 'download smoothride app, and login with email:'.$email.','.'password:'.$password.'';
          $this->sendSmsMessage($formInput['phone'],$msg);

        return redirect()->back()->with('message','Staff added successfully');
    }

    function extractDomain($email)
    {
        //$email = 'user@example.com';
        //return substr($email, strpos($email, '@') + 1);
        $result = substr(strrchr($email, "@"), 1);
        $arr = explode(".",$result);
        return $arr[0];

        //   echo "Today is " . date('l', mktime());
    }

     public function staffRegister()
    {
        return view('staff.register');
    }

    //direct registration for staff through browser
     public function postStaffRegister(Request $request)
    {
        $audit = Auth::user();
        $formInput=$request->except('password');
//        validation
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required',
            'email' =>'required|email',
            'phone'=>'required'
        ]);
 
        $formInput['password'] = bcrypt($request->password);
        $formInput['phone'] = preg_replace('/^0/','+234',$request->phone);
        $formInput['userType'] = 'staff'; 
        $formInput['company'] = $this->extractDomain($request->email);
 
        User::create($formInput);
        $audit->log("created staff");
        return redirect()->route('login')->with('message','Staff added successfully');
    }

     public function staffRequests()
    {
        $requests = Trip::where('staffId',Auth::id())->get();
        return view('staff.request',compact('requests'));
    }

    public function indStaffTrips()
    {
      $trips = Trip::where('staffId',Auth::id())->whereNotNull('pickUpAddress')->get();
        return view('staff.stafftrip', compact('trips'));
    }

     public function deleteStaff($id) 
    {
        $audit = Auth::user();
       $user = User::find($id);
       $user->delete();
       $audit->log("created staff");
       return back()->with('message','Deleted');
        
    }

    public function businessBranchDashboard($business_code)
    {
        $dt = Carbon::now();
         $user =  User::where(['unique_code' => $business_code, 'userType' => 'branch'])->first();
         $tripCount =  Trip::where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->count();
          $userCount =  User::where(['business_code' => $business_code, 'userType' => 'staff'])->count();
        $totaltripAmt =  Trip::whereNotNull('tripAmt')->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
         $totaltripAmtDay =  Trip::whereNotNull('tripAmt')->whereDay('created_at', date("d"))->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
          $totaltripAmtMonth =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m"))->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
           $totaltripAmtMonthPrev =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m")-1)->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
           $totaltripAmtYear =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
            $totaltripAmtWeek = Trip::whereNotNull('tripAmt')->where(\DB::raw("WEEKOFYEAR(created_at)"), $dt->weekOfYear)->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');

        return view('business.branchdashboard', compact('tripCount','userCount','totaltripAmtDay','totaltripAmtMonth','totaltripAmtYear','totaltripAmt', 'totaltripAmtMonthPrev','totaltripAmtWeek','user'));
    }

     public function businessBranchTrips($business_code)
    {
        
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
        
         $trips =  Trip::where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        return view('business.branchtrips', compact('trips','business_code'));
    }

     public function searchBranchTrip(Request $request,$business_code)
    {
       $from = $request->first ?? Carbon::now();
       $to = $request->second ?? Carbon::now();
       $trips =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => $business_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      return view('business.branchtrips', compact('trips','business_code'));
    }

     public function getBranchStaff($business_code)
    {
        $staffs = User::where('business_code', $business_code)->where('userType','staff')->paginate(10);
     
        return view('business.branchstaff', compact('staffs'));
    }

     public function getBranchDriverRequest()
    {
        $driverrequests = DriverRequest::where(['business_code' => Auth::user()->unique_code, 'status' => 'true',  'userType' => 'driver'])->paginate(10);
    // dd($driverrequests);
        return view('business.driverrequest', compact('driverrequests'));
    }

    public function approveDriverRequest($id)
    {
       $d_request = DriverRequest::where('id', $id)->first();

       if(!is_null($d_request))
       {
            DriverRequest::where('id', $id)->update(['status' => 'false']);
            User::where('id',$d_request->user_id)->update(['isAvailable' =>'offline']);
            $this->sendSmsMessage($d_request->driver->phone,'Congrats, you are offline now, kindly remember to come online');
       }

        return redirect()->back()->with('message','Approved successfully');
    }

     public function declineDriverRequest($id)
    {
       $d_request = DriverRequest::where('id', $id)->first();

       if(!is_null($d_request))
       {
            DriverRequest::where('id', $id)->update(['status' => 'false']);
           // User::where('id',$d_request->user_id)->update(['isAvailable' =>'offline']);
            $this->sendSmsMessage($d_request->driver->phone,'Whao, your request was declined, contact the admin');
       }

        return redirect()->back()->with('message','Declined successfully');
    }

    public function autoApproveBusiness(Request $request)
    {
        User::where('id',Auth::id())->update(['autoApprove' => $request->autoApprove]);
        return redirect()->back()->with('message','Updated successfully');
    }

     function sendSmsMessage($to,$message)
    {
       // $to = '08066289557';
      //  $message = 'You are doing good';

          $headers = [
            'Content-Type' => 'application/json',
            'apiKey' => '53f177068392eb223a0e480d44f86c78330a1bb5a611f64fd6a0cc625e950c98',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
    $response = $client->request('POST', 'https://api.africastalking.com/version1/messaging', [
        'form_params' => [
             "username" => 'smoothride',
             "to" => $to,
             "message" => $message,
             "from" => 'SmoothRide',
        ]
    ]);

    $response = $response->getBody()->getContents();

    //echo '<pre>'; $response->AfricasTalkingResponse->SMSMessageData->Recipients['status']
   // print_r($response);
    }

    function totalTripBranch($unique_code){
        return Trip::whereNotNull('tripAmt')->where(['business_code' => $unique_code, 'tripRequest' => 'approved', 'flag' => 0])->count();
    }

     function totalTripBranchAmountMonth($unique_code){
        return  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
    }

     function totalTripBranchAmountDay($unique_code){
        return Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved', 'flag' => 0])->sum('tripAmt');
    }

    function totalTrackerTripBranchAmountMonth($unique_code){
        return TrackerTrip::where(['business_code' => $unique_code])->whereYear('date',date("Y"))->whereMonth('date',date("m"))->sum('amount');
    }

    function totalTrackerTripBranchAmountDay($unique_code){
        return TrackerTrip::where(['business_code' => $unique_code])->whereYear('date',date("Y"))->whereMonth('date',date("m"))->whereDay('date', date("d"))->sum('amount');
    }
    
    function tripsWithBranchF($unique_code){
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
        
      return Trip::whereNotNull('tripAmt')->where(['business_code' =>  $unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();

    }

    function nestedToSingle(array $array){
    $singleDimArray = [];

    foreach ($array as $item) {

        if (is_array($item)) {
            $singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));

        } else {
            $singleDimArray[] = $item;
        }
    }

    return $singleDimArray;
}



 function tripsWithBranchFLimited($unique_code){
        
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
     
        $last=strtotime("now");
        $first=strtotime("-1 month",$last);
        $first=date("Y-m-d H:i:s",$first);
        $last=date("Y-m-d H:i:s",$last);
        // dd($first);
        return Trip::whereNotNull('tripAmt')->where(['business_code' =>  $unique_code, 'tripRequest' => 'approved', 'flag' => 0])
        ->whereBetween('trips.created_at', [$first, $last])
        ->get();
    }


    function tripsWithBranchHQ(){
        
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
        
     // return Trip::where('business_code', Auth::user()->unique_code)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved','flag' => 0])->get();
       return Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
        
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
       // return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, 
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page'
            ]
        );
    }
    
    
    public function tripsWithBranch(Request $request)
    {  
        ini_set('max_execution_time',600);
        //ini_set('memory_limit','4096');
       ini_set('memory_limit','-1');
        
       //$alltrips = collect();
        $alltrips = array();
        $users=  User::where(['userType' => 'branch','business_code'=> Auth::user()->unique_code ])->get();
    // $alltrips->push($this->tripsWithBranchHQ());
        foreach($users as $key => $name){
          //  dd($this->tripsWithBranchF($name->unique_code));
        //  $alltrips->push(...$this->tripsWithBranchF($name->unique_code));
         array_push($alltrips,...$this->tripsWithBranchF($name->unique_code));
        }
     array_push($alltrips,...$this->tripsWithBranchHQ());
      $alltrips = $this->paginate($alltrips);
      
      return view('business.businessbranchtrips',compact('alltrips'));
        
     
    }
    
   
  
   /*
    public function tripsWithBranch(Request $request)
    {  

        ini_set('max_execution_time',600);
        //ini_set('memory_limit','4096');
        ini_set('memory_limit','-1');
        
      $alltrips = array();
        $users=  User::where(['userType' => 'branch','business_code'=> Auth::user()->unique_code ])->get();
        foreach($users as $key => $name){
            if (Auth::user()->largeData == 1) {
                array_push($alltrips,...$this->tripsWithBranchFLimited($name->unique_code));
            }else{
                array_push($alltrips,...$this->tripsWithBranchF($name->unique_code));
            }
          
        }
     //array_push($alltrips,...$this->tripsWithBranchHQ());
      
      $alltrips = $this->paginate($alltrips);
      
      return view('business.businessbranchtrips',compact('alltrips'));
        
     
    }
   */
  public function indexe()
  {
      $file=Documents::all();
      return view('business.businessbranchtrips',compact('file'));
  }

    public function tripwithbranchfilter($file)
    {
        return response()->download('public/'.$file);
    
        /*
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');
        
        $this->validate($request,[
            'year'=>'required',
            'month'=>'required',
        ]);
        $first=strtotime("00:00 First day of $request->month $request->year");
        //echo $first;
        
        $last=strtotime("11:59:59 Last day of $request->month $request->year");
        $first=date("Y-m-d H:i:s",$first);
        $last=date("Y-m-d H:i:s",$last);
        $alltrips = array();
        $users=  User::where(['userType' => 'branch','business_code'=> Auth::user()->unique_code ])->get();
        foreach($users as $key => $name){
            
            $trips=Trip::leftJoin('users as u1','u1.id','=','trips.staffId')
            ->leftJoin('users as u2','u2.id','=','trips.driverId')
            ->leftJoin('users as u3', function ($join) {
                $join->on('u3.unique_code', '=', 'trips.business_code')
                     ->where('u3.usertype', '=', "branch");
            })
            ->select('u1.name as staff_name','pickUpAddress','destAddress','tripAmt','tripDist','travelTime','trip_start_time','tripEndTime','wait_time_start','wait_time_end','wait_time','cost_wait','u2.name as driver_name','u3.name')
            ->whereNotNull('tripAmt')->where(['trips.business_code' =>  $name->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->whereBetween('trips.created_at', [$first, $last])->get();
            
            
            array_push($alltrips,...$trips);
        }
        $alltrips = Collection::make($alltrips);
        
        return Excel::create('smoothride_'.$request->month, function($excel) use ($alltrips) {
            $excel->sheet('mySheet', function($sheet) use ($alltrips)
            {
                $sheet->fromArray($alltrips, null, 'A1', false, false);
                
                $headings = array('Staff Name', 'Pickup address',   'Drop address', 'Amount',   'Distance', 'Time', 'Start Time',   'End Time', 'Wait Start Time',  'Wait End Time',    'Wait Time',    'Cost of Waiting',  'Driver','Branch');

                $sheet->prependRow(1, $headings);
            });
        })->download('xlsx');
            */
    }

     public function searchBranchBusinessTrip(Request $request)
    {
        ini_set('max_execution_time',600);
        ini_set('memory_limit','-1');

    //  $from = $request->first ?? Carbon::now();
    //   $to = $request->second ?? Carbon::now();
     //  session(['from' => $from, 'to' => $to]);

       $alltrips = array();
       $users=  User::where(['userType' => 'branch','business_code'=> Auth::user()->unique_code ])->get();

       //$parent_trips = Trip::whereBetween('created_at', [$from, $to])->where(['parent_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0]);
       $parent_trips = DB::table('trip')
       ->whereBetween('id', [79493, 79733])
       ->where(['parent_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])
       ->get();
     //  dd($parent_trips);
        //$Hqtrips = Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        $Hqtrips = DB::table('trip')
       ->whereBetween('id', [79493, 79733])
       ->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])
       ->get();
      foreach($users as $key => $name){
       //$trips = Trip::whereBetween('created_at', [$from, $to])->whereNotNull('tripAmt')->where(['business_code' => $name->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
       $trips = Trip::whereBetween('id', [79493, 79733])->whereNotNull('tripAmt')->where(['business_code' => $name->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        if(count($trips) >0){
           //  $alltrips->push($trips);
           array_push($alltrips,...$trips);
            };
      }

       $fileName = 'smoothride'.Carbon::now() . '.' . 'xlsx';
      return (new TripsExport($parent_trips))->download($fileName);
      //export to excel
  //   Exporter::make('Excel')->load(Trip::all())->stream($fileName);
     /* $data = Trip::all();
     // return 1;
        Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->export('xlsx');*/
        // return (new FastExcel($alltrips))->download('tripsmoothride.xlsx');
       // return  Excel::download(new TripsViewExport($alltrips), 'trips.xlsx');
       // return (new Trips2Export())->download('trips.xlsx');
       
       $alltrips = $this->paginate($alltrips);

     //   return count($alltrips);
        return view('business.businessbranchtrips2',compact('alltrips'));
    }

    public function exportTrips(Request $request, $type)
    {
       $from = $request->session()->get('from');
       $to = $request->session()->get('to');

       $alltrips = array();
       $users=  User::where(['userType' => 'branch','business_code'=> Auth::user()->unique_code ])->get();

        //$Hqtrips = Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        $Hqtrips = Trip::whereBetween('id', [79493, 79733])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      foreach($users as $key => $name){
       //$trips = Trip::whereBetween('created_at', [$from, $to])->whereNotNull('tripAmt')->where(['business_code' => $name->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
       $trips = Trip::whereBetween('id', [79493, 79733])->whereNotNull('tripAmt')->where(['business_code' => $name->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
        if(count($trips) >0){
           //  $alltrips->push($trips);
           array_push($alltrips,...$trips);
            };
      }
      array_push($alltrips,...$Hqtrips);

        return  Excel::download(new TripsViewExport($alltrips), 'trips.'.$type);
    //  return (new FastExcel($alltrips))->download('tripsmoothride.xlsx');
    }
}