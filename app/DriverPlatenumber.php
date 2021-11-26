<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverPlatenumber extends Model
{
     protected $fillable = [
       'user_id','region_id','platenumber', 'business_code','parent_code'
    ];

      public function user()
	{
    	return $this->belongsTo('App\User');
	}

	   public function region()
	{
    	return $this->belongsTo('App\Region');
	}
}
