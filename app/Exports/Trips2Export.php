<?php

namespace App\Exports;

use App\Trip;
use App\User;
use Carbon\Carbon;
use Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class Trips2Export implements FromQuery
{
     use Exportable;

    public function query()
    {
        return Trip::query()->where('staffId',11);
    }

}
