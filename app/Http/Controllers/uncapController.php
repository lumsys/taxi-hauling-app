<?php

namespace App\Http\Controllers;
use App\Uncap;
use App\User;
use App\Trip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;

use Illuminate\Http\Request;

class UncapController extends Controller
{
    /**


     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!session()->exists('driver')) {
            return redirect('/uncaps/login');
        }else {
            $id=session()->get('driver');
            $driver=user::find($id);
            $bizcode=$driver->business_code;
            $staff = user::whereraw("business_code='$bizcode' and usertype='staff'")->get();
            return view('uncaps.uncap',compact('staff'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $uncaps = Uncap::all();
        return view('uncap',['uncaps'=>$uncaps,'layout'=>'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if (!session()->exists('driver')) {
            return redirect('/');
        }else {
            // insert
            
            $id=session()->get('driver');
            $driver=user::find($id);
            $bizcode=$driver->business_code;
            //$drivrr = User::where('unique_code',$driver->business_code)->first();
            $staff = user::whereraw("business_code='$bizcode' and usertype='staff'")->first();
           

            $this->validate($request,[
                'staff'=>'required',
                'origin'=>'required',
                'startTime'=>'required',
                'destination'=>'required',
                'duration'=>'required',
                'distance'=>'required',
                'endTime'=>'required',
            ]);
            $uncap = new trip();
            $uncap->driverId= $id;
            $uncap->staffName = $this->userIdToUserName($staff->id);
            $uncap->driverName = $this->userIdToUserName($driver->id);
            $uncap->srcLat= $request->input('srclat');
            $uncap->srcLong= $request->input('srclng');
            $uncap->destLat= $request->input('deslat');
            $uncap->destLong= $request->input('deslng');
            $uncap->staffId= $request->input('staff');
            $uncap->pickupAddress= $request->input('origin');
            $uncap->trip_start_time=date('D M j Y G:i:s',strtotime($request->input('startTime'))).' GMT+0100 (WAT)';
            $uncap->destAddress= $request->input('destination');
            $uncap->travelTime= $request->input('duration');
            $uncap->tripDist= $request->input('distance');
            $uncap->tripAmt= ($request->input('distance')*195)/1000;
            $uncap->tripEndTime= date('D M j Y G:i:s',strtotime($request->input('endTime'))).' GMT+0100 (WAT)';
            $uncap->business_code= $driver->business_code;
            $uncap->parent_code= $this->insertParentCode($driver->business_code);
            $uncap->tripRequest= 'approved';
            $uncap->save();
            return redirect('/uncaps/dashboard')->with('success','trip captured sucsessfully');
        }
        
    }


    function userIdToUserName($id){
        $user = User::where('id',$id)->first();
         return $user->name;
    }

    function insertParentCode($busy_code){
        $driver = User::where(['unique_code' => $busy_code])->first();
        return $driver->business_code ? $driver->business_code : $busy_code ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {
        $uncap = Uncap::find($id);
        $uncaps = Uncap::all();
        return view('uncap',['uncaps'=>$uncaps,'uncap'=>$uncap,'layout'=>'show']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uncap = Uncap::find($id);
        $uncaps = Uncap::all();
        return view('uncap',['uncaps'=>$uncaps,'uncap'=>$uncap,'layout'=>'edit']);
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
        $uncap = Uncap::find($id);
        $uncap->driverName= $request->input('driverName');
        $uncap->staffName= $request->input('staffName');
        $uncap->from= $request->input('from');
        $uncap->startTime= $request->input('startTime');
        $uncap->to= $request->input('to');
        $uncap->endTime= $request->input('endTime');
        $uncap->save();
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
               $uncap = Uncap::find($id);
               $uncap->delete();
               return redirect('/');
    }
    //funtion to validate user and login
    public function login(Request $request)
    {
        $this->validate($request,[
            'useremail'=>'required',
            'userpwd'=>'required',
        ]);

        $email=request('useremail');
        $password=request('userpwd');
        $count= user::whereraw("email='$email' and usertype='driver'")->count();
        if ($count>0) {
            $exists=true;
        }else {
            $exists=false;
        }
        if ($exists) {
            $dbpassword=user::whereraw("email='$email' and usertype='driver'")->pluck('password')->first();
            $login=Hash::check($password,$dbpassword);
            if ($login) {
                $id=user::whereraw("email='$email' and usertype='driver'")->pluck('id')->first();
                session()->put('driver',$id);
                return redirect('uncaps/dashboard');
            }else {
                return redirect()->back()->with('msg','invalid login details');
            }
        } else {
            return redirect()->back()->with('msg','invalid login details');
        }
        
        
    }
    //funtion to logout
    public function logout()
    {
        session()->flush();
        return redirect('/uncaps/login');
    }
    //funtion to display login page but redirect to dashboard if already logged in
    public function prelogin()
    {
        if (!session()->exists('driver')) {
            return view('uncaps.login');
        }else {
            return redirect('/uncaps/dashboard');
        }
    }
}
