<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Trip;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\DriverRequest;
use App\Config;

class BranchController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
  
    	$branches = User::where(['business_code' => Auth::user()->unique_code, 'userType' => 'branch'])->get();

    	return view('business.branch.index',compact('branches'));
    }

     public function dashboard()
    {
      $dt = Carbon::now();
        $tripCount =Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->count();
        $userCount = User::where(['unique_code' => Auth::user()->unique_code, 'userType' => 'staff'])->count();
         $totaltripAmt =  Trip::whereNotNull('tripAmt')->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
         $totaltripAmtDay =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->whereDay('created_at', date("d"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
          $totaltripAmtMonth =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtMonthPrev =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m")-1)->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtYear =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtWeek = Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->whereMonth('created_at',date("m"))->where(\DB::raw("WEEKOFYEAR(created_at)"), $dt->weekOfYear)->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');

    	return view('business.branch.dashboard',compact('tripCount','userCount','totaltripAmtDay','totaltripAmtMonth','totaltripAmtYear','totaltripAmt', 'totaltripAmtMonthPrev','totaltripAmtWeek'));
    }

     public function store(Request $request)
    {
        $audit = Auth::user();
        $formInput=$request->except('password');
//        validation
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required',
            'email' =>'unique:users|required|email',
            'phone'=>'required'
        ]);

        $formInput['password'] = bcrypt($request->password);
        $formInput['userType'] = 'branch'; 
        $formInput['unique_code'] = str_random(6);
        $formInput['business_code'] = Auth::user()->unique_code;
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

       $config = Config::where('unique_code',Auth::user()->unique_code)->first();
       
        User::create($formInput);

        Config::create([
            'basefare'=> $config->basefare,
            'perkm'=>$config->perkm,
            'permin'=>$config->permin,
            'unique_code'=> $formInput['unique_code'],
        ]);

      try{
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

            
            $audit->log("created branch");
        return redirect()->back()->with('message','Branch added successfully');
    }

     public function getStaff()
    {
        $staffs = User::where('business_code', Auth::user()->unique_code)->where('userType','staff')->paginate(10);
     
        return view('business.branch.staff', compact('staffs'));
    }
//staff registration by admin
     public function postStaff(Request $request)
    {
        $audit = Auth::user();
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
        $formInput['business_code'] = Auth::user()->unique_code;
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

        $config = Config::where('unique_code',Auth::user()->unique_code)->first();
       if(!$config){
        return redirect()->back()->with('message','This account doesnot have configuration, contact support');
       }

        User::create($formInput);
        
        $audit->log("created Data");
         Mail::send('emails.staffregistration',  [
            'email' => $email, 
            'name' => $name,
            'password' => $password 
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Welcome to SmoothRide');
            });
          $msg = 'download smoothride app, and login with email:'.$email.','.'password:'.$password.'';
          $this->sendSmsMessage($formInput['phone'],$msg);
        return redirect()->back()->with('message','Staff added successfully');
    }

    public function branchTrips()
    {
         $trips =  Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->paginate(10);
          
         return view('business.branch.trips', compact('trips'));
    }

     public function branchTripRequest()
    {
        $triprequests =  Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'pending'])->paginate(10);

         $driver_assigned =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code])->get();

         $driver_onlines =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->whereNull('currTripState')->get();
          $driver_offlines =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'offline'])->whereNull('currTripState')->get();
          $driver_ontrips =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->where('currTripState','=','ontrip')->get();
          
        return view('business.branch.triprequest',compact('triprequests','driver_onlines','driver_offlines','driver_ontrips','driver_assigned'));
    }

     public function searchTrip(Request $request)
    {
       $from = $request->first ?? Carbon::now();
       $to = $request->second ?? Carbon::now();
       $trips =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved'])->get();
      return view('business.branch.trips', compact('trips'));
    }

      public function approveTripRequest($id) 
    {
        
    $driver =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->whereNull('currTripState')->orderBy('updated_at')->first();
   // dd($driver);
       if(is_null($driver))
       {
        return back()->with('message','No Driver Available');
       }else {

        Trip::where('id',$id)->update(['tripRequest' =>'approved','driverId' => $driver->id]);

        User::where('id',$driver->id)->update(['currTripState'=>'assign']);
       $trip = Trip::find($id);
       
      
    $this->sendSmsMessage($driver->phone,'A Trip has been assigned to you, kindly check your app');
     $this->sendSmsMessage($trip->user->phone,'Your trip request approved, kindly check your app');
    $this->initiatePushToken($trip->user->pushToken);
    $this->initiatePushToken($driver->pushToken); 
       return back()->with('message','Trip approved');
       }
        
        
    }

     public function declineTripRequest($id) 
    {
       $trip = Trip::find($id);
        $trip->delete();
       return back()->with('message','Deleted');
        
    }
     public function getBranchDriverRequest()
    {
        $driverrequests = DriverRequest::where(['business_code' => Auth::user()->unique_code, 'status' => 'true',  'userType' => 'driver'])->paginate(10);
    //dd($driverrequests);
        return view('business.branch.driverrequest', compact('driverrequests'));
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

     public function initiatePushToken($pushToken){
    $isError = 0;
    $errorMessage = true;

    //Preparing post parameters
    $postData = array(
       'to' => $pushToken,
    'notification' => array(
        'body' => 'New Assigned Trip',
        'title' => 'New Trip',
        'content_available' => true,
        'priority' => 'high'
    ),
    'data' => array(
        'body' => 'New Assigned Trip',
        'title' => 'New Trip',
        'content_available' => true,
        'priority'=> 'high'
    )
    );
//dd($postData, $pushToken);
    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));

