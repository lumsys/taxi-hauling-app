<?php

namespace App\Exports;

use App\Trip;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Auth;

class TripsViewExport implements FromView
{
	 private $alltrips;

    public function __construct($alltrips)
    {
        $this->alltrips = $alltrips;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
    	
      
        return view('business.businessbranchtrips4',[
        	'alltrips' => $this->alltrips 
        ]);
    }
}
