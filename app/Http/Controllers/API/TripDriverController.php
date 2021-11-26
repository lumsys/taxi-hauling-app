<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Trip;
use App\Config;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\User;
use Carbon\Carbon;
use App\DriverRequest;
use App\LogRequest;
use App\error_logg;

class TripDriverController extends Controller
{ 
    public function getDriverTrip(Request $request)
    {
        $users = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.staffId')
             ->join('configs', 'users.business_code', '=', 'configs.unique_code')
            ->select('users.*', 'trips.*','configs.*','trips.id as trip_id')->where(['trips.driverId' => $request->user()->id,'trips.flag'=> 0])->whereNotNull('trips.tripAmt')->whereNull('trips.deleted_at')->orderBy('trips.id', 'desc')->get();
        return response()->json($users);
       
    }

     public function getTripDetails($id)
    {
        $trip = Trip::where('id', $id)->first();
 
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json($trip);
    }

    public function requestRide(Request $request)
    {
        $user = $request->user();
       
         $business = User::where('unique_code',$user->business_code)->first();

        if( $business->autoApprove)
            {
                 $driver =  User::where(['userType' => 'driver', 'business_code' => $business->unique_code,'isAvailable' => 'online'])->whereNull('currTripState')->orderBy('updated_at')->first();
  
       if(is_null($driver))
       {

        $drivers = User::where([
                'userType'=>'driver',
                'unique_code'=>$user->business_code,
                'currTripState'=>'assign'])->get();
        //$bizcode=$drivers->business_code;
        $staff = user::where("'business_code'=> $business->unique_code and 'usertype'='staff'")->get();
       return response()->json(['success' => 'nodriver','message' => 'No Driver Available'], 500);
       }else {
      
            $trip = new Trip();
            $trip->staffId = $user->id;
            $trip->staffName = $this->userIdToName($user->id);
            $trip->business_code = $user->business_code;
            $trip->driverId = $driver->id;
            $trip->driverName = $this->userIdToName($driver->id);
            $trip->tripRequest = 'approved';
            $trip->parent_code = $this->businesscodeToParentCode($user->business_code);
            $trip->purpose = $request->purpose;
            $trip->save();
        User::where('id',$driver->id)->update(['currTripState'=>'assign']);
      
    $this->sendSmsMessage($driver->phone,'A Trip has been assigned to you, kindly check your app');
     $this->sendSmsMessage($request->user()->phone,'Your trip was auto-approved, kindly check your app');
    $this->initiatePushToken2($request->user()->pushToken);
    $this->initiatePushToken2($driver->pushToken); 
       return response()->json([
                'success' => 'approved',
                'data' => $trip->toArray()
            ]);
       }
            } 
            else {

      $tripcheck = Trip::where(['staffId'=>$user->id,'tripRequest' => 'pending'])->orderBy('id', 'desc')->first();
      if(!is_null($tripcheck))
      {
         return response()->json([
                'success' => 'pending',
                'message' => 'There is a pending trip'
            ], 500);
      }
      
        $trip = new Trip();
        $trip->tripRequest = 'pending';
        $trip->purpose = $request->purpose;
        if($user->userType == 'driver')
        {
           $trip->driverId = $user->id;
           $trip->driverName = $this->userIdToName($user->id);
        }
        else 
        {
            $trip->staffId = $user->id;
            $trip->staffName = $this->userIdToName($user->id);
            $trip->business_code = $user->business_code;
            $trip->parent_code = $this->businesscodeToParentCode($user->business_code);
        }
        
        $business = User::where('unique_code',$user->business_code)->first();
        $email = $business->email;
        $name =   $business->name;
 
        if ($trip->save())
         {
                if($user)
                {
                     $this->sendSmsMessage($user->phone,'Trip Request was successful');
                  
                }

                if($business->phone)
                {
                    $this->sendSmsMessage($business->phone,'Kindly approve a staff trip request, click here to login https://smoothride.ng/taxi/login');
                }

             Mail::send('emails.stafftriprequest',  [
            'email' => $email, 
            'name' => $name,
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('SmoothRide: Trip Request');
            }); 

            return response()->json([
                'success' => true,
                'data' => $trip->toArray()
            ]);
         }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Trip could not be created'
            ], 500);
         }
        }
    }

    public function getLastAssignTrip(Request $request)
    
    {
        ini_set('max_execution_time',600);
        ini_set('memory_limit', '-1');
        
        $trip = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.staffId')
            ->select('users.business_code as ubusiness_code','users.*', 'trips.*')->whereNull('trips.srcLat')->whereNull('trips.deleted_at')->where(['trips.driverId'=> $request->user()->id, 'tripRequest' => 'approved' ])->orderBy('trips.id','DESC')->first();
         $driverdetails = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.driverId')
            ->select('users.*', 'trips.*')->whereNull('trips.srcLat')->whereNull('trips.deleted_at')->where(['trips.driverId'=> $request->user()->id, 'tripRequest' => 'approved' ])->orderBy('trips.id','DESC')->first();
          
          if(!is_null($trip))
          {
             $config = Config::where('unique_code', $trip->ubusiness_code)->first();
          } else {
             $config = null;
          }
          
        return response()->json(['config' => $config,'data' => $trip,'driverdetails'=> $driverdetails, 'success'=> true]);
    }

    public function updateTripCompleted(Request $request, $id)
    {

        $input = $request->all();
        $input['pickUpAddress'] = $this->getAddressFromLatLong($request->srcLat,$request->srcLong);
        $input['destAddress'] = $this->getAddressFromLatLong($request->destLat,$request->destLong);
        $input['trip_start_time']=date('D M j Y G:i:s',strtotime($request->trip_start_time)).' GMT+0100 (WAT)';
        $input['tripAmt'] = $request->tripAmt >500 ? $request->tripAmt: 500;

        if(!is_null($id))
        {
          $trip = Trip::where('id', $id)->first();
          $updated = $trip->fill($input)->save();
          $amount =  $trip->tripAmt;
          
$uniquee_trips = trip::where('driverid', $request->user()->id)->where('trip_start_time',date('D M j Y G:i:s',strtotime($request->trip_start_time)).' GMT+0100 (WAT)')->count();
        if ($uniquee_trips > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Trip has been saved'
            ]);

        }else{
            //
        }

         if (($updated) && ($request->srcLat != $request->destLat) && ($request->srcLong != $request->destLong) && ($amount <= 7000)){
            $get_user = User::where('id',$request->user()->id)->first();
        
                $this->initiatePushToken($trip->user->pushToken, round($amount,2),'Trip Details');
                
                $email = $trip->user->email;
                try {
                    Mail::send('emails.tripreceipt',  [
                        'pickUpAddress' => $input['pickUpAddress'], 
                        'destAddress' => $input['destAddress'],
                        'tripAmt' => $input['tripAmt'],
                        'name' => $trip->user->name,
                    ], function ($message) use ($email) {
                        $message->to($email);
                        $message->subject('SmoothRide: Trip');
                    }); 
                } catch (\Throwable $th) {
                    $error = new error_logg();
                    $error->trip_id = $get_user->id;
                    $error->date = Carbon::now()->toDateTimeString(); 
                    $error->error_log = "unable to send mail";
                    $error->save();
                }
                
            return response()->json([
                'success' => true
            ]);
        }
        else {
            Trip::where('id',$id)->update(['flag' =>1]);
            return response()->json([
                'success' => true,
                'message' => 'Trip could not be updated and is flagged'
            ]);
        }
    } else
    {
       $newtrip = Trip::create(['tripRequest' => 'approved']);
        if ($newtrip){
            return response()->json([
                'success' => true
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Trip could not be created'
            ], 500);
        }

    }
       
    }
    
     public function getAssignedDriver(Request $request)
    { //drivers and trips details to rider/staff
        $trip = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.staffId')
            ->select('users.*', 'trips.*')->whereNull('trips.srcLat')->whereNull('trips.deleted_at')->where(['trips.staffId'=> $request->user()->id, 'tripRequest' => 'approved' ])->orderBy('trips.id','DESC')->first();
         $driverdetails = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.driverId')
            ->select('users.*', 'trips.*')->whereNull('trips.srcLat')->whereNull('trips.deleted_at')->where(['trips.staffId'=> $request->user()->id, 'tripRequest' => 'approved' ])->orderBy('trips.id','DESC')->first();
            
        return response()->json(['data' => $trip,'driverdetails'=> $driverdetails, 'success'=> true]);
    }

     public function getRiderTrip(Request $request)
    {
        
        $ridertrips = DB::table('users')
            ->join('trips', 'users.id', '=', 'trips.staffId')
            ->select('users.*', 'trips.*')->where(['trips.staffId'=> $request->user()->id,'trips.flag'=> 0])->whereNotNull('trips.srcLat')->whereNull('trips.deleted_at')->orderBy('trips.id', 'desc')->get();
        return response()->json($ridertrips);
       
    }

     function sendPushToken($pushToken,$body,$title)
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
        "body" => $body,
        "title" => $title,
        "content_available" => true,
        "priority" => "high"
    ],
    "data" => [
        "body" => $body,
        "title" => $title,
        "content_available" => true,
        "priority"=> "high"
    ],
        ]
    ]);

    $response = $response->getBody()->getContents();
   
    }

     public function initiatePushToken($pushToken,$body,$title){
    $isError = 0;
    $errorMessage = true;

    //Preparing post parameters
    $postData = array(
       'to' => $pushToken,
    'notification' => array(
        'body' => "Your Trip Amount is"." ₦".$body,
        'title' => $title,
        'content_available' => true,
        'priority' => 'high'
    ),
    'data' => array(
        'body' => $body,
        'title' => $title,
        'content_available' => true,
        'priority'=> 'high'
    )
    );
 
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
}

 public function initiatePushToken2($pushToken){
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
 
}
 
  public function initiatePushTokenMessage($pushToken,$body,$title){
    $isError = 0;
    $errorMessage = true;

    //Preparing post parameters
    $postData = array(
       'to' => $pushToken,
    'notification' => array(
        'body' => $body,
        'title' => $title,
        'content_available' => true,
        'priority' => 'high'
    ),
    'data' => array(
        'body' => $body,
        'title' => $title,
        'content_available' => true,
        'priority'=> 'high'
    )
    );
 
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
}

    function getAddressFromLatLong($lat, $long)
    {
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyAsjKM16fbsmVRNU4jlrhn3yinTyu3z5JU";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response);
        $dataarray = get_object_vars($output);
        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if (isset($dataarray['results'][0]->formatted_address)) {

                $address = $dataarray['results'][0]->formatted_address;

            } else {
                $address = 'Not Found';

            }
        } else {
            $address = 'Not Found';
        }

        return $address; 
    }

     public function subsequentTrip(Request $request,$riderid)
    {
        $rider= User::find($riderid);
        $input = $request->all();
        $input['staffId'] = $riderid;
        $input['staffName'] = $this->userIdToName($riderid);
        $input['driverId'] = $request->user()->id;
        $input['driverName'] = $this->userIdToName($request->user()->id);
        $input['tripRequest'] = 'approved';
        $input['business_code'] = $rider->business_code;
        $input['parent_code'] = $this->businesscodeToParentCode($rider->business_code);
        $input['pickUpAddress'] = $this->getAddressFromLatLong($request->srcLat,$request->srcLong);
        $input['destAddress'] = $this->getAddressFromLatLong($request->destLat,$request->destLong);
        $input['trip_start_time']=date('D M j Y G:i:s',strtotime($request->trip_start_time)).' GMT+0100 (WAT)';
        $input['tripAmt'] = $request->tripAmt >500 ? $request->tripAmt : 500 ;
        $amount = $request->tripAmt;
        
        $unique_trips = trip::where('driverid', $request->user()->id)->where('trip_start_time',date('D M j Y G:i:s',strtotime($request->trip_start_time)).' GMT+0100 (WAT)')->count();
        if ($unique_trips > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Trip has been saved'
            ]);
        }else {
            $newtrip = Trip::create($input);
            if (($newtrip) && ($request->srcLat != $request->destLat) && ($request->srcLong != $request->destLong) && ($amount <= 7000)){
            
                    $this->initiatePushToken($newtrip->user->pushToken, round($input['tripAmt'],2),'Trip Details');

                    $email = $rider->email;
                try{
                Mail::send('emails.tripreceipt',  [
                'pickUpAddress' => $input['pickUpAddress'], 
                'destAddress' => $input['destAddress'],
                'tripAmt' => $input['tripAmt'],
                'name' => $rider->name,
                ], function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('SmoothRide: Trip');
                });

            } catch (\Throwable $th) {
                $error = new error_logg();
                $error->trip_id = $newtrip->id;
                $error->date = Carbon::now()->toDateTimeString(); 
                $error->error_log = "unable to send mail";
                $error->save();
            }       
                return response()->json([
                    'success' => true
                ]);
            }
            else {
                Trip::where('id',$newtrip->id)->update(['flag' =>1]);
                return response()->json([
                    'success' => true,
                    'message' => 'Trip could not be created and is flagged'
                ]);
            }
        }
        
    }


    public function cancelTrip(Request $request)
    {
        $trip = Trip::whereNull('srcLat')->where(['staffId' => $request->user()->id ])->first();

        if($trip)
        {
            $get_user = User::where('id',$trip->driverId)->first();
             if($get_user)
             {
                if($get_user->currTripState != 'ontrip')
                {
                    $trip->delete();
                   User::where('id',$trip->driverId)->update(['currTripState'=> null]); 
                   $this->initiatePushTokenMessage($get_user->pushToken,'Trip has been cancelled','cancel');
                   LogRequest::create(
                        [
                             'user_id' => $request->user()->id,
                             'userType' => 'staff', 
                             'reason' => 'staff cancelled the trip',
                             'trip_id' => $trip->id,
                        ]);
                   $this->sendSmsMessage($get_user->phone,'Trip has been cancelled');
                      return response()->json([
                        'success' => true,
                        'message' => 'Trip deleted'
                    ], 500);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Driver is on trip'
                    ], 500);
                }
            } else {    
                 $trip->delete();
                      return response()->json([
                        'success' => true,
                        'message' => 'Driver details cannot be found'
                    ], 500);
            }
      } else {

        return response()->json([
                        'success' => false,
                        'message' => 'You cannot cancel trip'
                    ], 500);
      }
    }

    public function test($lat, $long)
    {
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyAsjKM16fbsmVRNU4jlrhn3yinTyu3z5JU";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response);
        $dataarray = get_object_vars($output);
        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if (isset($dataarray['results'][0]->formatted_address)) {

                $address = $dataarray['results'][0]->formatted_address;

            } else {
                $address = 'Not Found';

            }
        } else {
            $address = 'Not Found';
        }

        return $address;

    }

    public function test2()
    {
         $to = '+2348113975330';
      $message = 'You are doing good, click here to login https://smoothride.ng/taxi/login';
     //   $this->sendSmsMessage($to,$message);
       // return 1;
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
             "from" => 'SmoothRide'
        ]
    ]);

    $response = $response->getBody()->getContents();
   
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
             "from" => 'SmoothRide'
        ]
    ]);

    $response = $response->getBody()->getContents();
   
    }

    function businesscodeToParentCode($business_code){
        $user = User::where(['unique_code' => $business_code])->first();
        return $user->business_code ? $user->business_code : $business_code ;
    }

    function userIdToName($id){
        $user = User::where('id',$id)->first();
         return $user->name;
    }

    public function allBusinessAccount()
    {
        $users = User::where('userType','business')->get();
        return response()->json($users);
    }

    public function businessTripSummary($unique_code)
    {
        $dt = Carbon::now();
        $date = Carbon::today()->subDays(14);
       $user = User::where('unique_code',$unique_code)->first();
         $totaltripAmt =  Trip::whereNotNull('tripAmt')->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
         $totaltripAmtDay =  Trip::whereNotNull('tripAmt')->whereDay('created_at', date("d"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');

          $totaltripsDay =  Trip::whereNotNull('tripAmt')->whereDay('created_at', date("d"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->count();

          $totaltraveltimeDay =  Trip::whereNotNull('tripAmt')->whereDay('created_at', date("d"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('travelTime');

           $totaldistanceDay =  Trip::whereNotNull('tripAmt')->whereDay('created_at', date("d"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripDist');

          $totaltripAmtMonth =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtMonthPrev =  Trip::whereNotNull('tripAmt')->whereMonth('created_at',date("m")-1)->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
           $totaltripAmtYear =  Trip::whereNotNull('tripAmt')->whereYear('created_at',date("Y"))->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
            $totaltripAmtWeek = Trip::whereNotNull('tripAmt')->where(\DB::raw("WEEKOFYEAR(created_at)"), $dt->weekOfYear)->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
            $totaltripxDays = Trip::whereNotNull('tripAmt')->where('created_at', '>=', $date)->where(['business_code' => $unique_code, 'tripRequest' => 'approved'])->sum('tripAmt');
        return response()->json(['user' => $user, 'total' => $totaltripAmt, 'day' => $totaltripAmtDay,'month' => $totaltripAmtMonth,'premonth' => $totaltripAmtMonthPrev,'year' => $totaltripAmtYear,'week' => $totaltripAmtWeek,'xdays' => $totaltripxDays, 'tripsday' => $totaltripsDay, 'traveltimeday'=> $totaltraveltimeDay, 'distanceday' => $totaldistanceDay]);
    }

    public function listOfDrivers(Request $request)
    {
        $user = User::where('id',$request->user()->id)->first();

         if(!is_null($user))
         {  
             $drivers = User::where(['business_code' => $user->business_code, 'userType' => 'driver', 'isAvailable' => 'online' ])->whereNull('currTripState')->get();
            return response()->json(['success' => true, 'drivers' => $drivers]);
         } else 
         {
            return response()->json(['success' => false, 'drivers' => null]);
         }
       
    }

    public function driverRequestState(Request $request)
    {

        $input = $request->all();
        $user = User::where('id',$request->user()->id)->first();
        $business = User::where('unique_code',$user->business_code)->first();

         if(is_null($user))
         {
           return response()->json(['success' => false, 'message' => 'no driver']);
         }

        if($user->isAvailable == 'online')
        {

          DriverRequest::create(['user_id' => $user->id , 'userType' => $user->userType , 'reason' => $request->reason , 'business_code' => $user->business_code]); 

          $this->sendSmsMessage($business->phone,'Dear Admin,'. $user->name.', the driver request to go offline');

           return response()->json(['success' => true, 'message' => 'request state submitted awaiting approval']);
        } else 
        {
           //  $driver = User::where('id', $request->user()->id)->first();
            $updated = $user->fill($input)->save();
              $this->sendSmsMessage($business->phone,'Dear Admin,'.$user->name.', the driver is back online now!');
            return response()->json(['success' => true, 'message' => 'request state changed to online']);
        }
        
    }

    public function driverDeclineTrip(Request $request)
    {
         $id = $request->trip_id;
      if(!$id)
       {
        return response()->json(['success' => false, 'message' => 'No trip id'],400);
       }
        if($request->reason == 'reassign'){
        $driver =  User::where(['userType' => 'driver', 'business_code' => $request->user()->business_code,'isAvailable' => 'online'])->whereNotIn('id',[$request->user()->id])->whereNull('currTripState')->orderBy('updated_at')->first();

          if(is_null($driver))
       {
        return response()->json(['success' => false, 'message' => 'No driver']);
       }else {

        Trip::where('id',$id)->update(['tripRequest' =>'approved','driverId' => $driver->id]);

        User::where('id',$driver->id)->update(['currTripState'=>'assign']);
        User::where('id',$request->user()->id)->update(['currTripState'=> null]);
       $trip = Trip::find($id);
       $staff = User::find($trip->staffId);
      
       LogRequest::create(
        [
             'user_id' => $request->user()->id,
             'userType' => 'driver', 
             'reason' => 'driver declined the trip and was reassigned',
             'trip_id' => $request->trip_id ,
        ]);

       $this->initiatePushTokenMessage($staff->pushToken,'Trip has been assigned to another driver','Trip Reassigned');
       
         $this->sendSmsMessage($driver->phone,'A Trip has been assigned to you, kindly check your app');
     
        $this->initiatePushToken2($driver->pushToken); 
       return response()->json(['success' => true, 'message' => 'trip reassigned successfully']);
       }
        }

        if($request->reason == 'decline'){

            $trip = Trip::find($id);
            if(!$trip)
            {
             User::where('id',$request->user()->id)->update(['currTripState'=> null]);

             return response()->json(['success' => true, 'message' => 'trip decline successfully']);
             } else {
                return response()->json(['success' => false, 'message' => 'Why na! is better to reassign the trip']);
             }
        }
    }

     public function getTripStatus($id)
    {
       $trip = Trip::find($id);
            if(!$trip)
            {
             return response()->json(['success' => false, 'message' => 'Trip with id'.$id.'not found']);
             } else {
                return response()->json(['success' => true, 'message' => 'Trip with id'.$id.'found']);
             }
    }

}
