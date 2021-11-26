<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class error_logg extends Model
{
   //

   protected $fillable = [
    'trip_id', 'id', 'date', 'error_log'
];


  public function trip()
{
return $this->belongTo(trip::class);
}
}
