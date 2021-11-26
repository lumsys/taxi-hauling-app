<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Trip;
use Carbon\Carbon;
use App\Config;
use Illuminate\Support\Facades\Mail;
use App\LogRequest;
use App\AppSetting;

class AuthController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
           // 'password' => bcrypt($request->password),
       		'userType' => 'staff',
     		'company' => $this->extractDomain($request->email),
        ]);
 
        $token = $user->createToken('smoothride')->accessToken;
 
        return response()->json(['access_token' => $token,'token_type' => 'Bearer'], 200);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
      /*  $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('smoothride')->accessToken;
           // $expiration = $token->token->expires_at->diffInSeconds(Carbon::now());
           $expiration = 1;
            return response()->json(['access_token' => $token,'token_type' => 'Bearer','expires_in'=> $expiration], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        } */

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(!auth()->attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('smoothride');
        $token = $tokenResult->token;
      //  if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(4);
        $token->save();

         $get_user = User::where('email',$request->email)->first();
         $updated = $get_user->fill(['pushToken' => $request->pushToken])->save();

        $appsetting_google_map = AppSetting::where('key','google_maps_key')->first();
        $appsetting_version = AppSetting::where('key','version')->first();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(), 
            'user' => $user,
            'google_maps_key' => $appsetting_google_map ,
            'version' => $appsetting_version
        ]);
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    function extractDomain($email)
    {
		$result = substr(strrchr($email, "@"), 1);
		$arr = explode(".",$result);
		return $arr[0];
	}

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


   public function updateTripStatus(Request $request)
     {  
             $get_user = User::where('id',$request->user()->id)->first();
             
          $updated = $get_user->fill(['currTripState' => 'ontrip'])->save();

          if ($updated){

            $trip = Trip::where('id', $request->trip_id)->first();
            $user = User::where('id',$trip->staffId)->first();
            $this->initiatePushTokenMessage($user->pushToken,'Driver has started trip','On Trip');
            return response()->json([
                'success' => true
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'User status could not be updated'
            ], 500);
        }
    }

     public function updateDriverStatus(Request $request)
     {  
             $get_user = User::where('id',$request->user()->id)->first();
          $updated = $get_user->fill(['isAvailable' => $request->isAvailable ])->save();
          if ($updated){
          
            return response()->json([
                'success' => true
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'User status could not be updated'
            ], 500);
        }
    }

    public function exitTripwithRider(Request $request)
    {
           $get_user = User::where('id',$request->user()->id)->first();

           if(!is_null($request->rider_id)){
           $user = User::where('id',$request->rider_id)->first();
            //push notification to rider that a driver has ended the trip 
          $this->initiatePushTokenMessage($user->pushToken,'Driver has ended trip','Trip Completed');
          //push notification to driver that he has ended the trip completely
          $this->initiatePushTokenMessage($get_user->pushToken,'Driver has ended trip','Trip Completed');
          }

          $updated = $get_user->fill(['currTripState' => null ])->save();
          if ($updated){
            return response()->json([
                'success' => true
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'exit trip with rider failed'
            ], 500);
        }
    }

     public function getUserwithConfig(Request $request, $id)
    {
           $get_user = User::where('id',$id)->first();
           $config = Config::where('unique_code', $get_user->business_code)->first();

          if ($id){
          
            return response()->json([
                'success' => true,
                'user' => $get_user,
                'config' => $config
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'No user id'
            ], 500);
        }
    }

      public function forgotPassword(Request $request)
    {
      $user = User::where('email',$request->email)->first();
          if ($user){
            $pass = str_random(5);
            $email = $user->email;
            $user_updated = $user->fill(['password' => bcrypt($pass)])->save();
           
            Mail::send('emails.forgotpassword',  [
            'password' => $pass,
            'name' => $user->name,
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('SmoothRide: Password Reset');
            }); 

           $this->sendSmsMessage($user->phone,'Your password is: '.$pass);
          
            return response()->json([
                'success' => true
            ]);
        }
        else {
            return response()->json([
                'success' => false
              ]);
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
  }

}
