<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

     protected $fillable = [
      'staffId','driverId','srcLat','srcLong','destLat','destLong','pickUpAddress','destAddress','latitudeDelta','longitudeDelta','paymentMode','paymentStatus','tripAmt','tripDist','bookingTime','tripEndTime','travelTime','taxiType','riderRatingByStaff','driverRatingByRider', 'riderReviewByDriver', 'driverReviewByRider', 'seatBooked', 'tripStatus', 'tripIssue', 'roadMapUrl', 'business_code','company','tripRequest','tripPoints','purpose','wait_time_start', 'wait_time_end', 'cost_wait', 'wait_time', 'trip_start_time','staffName','driverName','parent_code'
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
	{
    	return $this->belongsTo('App\User', 'staffId','id');
	}

	 public function driver()
	{
    	return $this->belongsTo('App\User', 'driverId','id');
	}

     public function branch()
    {
        return $this->belongsTo('App\User', 'business_code','unique_code');
    }

    public function error_log()
    {
        return $this->belongsTo('App\error_log', 'driverId','id');
    }

}
