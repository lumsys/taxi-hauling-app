<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Trip;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;

class TripController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth')->except(['sendSmsMessage','initiatePushToken']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $trips =  Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->paginate(30);
        //dd($trips);
         return view('business.trips', compact('trips'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     public function searchTrip(Request $request)
    {
       $from = $request->first ?? Carbon::now();
       $to = $request->second ?? Carbon::now();
       $trips =  Trip::whereBetween('created_at', [$from, $to])->where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'approved', 'flag' => 0])->get();
      return view('business.trips2', compact('trips'));
    }

    public function allTrips()
    {

        $triprequests =  Trip::where(['business_code' => Auth::user()->unique_code, 'tripRequest' => 'pending', 'flag' => 0])->paginate(10);

        $driver_assigned =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code])->get();

         $driver_onlines =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->whereNull('currTripState')->get();
          $driver_offlines =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'offline'])->whereNull('currTripState')->get();
          $driver_ontrips =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->where('currTripState','=','ontrip')->get();
        return view('business.alltriprequest',compact('triprequests','driver_onlines','driver_offlines','driver_ontrips','driver_assigned'));
    }

     public function approveTripRequest($id) 
    {
       /* $driver =  User::where(['userType' => 'driver', 'company' => Auth::user()->company,'isAvailable' => 'online'])->where('currTripState',null)->orwhere('currTripState','!=','ontrip')->inRandomOrder()->first(); */
    
    $driver =  User::where(['userType' => 'driver', 'business_code' => Auth::user()->unique_code,'isAvailable' => 'online'])->whereNull('currTripState')->orderBy('updated_at')->first();
   // dd($driver);
       if(is_null($driver))
       {
        return back()->with('message','No Driver Available');
       }else {
        $trip = Trip::find($id);
        if(is_null($trip->driverId)){
        Trip::where('id',$id)->update(['tripRequest' =>'approved','driverId' => $driver->id, 'driverName' => $this->userIdToName($driver->id)]);

        User::where('id',$driver->id)->update(['currTripState'=>'assign']);
     //  $trip = Trip::find($id);
       
      
    $this->sendSmsMessage($driver->phone,'A Trip has been assigned to you, kindly check your app');
     $this->sendSmsMessage($trip->user->phone,'Your trip request was approved, kindly check your app');
    $this->initiatePushToken($trip->user->pushToken);
    $this->initiatePushToken($driver->pushToken); 
       return back()->with('message','Trip approved');
   }else{
         return back()->with('message','Trip has been approved before');
             }
       }
   }
        

     public function declineTripRequest($id) 
    {
       $trip = Trip::find($id);
        $trip->delete();
       return back()->with('message','Deleted');
        
    }
/* Authorization: key=AAAAMuOwMjs:APA91bEEDyGUa-K_V2q0WINe6HymBNDwMLzArlvWV-nx7xx7L1d9tKgC8oOA-ToCOAKaukn6-2XG5QAjP1rDDkqIV4klEFes3oeSOHb6U-cheoqmGywczpaFOI8epfm0U1YAzrUkDuiw

content-Type: application/json


{
    "to" : "ez3IQINggeQ:APA91bGCx3GnNAaVZpe_v-La1ki1A6KuL4MwRk9xv4DEtieZwZo3Ed4Y21xi6S88A5nc5oOithKqnkUjTQReYWv-91ctWjjNcnx7D2qAvjVPtsiQemQgdhry6JvLbEvYNjrloyONlR25",
    "notification" : {
        "body" : "The first message from the React Native and Firebase",
        "title" : "React Native Firebase",
        "content_available" : true,
        "priority" : "high"
    },
    "data" : {
        "body" : "The first message from the React Native and Firebase",
        "title" : "React Native Firebase",
        "content_available" : true,
        "priority" : "high"
    }
}
 */
    function sendPushToken($pushToken)
    {
          $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=AAAAMuOwMjs:APA91bEEDyGUa-K_V2q0WINe6HymBNDwMLzArlvWV-nx7xx7L1d9tKgC8oOA-ToCOAKaukn6-2XG5QAjP1rDDkqIV4klEFes3oeSOHb6U-cheoqmGywczpaFOI8epfm0U1YAzrUkDuiw',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
    $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
        'form_params' => [
             "to" => $pushToken,
    "notification" => [
        "body" => "New Assigned Trip",
        "title" => "New Trip",
        "content_available" => true,
        "priority" => "high"
    ],
    "data" => [
        "body" => "New Assigned Trip",
        "title" => "New Trip",
        "content_available" => true,
        "priority"=> "high"
    ],
        ]
    ]);

    $response = $response->getBody()->getContents();
    //echo '<pre>';
   // print_r($response);
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

     function userIdToName($id){
        $user = User::where('id',$id)->first();
         return $user->name;
    }

}
