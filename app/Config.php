<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
     protected $fillable = [
       'basefare','perkm', 'permin','unique_code','user_id','waitpermin'
    ];

     public function accountname()
	{
    	return $this->belongsTo('App\User', 'unique_code','unique_code');
	}

}
