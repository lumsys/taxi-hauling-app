 @extends('layouts.business')

@section('title')
All Trips
@endsection
@section('css')
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
<div class="row">
   
                <div class="col-md-4">
                     <form action="{{route('branch.searchtrip')}}" method="POST" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                    <div class="form-group">
                    <span>From:</span>   <input type="date" name="first" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                  <span>To:</span>  <input type="date" name="second" class="form-control">
                </div>
                <div class="col-md-4">
                     <div class="form-group" align="center">
                        <button type="submit" name="filter" id="filter" class="btn btn-info">Filter</button>
                    </form>
                       <a href="{{route('branch.branchtrips')}}" class="btn btn-default">Reset</a>   
                    </div>
                </div>
        
</div>

 <div class="element-wrapper">
                                    <div class="element-box">
                                        <div style="overflow-x: scroll; width: 90%" class="table-responsive">
                                            <table id ="example" class="table table-lightborder">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns:[ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            'colvis'
        ]
            } );
        } );
    </script>
@endsection