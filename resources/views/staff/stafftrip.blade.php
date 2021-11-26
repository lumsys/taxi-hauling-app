 @extends('layouts.business')

@section('title')
All Trips
@endsection
@section('css')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection
@section('content')
 <div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="table-responsive">
                                            <table class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Staff Name</th>
														<th class="text-center">Driver</th>
                                                        <th class="text-center">Pickup address</th>
                                                        <th class="text-center">Drop address</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Distance</th>
                                                         <th class="text-center">Time</th>
                                                          <th class="text-center">Booking Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    @foreach($trips as $trip)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block smaller lighter">{{$trip->user['name']}}</span></div>
                                                        </td>
                                                        <td class="text-center smaller lighter">{{$trip->driver['name']}}</td>
                                                        <td class="text-center"><span class="smaller lighter">{{$trip->pickUpAddress}}</span></td>
														<td class="text-center"><span class="smaller lighter">{{$trip->destAddress}}</span></td>
														<td class="text-center"><span class="smaller lighter">&#8358; {{ number_format($trip->tripAmt,2) }}</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{$trip->tripDist}}km</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{ gmdate("H:i:s",$trip->travelTime)}}</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{$trip->created_at}}</span></td>
                                   @endforeach                 </tr> 
                                                    
                                                </tbody>
                                            </table>
											<div class="controls-below-table">
                                              
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
@section('script')
 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
  
@endsection