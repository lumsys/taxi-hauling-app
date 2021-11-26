<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/business-index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'checkbox' =>'accepted',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $email = $data['email'];
        $name = $data['name'];
        $password = $data['password'];

         Mail::send('emails.staffregistration',  [
            'email' => $email, 
            'name' => $name,
            'password' => $password 
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Welcome to SmoothRide');
            }); 

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => preg_replace('/^0/','+234',$data['phone']),
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'unique_code' => str_random(5),
            'plan' => $data['plan'],
            'userType' => 'business',
            'company' => $this->extractDomain($data['email']),
        ]);
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
}