$headers = [
     'Content-Type: application/json',
    'Authorization: key=AAAAlhbREWE:APA91bFgTZc54SVTTFtGxW1nDK2eIDdUwYGV3mQItNq1kWgkESdVJBdUsamxVwHI_Pq1Xqvl8wt6ZguCCFK37f1xo8Po6JjJb0fSzS4GxSB25fH2ktJp8m9RYcHSqnKhPyRRMxtCUNxD'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //Ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    //get response
    $output = curl_exec($ch);
   
    //Print error if any
    if (curl_errno($ch)) {
        $isError = true;
        $errorMessage = curl_error($ch);
    }
    curl_close($ch);


 /*   if($isError){
        return array('error' => 1 , 'message' => $errorMessage);
    }else{
        return array('error' => 0 );
    }*/
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


    function extractDomain($email)
    {
    	//$email = 'user@example.com';
		//return substr($email, strpos($email, '@') + 1);
		$result = substr(strrchr($email, "@"), 1);
		$arr = explode(".",$result);
		return $arr[0];

   		//	 echo "Today is " . date('l', mktime());
	}
    public function editB($unique_code)
    {
        //$branch = Config::where('unique_code',Auth::user()->unique_code)->first();
        //check to ensure data is being queried by parent business
        $exists = User::where(['business_code' => Auth::user()->unique_code, 'userType' => 'branch','unique_code'=>$unique_code])->count();
        if ($exists > 0) {
            $branch = User::where(['business_code' => Auth::user()->unique_code, 'userType' => 'branch','unique_code'=>$unique_code])->first();
            return view('business.branch.editB',compact('branch'));
        }else{
            return redirect()->route('branch.index');
        }

    }
    public function posteditB(Request $request, $id)
    {
        $audit = Auth::user();
        //$branch= Config::find('unique_code',Auth::user()->unique_code);
        // $config = Config::where('unique_code',Auth::user()->unique_code)->findOrFail($id);
          //$formInput=$request->except('password');
//        validation
        $this->validate($request,[
            'name'=>'required',
            'email' =>'required|email|unique:users,email,'.$id,
            'phone'=>'required'
        ]);
        $email = $request->email;
        $name = $request->name;
        $phone = $request->phone;
        
        // check to ensure this is being updated by the oarent business
        $exists = User::where(['business_code' => Auth::user()->unique_code, 'userType' => 'branch','id'=>$id])->count();
        if ($exists > 0) {
            $branch = User::find($id);
            $branch->name = $name;
            $branch->email = $email;
            $branch->phone = $phone;
            $branch->save();

            $audit->log("Edited branch");
            return redirect('/branch-index')->with('message','Branch edited successfully');
        }else{
            return redirect()->route('branch.index');
        }
            
    }

}
