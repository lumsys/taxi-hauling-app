<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackerTrip extends Model
{
     use SoftDeletes;

      protected $fillable = [
       'user_id','userType', 'business_code','amount','date'
    ];

     protected $dates = ['deleted_at'];

      public function user()
	{
    	return $this->belongsTo('App\User', 'business_code','unique_code');
	}
}
