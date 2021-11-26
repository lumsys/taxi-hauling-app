<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
   use SoftDeletes, HasApiTokens, Notifiable,\OwenIt\Auditing\Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','email','phone', 'password','dob','bloodGroup','address','city','state','country','latitudeDelta','longitudeDelta','userRating','profileUrl','currTripId','currTripState','userType','loginStatus','mobileVerified','emailVerified','otp','isApproved','isAvailable','verified','jwtAccessToken','userCardId','region_id', 'vechilePaperUrl' ,'rcBookUrl', 'licenceUrl', 'mapCoordinates', 'deviceId', 'pushToken', 'lat', 'long', 'plan', 'unique_code', 'business_code','company','autoApprove'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $auditExclude = [
        'currTripState','pushToken','remember_token'
    ];

     public function branch()
    {
        return $this->belongsTo('App\User', 'business_code','unique_code');
    }
 
   /* public function staff()
    {
        return $this->hasMany(Trip::class, 'staffId');
    }

     public function driver()
    {
        return $this->hasMany(Trip::class, 'driverId');
    } */

    public function model_logg()
    {
            return $this->hasMany(ModelLogg::class);

    }

    public function log($message)
{
    $message = ucwords($message);

    //$data =  
    //dd($data);
    ModelLogg::query()->create([
        'user_id' => $this->id,
        'name' => $this->name,
        'date' => Carbon::parse(now())->toDateString(),
        'activity' => "{$this->name} $message"
    ]
       
        );
}

}
