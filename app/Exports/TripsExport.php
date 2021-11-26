<?php

namespace App\Exports;

use App\Trip;
use App\User;
use Carbon\Carbon;
use Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripsExport implements FromQuery, WithHeadings, WithMapping
{
     use Exportable;

    private $parent_trips;

    public function __construct($parent_trips)
    {
        $this->parent_trips = $parent_trips;
    }

    public function query()
    {
        return $this->parent_trips;
       // return Trip::query();
    }

    public function headings(): array
    {
        return [
            
            'StaffName',
            'DriverName',
            'Branch',
            'pickUp Address',
            'Dropoff Address',
            'Trip Amount',
            'Distance (km)',
            'Start Time',
            'End Time',
            //'Travel Time',
            'Purpose',
            'Cost Wait',
            'Wait Time',
            'Start Wait Time',
            'End Wait Time',
        ];
    }

    public function map($trip): array
    {
        return [
            
            $trip->user->name ?? '' ,
            $trip->driver->name ?? '',
            $trip->branch->name ?? 'HQ',
            $trip->pickUpAddress ?? '',
            $trip->destAddress ?? '',
            //number_format($trip->tripAmt,2) ?? '',
            floatval(preg_replace('/[^\d.]/', '', $trip->tripAmt,2))?? '',
            ($trip->tripDist/1000)  ?? '',
             \Carbon\Carbon::parse($trip->tripEndTime) ?? '',
            \Carbon\Carbon::parse($trip->tripEndTime)->addSeconds($trip->travelTime) ?? '',
            //gmdate("H:i:s",$trip->travelTime) ?? '',
            $trip->purpose ?? '',
            $trip->cost_wait ?? '',
            gmdate("H:i:s",$trip->wait_time) ?? '',
            $trip->wait_time_start ?? '',
            $trip->wait_time_end ?? '',
            
        ];
    }

}
