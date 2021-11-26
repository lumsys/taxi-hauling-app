<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverRequest extends Model
{
     protected $fillable = [
       'user_id','userType', 'reason','business_code','status'
    ];

     public function driver()
	{
    	return $this->belongsTo('App\User', 'user_id','id');
	}
}
