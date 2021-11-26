<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = User::where('userType', 'driver')->whereNull('deleted_at')->get();
        $businesses = User::whereIn('userType', ['business', 'branch'])->get();
        foreach ($drivers as $driver ) {
            $driver->branchname = User::where([
                'userType'=>'branch',
                'unique_code'=>$driver->business_code,
            ])->pluck('name')->first();
        }
        // dd($drivers[1]);
        return view('driver.index', compact('drivers', 'businesses'));
    }

    public function indexx(){
        return view('dashboard.create');
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**0
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $audit = Auth::user();
        $formInput=$request->except(['profileUrl','password']);
//        validation
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required',
            'email' =>'unique:users|required|email',
            'phone'=>'required'
        ]);

//        image upload 
        $image=$request->profileUrl;
        $formInput['password'] = bcrypt($request->password);
        $formInput['userType'] = 'driver';
        $formInput['isAvailable'] = 'online';
        $formInput['phone'] = preg_replace('/^0/','+234',$request->phone);
        $formInput['name']  = $request->name;

        if($image){
            $imageName=$image->getClientOriginalName();
            $image->move('images',$imageName);
            $formInput['profileUrl']=$imageName;
        }
        User::create($formInput);
        $email = $request->email;
        $password = $request->password;
        $audit->log("Created Driver");

        try {
          Mail::send('emails.driverregistration',  [
            'email' => $email, 
            'name' => $request->name,
            'password' => $request->password 
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Welcome to SmoothRide');
            }); 
          }catch(\Exception $e){
                     
            }
             $msg = 'download smoothride app, and login with email:'.$email.','.'password:'.$password.'';
          $this->sendSmsMessage($formInput['phone'],$msg);

        return redirect()->back()->with('message','Driver added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *list
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $get_user = User::where('id',$id)->first();
        $user = User::where('unique_code',$request->business_code)->first();

          $updated = $get_user->fill(['business_code' => $request->business_code,'company' => $user->company])->save();
          if ($updated){
          
            return redirect()->back()->with('message','Driver assigned successfully');
        }
        else {
            return redirect()->back()->with('message','Driver not assigned');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }

    public function updateDriver(Request $request, $id)
    {
        $audit = Auth::user();
          $get_user = User::where('id',$id)->first();
        $user = User::where('unique_code',$request->unique_code)->first();
          $updated = $get_user->fill(['business_code' => $request->unique_code,'company' => $user->company])->save();
          
          if ($updated){
            $audit->log("Updated Data");
            return redirect()->back()->with('message','Driver assigned successfully');
        }
        else {
            return redirect()->back()->with('message','Driver not assigned');
        }
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

    public function CountDriver(){
        $drivers = User::where(['userType' => 'driver', 'isAvailable' => 'online'])->whereNull('deleted_at')->get();
       
      //  $businesses = User::whereIn('userType', ['business', 'branch'])->get();
        //dd($businesses);
        foreach ($drivers as $driver ) {
            $driver->branchname = User::where([
                'userType'=>'branch',
                'unique_code'=>$driver->business_code,
            ])->pluck('name')->first();
        }
        //dd($drivers[0]);
        return view('driver.indd', compact('drivers'));
    }



   

    public function listDriverWithAmountCount(){

        $from = $request->first ?? Carbon::now();
       $to = $request->second ?? Carbon::now();
        $drivers = collect([]);
        $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
      // $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
     //return view('business.trips2', compact('trips'));
//dd($users);


    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);

       foreach($users as $key => $name){

       $tripAmt =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
        $tripCount =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->count();
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
    //  dd($drivers);
      return view('dashboard.drivers_list',compact('drivers'));
    }

    public function createe()
    {
        return view('dashboard.uploadlist');
    } 
    public function storee(Request $request)
    {
        return $request;
    } 

public function filterDate(Request $request)
    {
       $from = $request->first ?? Carbon::now();
       $to = $request->second ?? Carbon::now();
        $drivers = collect([]);
        $users=  User::where(['userType' => 'driver', 'isAvailable' => 'online' ])->get();
       $drivers =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();

       $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);

       foreach($users as $key => $name){

       $tripAmt =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->sum('tripAmt');
        $tripCount =  Trip::where('driverId', $name->id)->whereNotNull('tripAmt')->where([ 'tripRequest' => 'approved'])->count();
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
      return view('dashboard.drivers_list',compact('drivers'));
        }
    }

public function deleteDriver($id)
    {
        $audit = Auth::user();
        $user = User::find($id);
        $user->delete();
        $audit->log("Delete driver");
       return back()->with('message','Deleted');
    }

    public function editDriver($id)
    {
        
        //$audit = Auth::user();
        $driver = User::where('id',$id)->first();
        //$audit->log("Edited driver");
        return view('driver.edit',compact('driver'));

    //return view('driver.edit',array($driver->'id'));
        //return back()->with('message','Edited');
//return 'id';

    //return View('driver.editDriver','id'->$driver);

    }

    public function postedit(Request $request, $id)
    {
        $audit = Auth::user();
        $user=user::find($id);
        $this->validate($request,[
            'name'=>'required',
            'email' =>'required|email|unique:users,email'.",$id",
            'phone'=>'required'
        ]);
        $name=request('name');
        $email=request('email');
        $phone= preg_replace('/^0/','+234',request('phone'));
        $image=request('profileUrl');
        
        
        if($image){
            $imageName=$image->getClientOriginalName();
            $image->move('images',$imageName);
            $image=$imageName;
            DB::table('users')
                ->where('id',$id)
                ->update([
                    'profileUrl'=>$image,
                ]);
        }
        DB::table('users')
                ->where('id',$id)
                ->update([
                    'name'=>$name,
                    'email'=>$email,
                    'phone'=>$phone,
                ]);
        $audit->log("Edit driver");
        return redirect('/driver')->with('success','Driver edited successfully');
    }
       

 public function logger()
 {  
    $today = Carbon::now()->toDateString();
     $drivers=user::whereRaw("userType='driver' and currTripState='assign'")->get();
     foreach ($drivers as $driver ) {
         DB::table('log_drivers_online')->insert([
             'date'=>$today,
             'user_id'=>$driver->id,
         ]);
     }
 }










}
