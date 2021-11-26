<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLogg extends Model
{
    //

protected $fillable = [
        'user_id', 'name', 'date', 'activity'
    ];

public function user()
{
  return $this->belongTo(user::class);
}

}
