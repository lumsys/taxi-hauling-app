 @extends('layouts.business')

@section('title')
Staffs Trips Request
@endsection
@section('content')

<div class="element-wrapper">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                     <div style="line-height: 25%;">
                        <p> Drivers Assigned to {{Auth::user()->name}} :{{$driver_assigned->count()}} --> @foreach($driver_assigned as $driver_assigne)<span>{{$driver_assigne->name}} &nbsp; &nbsp;| &nbsp; &nbsp;</span>@endforeach</p>
                         <p>Available Drivers:{{$driver_onlines->count()}} --> @foreach($driver_onlines as $driver_online)<span>{{$driver_online->name}} &nbsp; &nbsp;| &nbsp; &nbsp;</span>@endforeach</p>
                        <p>  Drivers On Trip:{{$driver_ontrips->count()}}  --> @foreach($driver_ontrips as $driver_ontrip)<span>{{$driver_ontrip->name}} &nbsp; &nbsp;| &nbsp; &nbsp;</span>@endforeach </p>
                        <p>Offline Drivers:{{$driver_offlines->count()}}  --> @foreach($driver_offlines as $driver_offline)<span>{{$driver_offline->name}} &nbsp; &nbsp;| &nbsp; &nbsp;</span>@endforeach</p>
                    </div>
                           
                                    <div class="element-box">
                                        <div class="table-responsive">
                                            <table class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Staff Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Phone</th>
                                                         <th class="text-center">Purpose</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center"> </th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            @foreach($triprequests as $triprequest)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block smaller ">{{$triprequest->user['name']}}</span></div>
                                                        </td>
                                                        <td class="text-center smaller  ">{{$triprequest->user['email']}}</td>
                                                        <td class="text-center"><span class="smaller  ">{{$triprequest->user['phone']}}</span></td>
                                                         <td class="text-center smaller">{{$triprequest->purpose}}</td>
                                                        
                                                        <td class="text-center smaller">{{$triprequest->tripRequest}}</td>
                                                        <td class="text-center smaller">{{$triprequest->created_at}}</td>
                                                    @if($triprequest->tripRequest == 'pending')
                                                        <td class="text-center smaller"><a class="mr-2 mb-2 btn btn-primary btn-sm" href="{{route('branch.approvetriprequest',$triprequest->id)}}">Approve</a></td>
                                                    @endif
                                                    <td class="text-center smaller"><a class=" mb-2 btn btn-danger btn-sm" href="{{route('branch.declinetriprequest',$triprequest->id)}}" onclick="return confirm('Are you sure you want to delete this?');">Decline</a></td>

                                                    </tr>
                            @endforeach                        
                                                </tbody>
                                            </table>
                                            <div class="controls-below-table">
                                                {{$triprequests->links()}}
                                          <!--
                                            <div class="table-records-info">Showing records 1 - 6</div>
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

  <script src="{{asset('first/bower_components/jquery/dist/jquery.min.js')}}"></script>
   <script src="{{asset('first/bower_components/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('first/bower_components/moment/moment.js')}}"></script>
    <script src="{{asset('first/bower_components/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{asset('first/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('first/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js')}}"></script>
    <script src="{{asset('first/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap-validator/dist/validator.min.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('first/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('first/bower_components/dropzone/dist/dropzone.js')}}"></script>
     <script src="{{asset('first/bower_components/editable-table/mindmup-editabletable.js')}}"></script>
    <script src="{{asset('first/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('first/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('first/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('first/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{asset('first/bower_components/tether/dist/js/tether.min.js')}}"></script>
    <script src="{{asset('first/bower_components/slick-carousel/slick/slick.min.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/util.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/alert.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/button.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/carousel.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/collapse.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/dropdown.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/modal.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/tab.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/tooltip.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/popover.js')}}"></script>
    <script src="{{asset('first/js/demo_customizer4a76.js?version=4.3.0')}}"></script>
    <script src="{{asset('first/js/main4a76.js?version=4.3.0')}}"></script>
@endsection