  

                                            <table id ="example"  class="table table-lightborder">
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
                                                         <th class="text-center">Branch</th>
                                                    </tr>
                                                </thead>  
                                                <tbody>
                              
                                    @foreach($alltrips as $trip)
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
                                                         <td class="text-center smaller lighter">{{$trip->branch['name']}}</td>
                                                </tr> 
                                    @endforeach   

                                                    
                                                </tbody>
                                            </table>
                                            
                                               
        