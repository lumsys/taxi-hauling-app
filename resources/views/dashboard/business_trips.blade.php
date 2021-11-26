 @extends('layouts.dash')

@section('title')
Business Trips
@endsection
 
@section('content')
 <div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="table-responsive">
                                            <table id ="example" class="table table-darkborder table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Staff Name</th>
                                                        
                                                        <th class="text-center">Pickup address</th>
                                                        <th class="text-center">Drop address</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Distance</th>
                                                         <th class="text-center">Time</th>
                                                        <th class="text-center">Start Time</th>
                                                        <th class="text-center">End Time</th>
                                                        <th class="text-center">Wait Start Time</th>
                                                        <th class="text-center">Wait End Time</th>
                                                        <th class="text-center">Wait Time</th>
                                                        <th class="text-center">Cost of Waiting</th>
                                                        <th class="text-center">Driver</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    @foreach($trips as $trip)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block smaller lighter">{{$trip->user['name']}}</span></div>
                                                        </td>
                                                       
                                                        <td class="text-center"><span class="smaller lighter">{{$trip->pickUpAddress}}</span></td>
                                                        <td class="text-center"><span class="smaller lighter">{{$trip->destAddress}}</span></td>
                                                        <td class="text-center"><span class="smaller lighter">&#8358; {{ number_format($trip->tripAmt,2) }}</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{$trip->tripDist/1000}}km</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{ gmdate("H:i:s",$trip->travelTime)}}</span></td>
                                                       <td class="text-center"><span class="smaller lighter">  {{ \Carbon\Carbon::parse($trip->tripEndTime)->subSeconds($trip->travelTime) }}
                                                            <!-- {{ \Carbon\Carbon::parse($trip->trip_start_time) }}-->
                                                         </span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{\Carbon\Carbon::parse($trip->tripEndTime)}}</span></td>
                                                         <td class="text-center"><span class="smaller lighter"> {{ $trip->wait_time_start }}</span></td>
                                                          <td class="text-center"><span class="smaller lighter"> {{ $trip->wait_time_end}}</span></td>
                                                         <td class="text-center"><span class="smaller lighter"> {{gmdate("H:i:s",$trip->wait_time)}}</span></td>
                                                          <td class="text-center"><span class="smaller lighter"> {{ $trip->cost_wait }}</span></td>
                                                           <td class="text-center smaller lighter">{{$trip->driver['name']}}</td>
                                                </tr> 
                                    @endforeach   
                                                    
                                                </tbody>
                                            </table>
											<div class="controls-below-table">
                                              {{$trips->links()}}
                            <!--                <div class="table-records-info">Showing records 1 - 6</div>
                                            <div class="table-records-pages">
                                                <ul>
                                                    <li><a href="#">Previous</a></li>
                                                    <li><a class="current" href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">Next</a></li>
                                                </ul>
                                            </div> -->
                                        </div>
                                        </div>
                                    </div>
                                </div>


@endsection
